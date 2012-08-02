<?php

	include_once( 'constants.php' );
	if(file_exists('countries.php') && empty($_COUNTRY_ISO_MAP)) include_once( 'countries.php' );
	
if(!function_exists(objectToArray)) {
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
}
	
	$FILTER = getFilter($_GET);
	
	if(!empty($FILTER['countries'])) {
		$countries = explode('|', $FILTER['countries']);
		$array['objects'] = array();
		foreach($countries AS $c) {
			$array['objects'][$c] = array('path' => $_GM_POLYGONS[$c], 'name' => $_COUNTRY_ISO_MAP[$c], 'total_cnt' => 0);
		}
		
		$activities_url = SEARCH_URL . "activities/?format=json&organisations=41120&limit=0&countries={$FILTER['countries']}";
		
		if(!empty($FILTER['query'])) {
			$activities_url .= "&query={$FILTER['query']}";
		}
		
		if(!empty($FILTER['regions'])) {
			$activities_url .= "&regions={$FILTER['regions']}";
		}
		
		if(!empty($FILTER['sectors'])) {
			$activities_url .= "&sectors={$FILTER['sectors']}";
		}
		
		if(!empty($FILTER['budgets'])) {
			$budgets = explode('|', trim($_REQUEST['budgets']));
			//Get the lowest budget from filter and use this one, all the other are included in the range
			ksort($budgets);
			$activities_url .= "&statistics__total_budget__gt={$budgets[0]}";
		}
		
		$content = file_get_contents($activities_url);
		$result = json_decode($content);
		$meta = $result->meta;
		$limit = $meta->total_count;
		
		
		$activities_url = SEARCH_URL . "activities/?format=json&organisations=41120&limit={$limit}&countries={$FILTER['countries']}";
		
		if(!empty($FILTER['query'])) {
			$activities_url .= "&query={$FILTER['query']}";
		}
		
		if(!empty($FILTER['regions'])) {
			$activities_url .= "&regions={$FILTER['regions']}";
		}
		
		if(!empty($FILTER['sectors'])) {
			$activities_url .= "&sectors={$FILTER['sectors']}";
		}
		
		if(!empty($FILTER['budgets'])) {
			$budgets = explode('|', trim($_REQUEST['budgets']));
			//Get the lowest budget from filter and use this one, all the other are included in the range
			ksort($budgets);
			$activities_url .= "&statistics__total_budget__gt={$budgets[0]}";
		}
		
		$content = file_get_contents($activities_url);
		$result = json_decode($content);
		$objects = $result->objects;
		$activities = objectToArray($objects);
		$array['meta']['total_count'] = $result->meta->total_count;
		foreach($activities AS $a) {
			foreach($a['recipient_country'] AS $c) {
				if(isset($array['objects'][$c['iso']])) {
					$array['objects'][$c['iso']]['total_cnt']++;
				}
			}
		}
		
	} else {
		
		/*$search_url = SEARCH_URL . "countries/?format=json&organisations=41120&limit=0";
				
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
		
		foreach($countries AS $c) {
			if(isset($_GM_POLYGONS[$c['iso']])) {
				$array[$c['iso']] = array('path' => $_GM_POLYGONS[$c['iso']], 'name' => $c['name']);
			}
		}
		*/
		$activities_url = SEARCH_URL . "activities/?format=json&organisations=41120&limit=0";
	
		if(!empty($FILTER['query'])) {
			$activities_url .= "&query={$FILTER['query']}";
		}
		
		if(!empty($FILTER['regions'])) {
			$activities_url .= "&regions={$FILTER['regions']}";
		}
		
		if(!empty($FILTER['sectors'])) {
			$activities_url .= "&sectors={$FILTER['sectors']}";
		}
		
		if(!empty($FILTER['budgets'])) {
			$budgets = explode('|', trim($_REQUEST['budgets']));
			//Get the lowest budget from filter and use this one, all the other are included in the range
			ksort($budgets);
			$activities_url .= "&statistics__total_budget__gt={$budgets[0]}";
		}
	
		$content = file_get_contents($activities_url);
		$result = json_decode($content);
		$meta = $result->meta;
		$limit = $meta->total_count;
		$activities_url = SEARCH_URL . "activities/?format=json&organisations=41120&limit={$limit}";
		
		if(!empty($FILTER['query'])) {
			$activities_url .= "&query={$FILTER['query']}";
		}
		
		if(!empty($FILTER['regions'])) {
			$activities_url .= "&regions={$FILTER['regions']}";
		}
		
		if(!empty($FILTER['sectors'])) {
			$activities_url .= "&sectors={$FILTER['sectors']}";
		}
		
		if(!empty($FILTER['budgets'])) {
			$budgets = explode('|', trim($_REQUEST['budgets']));
			//Get the lowest budget from filter and use this one, all the other are included in the range
			ksort($budgets);
			$activities_url .= "&statistics__total_budget__gt={$budgets[0]}";
		}
		
		$content = file_get_contents($activities_url);
		$result = json_decode($content);
		$objects = $result->objects;
		$activities = objectToArray($objects);
		$array['objects'] = array();
		$array['meta']['total_count'] = $result->meta->total_count;
		foreach($activities AS $a) {
			foreach($a['recipient_country'] AS $c) {
				if(isset($array['objects'][$c['iso']])) {
					$array['objects'][$c['iso']]['total_cnt']++;
				} else {
					if(isset($_GM_POLYGONS[$c['iso']])) {
						$array['objects'][$c['iso']] = array('path' => $_GM_POLYGONS[$c['iso']], 'name' => $c['name'], 'total_cnt' => 1);
					}
				}
			}
		}
	}
	
	if(!isset($FILTER['inline'])) {
		echo json_encode($array);
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
?>