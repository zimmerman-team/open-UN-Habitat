<?php
	include_once( 'constants.php' );
	if(empty($_COUNTRY_ISO_MAP) && file_exists('./countries.php')) {
		include_once('./countries.php' );
		asort($_COUNTRY_ISO_MAP);
	}
	
	$FILTER = getFilter($_GET);
	
	$FILTER['limit'] = intval($FILTER['limit']);
	if($FILTER['limit']<=0) $FILTER['limit'] = 20;
	
	$FILTER['offset'] = intval($FILTER['offset']);
	if($FILTER['offset']<0) $FILTER['offset'] = 0;
	
	$search_url = "http://oipa.openaidsearch.org/api/v2/activities/?format=json&limit={$FILTER['limit']}&offset={$FILTER['offset']}";
	if(!empty($_DEFAULT_ORGANISATION_ID)) {
		$search_url .= "&organisations=" . $_DEFAULT_ORGANISATION_ID;
	}
	
	if(!empty($FILTER['query'])) {
		$search_url .= "&query={$FILTER['query']}";
	}
	
	if(!empty($FILTER['countries'])) {
		$search_url .= "&countries={$FILTER['countries']}";
	} else {
		/*$countries = $_COUNTRY_ISO_MAP;
		unset($countries['WW']);
		$search_url .= "&countries=" . implode('|', array_keys($countries));*/
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
	echo $content;

	

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