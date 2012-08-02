<?php
include('constants.php');
ini_set('zend.ze1_compatibility_mode', '0');
include 'inc/PHPExcel.php';
include 'inc/PHPExcel/Writer/Excel2007.php';


$FILTER = getFilter($_GET);

if(!empty($FILTER['id'])) {
	$search_url = SEARCH_URL . "activities/{$FILTER['id']}/?format=json";
	
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$activity = objectToArray($result);
	if(empty($activity)) exit;
	generate_activity_export($activity);
	
} else {
	
	
	$FILTER['limit'] = intval($FILTER['limit']);
	if($FILTER['limit']<=0) $FILTER['limit'] = 20;

	$FILTER['offset'] = intval($FILTER['offset']);
	if($FILTER['offset']<0) $FILTER['offset'] = 0;

	$search_url = SEARCH_URL . "activities/?format=json&organisations=41120&limit={$FILTER['limit']}&offset={$FILTER['offset']}";

	if(!empty($FILTER['query'])) {
		$search_url .= "&query={$FILTER['query']}";
	}

	if(!empty($FILTER['countries'])) {
		$search_url .= "&countries={$FILTER['countries']}";
	}

	if(!empty($FILTER['regions'])) {
		$search_url .= "&regions={$FILTER['regions']}";
	}

	if(!empty($FILTER['sectors'])) {
		$search_url .= "&sectors={$FILTER['sectors']}";
	}

	if(!empty($FILTER['budgets'])) {
		$budgets = explode('|', trim($_REQUEST['budgets']));
		//Get the lowest budget from filter and use this one, all the other are included in the range
		ksort($budgets);
		$search_url .= "&statistics__total_budget__gt={$budgets[0]}";
	}

	if(!empty($FILTER['order_by'])) {
		$search_url .= "&order_by={$FILTER['order_by']}";
	}

	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$objects = $result->objects;
	$activities = objectToArray($objects);
	if(empty($activities)) exit;
	generate_search_export($activities);
}

function generate_activity_export($activity) {

	$objPHPExcel = new PHPExcel();

	$author = $_REQUEST['author'];

	$objPHPExcel->getProperties()->setCreator( $author );
	$objPHPExcel->getProperties()->setLastModifiedBy( $author );
	$objPHPExcel->getProperties()->setTitle($activity['titles'][0]['title']);
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
	$row=1;
	$objPHPExcel->getActiveSheet()->mergeCells("A{$row}:D{$row}");
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", $activity['titles'][0]['title']);
	
	$fill = array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'DADADA') );
	$objPHPExcel->getActiveSheet()->getStyle("A{$row}")->getFill()->applyFromArray($fill);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setSize(14);
	$objPHPExcel->getActiveSheet()->getStyle("A{$row}:D{$row}")->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Countries:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	$sep = '';
	$countries = '';
	if(empty($activity['recipient_country'])) {
		$countries = EMPTY_LABEL;
	} else {
		foreach($activity['recipient_country'] AS $country) {
			$countries .= $sep . $country['name'];
			$sep = ', ';
		}
	}
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $countries);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Principal Sector:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	$sep = '';
	$sectors = '';
	if(empty($activity['activity_sectors'])) {
		$sectors = EMPTY_LABEL;
	} else {
		foreach($activity['activity_sectors'] AS $sector) {
			$sectors .= $sep . $sector['name'];
			$sep = ', ';
		}
	}
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $sectors);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'IATI identifier:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['iati_identifier'])) $activity['iati_identifier'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['iati_identifier']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Reporting organisation:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['reporting_organisation']['org_name'])) $activity['reporting_organisation']['org_name'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['reporting_organisation']['org_name']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Start-date:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['start_actual'])) $activity['start_actual'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['start_actual']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Sector code:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['activity_sectors'])) $activity['activity_sectors'][0]['code'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['activity_sectors'][0]['code']);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Last updated:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['date_updated'])) $activity['date_updated'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['date_updated']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Start date planned:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['start_planned'])) $activity['start_planned'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['start_planned']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'End date planned:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['end_planned'])) $activity['end_planned'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['end_planned']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'End date actual:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['end_actual'])) $activity['end_actual'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['end_actual']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Collaboration type:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['collaboration_type']['name'])) {
		$collaboration_type = EMPTY_LABEL;
	} else {
		$collaboration_type = $activity['collaboration_type']['name'];
		if(!empty($activity['collaboration_type']['code'])) {
			$collaboration_type = $activity['collaboration_type']['code'] . '. ' .$collaboration_type;
		}
	}
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $collaboration_type);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Flow type:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['default_flow_type']['name'])) $activity['default_flow_type']['name'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['default_flow_type']['name']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Aid type:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	
	if(empty($activity['default_aid_type']['name'])) {
		$default_aid_type = EMPTY_LABEL;
	} else {
		$default_aid_type = $activity['default_aid_type']['name'];
		if(!empty($activity['default_aid_type']['code'])) {
			$default_aid_type = $activity['default_aid_type']['code'] . '. ' .$default_aid_type;
		}
	}
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $default_aid_type);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Finance type:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['default_finance_type']['name'])) $activity['default_finance_type']['name'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['default_finance_type']['name']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Tying status:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['default_tied_status_type']['name'])) $activity['default_tied_status_type']['name'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['default_tied_status_type']['name']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Activity status:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['activity_status']['name'])) $activity['activity_status']['name'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['activity_status']['name']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Name participating organisation:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['reporting_organisation']['org_name'])) $activity['reporting_organisation']['org_name'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['reporting_organisation']['org_name']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Organisation reference code:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	if(empty($activity['reporting_organisation']['ref'])) $activity['reporting_organisation']['ref'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['reporting_organisation']['ref']);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Description:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(100);
	if(empty($activity['descriptions'][0]['description'])) $activity['descriptions'][0]['description'] = EMPTY_LABEL;
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $activity['descriptions'][0]['description']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Documents:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", EMPTY_LABEL);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Commitments:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", EMPTY_LABEL);
	$row++;
	
	$objPHPExcel->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.ms-excel');

	header('Content-Disposition: attachment;filename="'. $author.'.xls"');

	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

	$objWriter->save('php://output');
	exit;
}

