<?php
include('constants.php');
ini_set('zend.ze1_compatibility_mode', '0');
include 'inc/PHPExcel.php';
include 'inc/PHPExcel/Writer/Excel2007.php';


$FILTER = getFilter($_GET);
	
$FILTER['limit'] = intval($FILTER['limit']);
if($FILTER['limit']<=0) $FILTER['limit'] = 20;

$FILTER['offset'] = intval($FILTER['offset']);
if($FILTER['offset']<0) $FILTER['offset'] = 0;

$search_url = "http://oipa.openaidsearch.org/api/v2/activities/?format=json&organisations=41120&limit={$FILTER['limit']}&offset={$FILTER['offset']}";

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



$objPHPExcel = new PHPExcel();

$author = $_REQUEST['author'];

$objPHPExcel->getProperties()->setCreator( $author );
$objPHPExcel->getProperties()->setLastModifiedBy( $author );
$objPHPExcel->getProperties()->setTitle("Search results");
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$row=1;

foreach($activities AS $a) {
	$objPHPExcel->getActiveSheet()->mergeCells("A{$row}:D{$row}");
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", $a['title']['default']);
	$objPHPExcel->getActiveSheet()->getStyle("A{$row}:D{$row}")->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Countries:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	$sep = '';
	$countries = '';
	foreach($a['recipient_country'] AS $country) {
		$countries .= $sep . $country['name'];
		$sep = ', ';
	}
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $countries);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Subject:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $a['title']['default']);
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Budget:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", "US$ " . format_custom_number($a['statistics']['total_budget']));
	$row++;
	$objPHPExcel->getActiveSheet()->setCellValue("A{$row}", 'Sector:');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	$sep = '';
	$sectors = '';
	if(empty($a['activity_sectors'])) {
		$sectors = "No information avaialable";
	} else {
		foreach($a['activity_sectors'] AS $sector) {
			$sectors .= $sep . $sector['name'];
			$sep = ', ';
		}
	}
	$objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $sectors);
	
	$row+=2;
}

$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.ms-excel');

header('Content-Disposition: attachment;filename="'. $author.'.xls"');

header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;

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