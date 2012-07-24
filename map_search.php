<?php
	include( 'constants.php' );
	
	
	$search_url = SEARCH_URL . "countries/?format=json&organisations=41120&limit=0";
				
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$meta = $result->meta;
	$total_count = $meta->total_count;
	$search_url = SEARCH_URL . "countries/?format=json&organisations=41120&limit={$total_count}";
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$meta = $result->meta;
	$objects = $result->objects;
	$countries = objectToArray($objects);
	
	$FILTER = getFilter($_GET);
	
	if(!empty($FILTER['countries'])) {
		$countries = explode('|', $FILTER['countries']);
		foreach($countries AS $c) {
			$array[$c] = array('path' => $_GM_POLYGONS[$c], 'name' => $_COUNTRY_ISO_MAP[$c]);
		}
	} else {
		foreach($countries AS $c) {
			if(isset($_GM_POLYGONS[$c['iso']])) {
				$array[$c['iso']] = array('path' => $_GM_POLYGONS[$c['iso']], 'name' => $c['name']);
			}
		}
	}
	
	echo json_encode($array);

	

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
?>