function generate_search_export($activities) {

	$objPHPExcel = new PHPExcel();

	$author = $_REQUEST['author'];

	$objPHPExcel->getProperties()->setCreator( $author );
	$objPHPExcel->getProperties()->setLastModifiedBy( $author );
	$objPHPExcel->getProperties()->setTitle("Search results");
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
	$row=1;

	foreach($activities AS $a) {
		$objPHPExcel->getActiveSheet()->mergeCells("A{$row}:D{$row}");
		$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", $a['titles'][0]['title']);
		$fill = array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'DADADA') );
		$objPHPExcel->getActiveSheet()->getStyle("A{$row}")->getFill()->applyFromArray($fill);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle("A{$row}:D{$row}")->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
		$row++;
		$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Countries:');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
		$sep = '';
		$countries = '';
		if(empty($a['recipient_country'])) {
			$countries = EMPTY_LABEL;
		} else {
			foreach($a['recipient_country'] AS $country) {
				$countries .= $sep . $country['name'];
				$sep = ', ';
			}
		}
		$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $countries);
		$row++;
		$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Subject:');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
		if(empty($a['titles'][0]['title'])) $a['titles'][0]['title'] = EMPTY_LABEL;
		$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $a['titles'][0]['title']);
		$row++;
		$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Budget:');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
		$budget = '';
		if(empty($a['statistics']['total_budget'])) {
			$budget = EMPTY_LABEL;
		} else {
			$budget = "US$ " . format_custom_number($a['statistics']['total_budget']);
		}
		$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $budget);
		$row++;
		$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Sector:');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
		$sep = '';
		$sectors = '';
		if(empty($a['activity_sectors'])) {
			$sectors = EMPTY_LABEL;
		} else {
			foreach($a['activity_sectors'] AS $sector) {
				$sectors .= $sep . $sector['name'];
				$sep = ', ';
			}
		}
		$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $sectors);
		$row++;
		
		$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Description:');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(100);
		if(empty($a['descriptions'][0]['description'])) $a['descriptions'][0]['description'] = EMPTY_LABEL;
		$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $a['descriptions'][0]['description']);
		
		$row+=2;
	}

	$objPHPExcel->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.ms-excel');

	header('Content-Disposition: attachment;filename="'. $author.'.xls"');

	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

	$objWriter->save('php://output');
	exit;
}

function getFilter(&$DATA, $format=1) {
	if (empty($DATA)) return false;
	if($format>2) return false;
	
	foreach ($DATA AS $key=>$value) {
		if($format==2) {
			$tmp->$key = $value;
		}elseif($format==1){
			$tmp["$key"] = $value;
		}
	}
	
	return $tmp;
}

function objectToArray($d) {
	if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars($d);
	}

	if (is_array($d)) {
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return array_map(__FUNCTION__, $d);
	}
	else {
		// Return array
		return $d;
	}
}

function format_custom_number($num) {
	
	$s = explode('.', $num);
	
	$parts = "";
	if(strlen($s[0])>3) {
		$parts = "." . substr($s[0], strlen($s[0])-3, 3);
		$s[0] = substr($s[0], 0, strlen($s[0])-3);
		
		if(strlen($s[0])>3) {
			$parts = "." . substr($s[0], strlen($s[0])-3, 3) . $parts;
			$s[0] = substr($s[0], 0, strlen($s[0])-3);
			if(strlen($s[0])>3) {
				$parts = "." . substr($s[0], strlen($s[0])-3, 3) . $parts;
				$s[0] = substr($s[0], 0, strlen($s[0])-3);
			} else {
				$parts = $s[0] . $parts;
			}
		} else {
			$parts = $s[0] . $parts;
		}
	} else {
		$parts = $s[0] . $parts;
	}
	
	
	$ret = $parts;
	
	if(isset($s[1])) {
		if($s[1]!="00") {
			$ret .= "," + $s[1];
		}
	}
	
	return $ret;
}
?>