<?php
	include( TEMPLATEPATH .'/constants.php' );
	if(file_exists(TEMPLATEPATH . '/countries.php')) {
		include_once( TEMPLATEPATH . '/countries.php' );
		asort($_COUNTRY_ISO_MAP);
	}
	if(file_exists(TEMPLATEPATH . '/sectors.php')) {
		include_once( TEMPLATEPATH . '/sectors.php' );
		asort($_SECTOR_CHOICES);
	}
	if(file_exists(TEMPLATEPATH . '/regions.php')) {
		include_once( TEMPLATEPATH . '/regions.php' );
		asort($_REGION_CHOICES);
	}
	// Add RSS links to <head> section
	automatic_feed_links();
	
	// Load jQuery
	if ( !is_admin() ) {
	   /*wp_deregister_script('jquery');
	   wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"), false);
	   wp_enqueue_script('jquery');*/
	}
	
	// Clean up the <head>
	function removeHeadLinks() {
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');
    
    if (function_exists('register_sidebar')) {
    	register_sidebar(array(
    		'name' => 'Sidebar Widgets',
    		'id'   => 'sidebar-widgets',
    		'description'   => 'These are widgets for the sidebar.',
    		'before_widget' => '<div id="%1$s" class="leftmenu %2$s">',
    		'after_widget'  => '</div>',
    		'before_title'  => '<h4>',
    		'after_title'   => '</h4>'
    	));
    }
	
	add_filter( 'request', 'my_request_filter' );
	function my_request_filter( $query_vars ) {
		if( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) {
			$query_vars['s'] = " ";
		}
		return $query_vars;
	}
	
	if(empty($_COUNTRY_ISO_MAP)) {
		wp_generate_constants();
	}

function wp_generate_results($details, &$meta, &$projects_html, &$has_filter) {
	$search_url = SEARCH_URL . "activities/?format=json&organisations=41120&limit=20";
	
	if(isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
		$query = rawurlencode($_REQUEST['s']);
		$search_url .= "&query={$query}";
	}
	
	if(!empty($_REQUEST['countries'])) {
		$countries = explode('|', trim($_REQUEST['countries']));
		foreach($countries AS &$c) $c = trim($c);
		$countries = implode('|', $countries);
		$search_url .= "&countries={$countries}";
		$has_filter = true;
	}
	
	if(!empty($_REQUEST['regions'])) {
		$regions = explode('|', trim($_REQUEST['regions']));
		foreach($regions AS &$c) $c = trim($c);
		$regions = implode('|', $regions);
		$search_url .= "&regions={$regions}";
		$has_filter = true;
	}
	
	if(!empty($_REQUEST['sectors'])) {
		$sectors = explode('|', trim($_REQUEST['sectors']));
		foreach($sectors AS &$c) $c = trim($c);
		$sectors = implode('|', $sectors);
		$search_url .= "&sectors={$sectors}";
		$has_filter = true;
	}
	
	if(!empty($_REQUEST['budgets'])) {
		$budgets = explode('|', trim($_REQUEST['budgets']));
		//Get the lowest budget from filter and use this one, all the other are included in the range
		ksort($budgets);
		$search_url .= "&statistics__total_budget__gt={$budgets[0]}";
		$has_filter = true;
	}
	
	$back_url = $_SERVER['REQUEST_URI'];
	
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$meta = $result->meta;
	$objects = $result->objects;
	
	$return = "";

	if(!empty($objects)) {
		$base_url = get_option('home');
		foreach($objects AS $idx=>$project) {
			
			$return .= '<div class="searchresult row'.$idx.'">
                        	<a id="detail_'.$idx.'" href="javascript:void(0);" class="moredetail"></a>';
			$return .= '<h3><a href="'.$base_url.'/?page_id=2&id='.$project->iati_identifier.'&back_url='.rawurlencode($back_url).'">'.$project->titles[0]->title.'</a></h3>';			
			
			if(in_array("all",$details) || in_array("country",$details) ){
				$return .= '<span class="detail"><span>Countries</span>:';
				$sep = '';
				foreach($project->recipient_country AS $country) {
					$return .= $sep . $country->name;
					$sep = ', ';
				}
				$return .= '</span>';
			}
			if(in_array("all",$details) || in_array("subject",$details) ){
				$return .= '<span class="detail"><span>Subject</span>: '.$project->titles[0]->title.'</span>';
			}
			if(in_array("all",$details) || in_array("budget",$details) ){
				$return .= '<span class="detail"><span>Budget</span>: US$ '.format_custom_number($project->statistics->total_budget).'</span>';
			}
			if(in_array("all",$details) || in_array("sector",$details) ){
				$return .= '<span class="detail"><span>Sector</span>: ';
				$sep = '';
				if(empty($project->activity_sectors)) {
					$return .= "No information avaialable";
				} else {
					foreach($project->activity_sectors AS $sector) {
						$return .= $sep . $sector->name;
						$sep = ', ';
					}
				}
				$return .= '</span>';
			}
			
			$return .= '<p class="shortdescription">'.$project->descriptions[0]->description.'</p>';
			$return .= '<div class="resultdetail detail_'.$idx.'">';
			$return .= '<div class="rcol rcol1">
							<ul>
							  <li><span>Last updated: </span>'.$project->date_updated.'</li>
							  <li><span>Status: </span>'.$project->activity_status->name.'</li>
							</ul>
						</div>';
			$return .= '<div class="rcol rcol2">
							<ul>
							  <li><span>Start date planned: </span>'.$project->start_planned.'</li>
							  <li><span>Start date actual: </span>'.$project->start_actual.'</li>
							</ul>
						</div>';
			$return .= '<div class="rcol rcol3">
							<ul>
							  <li><span>End date planned: </span>'.$project->end_planned.'</li>
							  <li><span>End dat actual: </span>'.$project->end_actual.'</li>
							</ul>
						</div>';
			$return .= '<div class="clr"></div>
						<div class="resultrow">
							<a href="'.$base_url.'/?page_id=42" class="whistleb"><span>WHISTLEBLOWER</span></a>
							<a href="#" class="share"><span>SHARE</span></a>
							<a href="#" class="bookmark"><span>BOOKMARK</span></a>
						</div>';
			
			$return .= '</div>
                       </div>';
				
		}
	}
	
	$projects_html = $return;

}

function wp_get_activity($identifier) {
	if(empty($identifier)) return null;
	$search_url = SEARCH_URL . "activities/{$identifier}/?format=json";
	
	$content = file_get_contents($search_url);
	$activity = json_decode($content);
	/*if(empty($activity->title->default)) $activity->title->default = EMPTY_LABEL;
	if(empty($activity->recipient_country)) $activity->recipient_country[0]->name = EMPTY_LABEL;
	if(empty($activity->activity_sectors)) $activity->activity_sectors[0]->name = EMPTY_LABEL;
	if(empty($activity->iati_identifier)) $activity->iati_identifier = EMPTY_LABEL;
	if(empty($activity->reporting_organisation->org_name)) $activity->reporting_organisation->org_name = EMPTY_LABEL;
	if(empty($activity->start_actual)) $activity->start_actual = EMPTY_LABEL;
	if(empty($activity->activity_sectors[0]->code)) $activity->activity_sectors[0]->code = EMPTY_LABEL;
	if(empty($activity->date_updated)) $activity->date_updated = EMPTY_LABEL;
	if(empty($activity->start_planned)) $activity->start_planned = EMPTY_LABEL;
	if(empty($activity->end_planned)) $activity->end_planned = EMPTY_LABEL;
	if(empty($activity->end_actual)) $activity->end_actual = EMPTY_LABEL;
	if(empty($activity->collaboration_type->name)) $activity->collaboration_type->name = EMPTY_LABEL;
	if(empty($activity->default_flow_type->name)) $activity->default_flow_type->name = EMPTY_LABEL;
	if(empty($activity->default_aid_type->name)) $activity->default_aid_type->name = EMPTY_LABEL;
	if(empty($activity->default_finance_type->name)) $activity->default_finance_type->name = EMPTY_LABEL;
	if(empty($activity->default_tied_status_type->name)) $activity->default_tied_status_type->name = EMPTY_LABEL;
	if(empty($activity->activity_status->name)) $activity->activity_status->name = EMPTY_LABEL;
	if(empty($activity->reporting_organisation->org_name)) $activity->reporting_organisation->org_name = EMPTY_LABEL;
	if(empty($activity->reporting_organisation->ref)) $activity->reporting_organisation->ref = EMPTY_LABEL;
	if(empty($activity->description->default)) $activity->description->default = EMPTY_LABEL;*/
	return $activity;

}

function wp_generate_filter_html( $filter, $limit = 4 ) {
	$limit = intval($limit);
	if($limit<=0) $limit = 4;
	
	$filter = strtoupper($filter);
	
	$return = "<ul>";
	$add_more = false;
	$generate_popup = false;
	switch($filter) {
		case 'COUNTRY':
			global $_COUNTRY_ISO_MAP;
			if(empty($_COUNTRY_ISO_MAP) && !file_exists(TEMPLATEPATH . '/countries.php')) {
				wp_generate_constants();
				include_once( TEMPLATEPATH . '/countries.php' );
				asort($_COUNTRY_ISO_MAP);
			}
			$_data = $_COUNTRY_ISO_MAP;
			$selected = array();
			if(!empty($_REQUEST['countries'])) {
				$tmp = explode('|', $_REQUEST['countries']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_COUNTRY_ISO_MAP[$s];
				}
				
				if(count($selected)>$limit) {
					$limit=count($selected);
					$_data = $selected;
				} else {
					$limit -= count($selected);
					$_data = array_diff($_data, $selected);
				}
			}
			
			$cnt = 1;
			$checked = "";
			if(empty($selected)) $checked = "checked";
			$return .= "<li>
							<label for=\"id_countries_{$cnt}\"><input name=\"countries\" value=\"All\" id=\"id_countries_{$cnt}\" type=\"checkbox\" {$checked}>All</label>
						</li>";
			if(!empty($selected)) {
				foreach($selected AS $iso=>$c) {
					$checked = "checked";
					$cnt++;
					$return .= "<li>
									<label for=\"id_countries_{$cnt}\"><input name=\"countries\" value=\"{$iso}\" id=\"id_countries_{$cnt}\" type=\"checkbox\" {$checked}>{$c}</label>
								</li>";
				}
				
				$limit+=$cnt-1;
			}
			foreach($_data AS $iso=>$c) {
				$checked = "";
				if(isset($selected[$iso])) $checked = "checked";
				$cnt++;
				$return .= "<li>
							<label for=\"id_countries_{$cnt}\"><input name=\"countries\" value=\"{$iso}\" id=\"id_countries_{$cnt}\" type=\"checkbox\" {$checked}>{$c}</label>
						</li>";
				if($cnt>$limit) break;
			}
			
			if($limit<count($_COUNTRY_ISO_MAP)) {
				$add_more = true;
				$generate_popup = true;
			}

			break;
		case 'REGION':
			global $_REGION_CHOICES;
			if(empty($_REGION_CHOICES) && !file_exists(TEMPLATEPATH . '/regions.php')) {
				wp_generate_constants();
				include_once( TEMPLATEPATH . '/regions.php' );
				asort($_REGION_CHOICES);
			}
			$_data = $_REGION_CHOICES;

			$selected = array();
			if(!empty($_REQUEST['regions'])) {
				$tmp = explode('|', $_REQUEST['regions']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_REGION_CHOICES[$s];
				}
				
				if(count($selected)>$limit) {
					$limit=count($selected);
					$_data = $selected;
				} else {
					$limit -= count($selected);
					$_data = array_diff($_data, $selected);
				}
			}
			
			$cnt = 1;
			$checked = "";
			if(empty($selected)) $checked = "checked";
			$return .= "<li>
							<label for=\"id_regions_{$cnt}\"><input name=\"regions\" value=\"All\" id=\"id_regions_{$cnt}\" type=\"checkbox\" {$checked}>All</label>
						</li>";
			if(!empty($selected)) {
				foreach($selected AS $iso=>$c) {
					$checked = "checked";
					$cnt++;
					$return .= "<li>
									<label for=\"id_regions_{$cnt}\"><input name=\"regions\" value=\"{$iso}\" id=\"id_regions_{$cnt}\" type=\"checkbox\" {$checked}>{$c}</label>
								</li>";
				}
				
				$limit+=$cnt-1;
			}
			foreach($_data AS $iso=>$c) {
				$checked = "";
				if(isset($selected[$iso])) $checked = "checked";
				$cnt++;
				$return .= "<li>
							<label for=\"id_regions_{$cnt}\"><input name=\"regions\" value=\"{$iso}\" id=\"id_regions_{$cnt}\" type=\"checkbox\" {$checked}>{$c}</label>
						</li>";
				if($cnt>$limit) break;
			}
			
			if($limit<count($_REGION_CHOICES)) {
				$add_more = true;
				$generate_popup = true;
			}

			break;
		case 'SECTOR':
			global $_SECTOR_CHOICES;
			if(empty($_SECTOR_CHOICES) && !file_exists(TEMPLATEPATH . '/sectors.php')) {
				wp_generate_constants();
				include_once( TEMPLATEPATH . '/sectors.php' );
				asort($_SECTOR_CHOICES);
			}
			$_data = $_SECTOR_CHOICES;
			$selected = array();
			if(!empty($_REQUEST['sectors'])) {
				$tmp = explode('|', $_REQUEST['sectors']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_SECTOR_CHOICES[$s];
				}
				
				if(count($selected)>$limit) {
					$limit=count($selected);
					$_data = $selected;
				} else {
					$limit -= count($selected);
					$_data = array_diff($_data, $selected);
				}
			}
			
			$cnt = 1;
			$checked = "";
			if(empty($selected)) $checked = "checked";
			$return .= "<li>
							<label for=\"id_sectors_{$cnt}\"><input name=\"sectors\" value=\"All\" id=\"id_sectors_{$cnt}\" type=\"checkbox\" {$checked}>All</label>
						</li>";
			if(!empty($selected)) {
				foreach($selected AS $iso=>$c) {
					$checked = "checked";
					$cnt++;
					$return .= "<li>
									<label for=\"id_sectors_{$cnt}\"><input name=\"sectors\" value=\"{$iso}\" id=\"id_sectors_{$cnt}\" type=\"checkbox\" {$checked}>{$c}</label>
								</li>";
				}
				
				$limit+=$cnt-1;
			}
			foreach($_data AS $iso=>$c) {
				$checked = "";
				if(isset($selected[$iso])) $checked = "checked";
				$cnt++;
				$return .= "<li>
							<label for=\"id_sectors_{$cnt}\"><input name=\"sectors\" value=\"{$iso}\" id=\"id_sectors_{$cnt}\" type=\"checkbox\" {$checked}>{$c}</label>
						</li>";
				if($cnt>$limit) break;
			}
			
			if($limit<count($_SECTOR_CHOICES)) {
				$add_more = true;
				$generate_popup = true;
			}

			break;
		case 'BUDGET':
			global $_BUDGET_CHOICES;
			$_data = $_BUDGET_CHOICES;
			$limit=6; //Show all
			$selected = array();
			if(!empty($_REQUEST['budgets'])) {
				$tmp = explode('|', $_REQUEST['budgets']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_BUDGET_CHOICES[$s];
				}
				
				if(count($selected)>$limit) {
					$limit=count($selected);
					$_data = $selected;
				}
			}
			
			$cnt = 1;
			$checked = "";
			if(empty($selected)) $checked = "checked";
			$return .= "<li>
							<label for=\"id_budgets_{$cnt}\"><input name=\"budgets\" value=\"All\" id=\"id_budgets_{$cnt}\" type=\"checkbox\" {$checked}>All</label>
						</li>";
			foreach($_data AS $iso=>$c) {
				$checked = "";
				if(isset($selected[$iso])) $checked = "checked";
				$cnt++;
				$return .= "<li>
							<label for=\"id_budgets_{$cnt}\"><input name=\"budgets\" value=\"{$iso}\" id=\"id_budgets_{$cnt}\" type=\"checkbox\" {$checked}>{$c}</label>
						</li>";
				if($cnt>$limit) break;
			}
			
			if($limit<count($_BUDGET_CHOICES)) {
				$add_more = true;
				$generate_popup = true;
			}

			break;
		default:
			
			break;
	}
	$return .= "</ul>";
	if($add_more) {
		$href = strtolower($filter) . '_popup';
		$return .= "<a class=\"overlay\" href=\"#{$href}\">+ SEE ALL</a>";
	}
	return $return;
	
}

function wp_generate_filter_popup($filter, $limit = 4 ) {
	$limit = intval($limit);
	if($limit<=0) $limit = 4;
	$base_url = get_option('home');
	if(isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
		$query = str_replace(" ","+",$_REQUEST['s']);
	}
	
	$return = '	<div id="__FILTERID__" class="nodisp">
						<div style="position:relative;">
							<div class="submtBtns rounded-corners-bottom" id="popupsubmtBtn">
								<a href="'.$base_url.'/?s='.$query.'">__FILTERDESC__</a>
							</div>
						';
	
	$filter = strtoupper($filter);
	
	switch($filter) {
		case 'COUNTRY':
			global $_COUNTRY_ISO_MAP;
			if(empty($_COUNTRY_ISO_MAP) && !file_exists(TEMPLATEPATH . '/countries.php')) {
				wp_generate_constants();
				include_once( TEMPLATEPATH . '/countries.php' );
				asort($_COUNTRY_ISO_MAP);
			}
			
			$selected = array();
			if(!empty($_REQUEST['countries'])) {
				$tmp = explode('|', $_REQUEST['countries']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_COUNTRY_ISO_MAP[$s];
				}
			}
			
			$return = preg_replace("/__FILTERID__/", strtolower($filter)."_popup", $return);
			$return = preg_replace("/__FILTERDESC__/", "Select the countries you wish to see from below and click here to see Search Results", $return);
			
			$checked = "";
			if(empty($selected)) $checked = "checked";
			$return .= '<div class="countrieslist">
							<div class="all">
								<label for="id_countries_1"><input name="countries" value="All" id="id_countries_1" type="checkbox" '.$checked.'/>All</label>
							</div>';
			
			$fltr_cnt = count($_COUNTRY_ISO_MAP);
			
			if($fltr_cnt%4!=0) $fltr_cnt++;
			while($fltr_cnt%4!=0) {
				$fltr_cnt++;
			}
			
			$items_per_col = $fltr_cnt/4;
			$cnt = 0;
			$return .= "<div class=\"col\"><ul>";
			if(!empty($_COUNTRY_ISO_MAP)) {
				foreach($_COUNTRY_ISO_MAP AS $iso=>$c) {
					$checked = "";
					if(isset($selected[$iso])) $checked = "checked";
					$cnt++;
					$return .= "<li>
								<input name=\"countries\" id=\"check-country{$cnt}\" class=\"check\" type=\"checkbox\" value=\"{$iso}\" {$checked}/>
								<label for=\"check-country{$cnt}\">{$c}</label>
							</li>";
					if($cnt%$items_per_col==0) {
						$return .= "</ul></div><div class=\"col\"><ul>";
					}
				}
			}
			$return .= "</ul></div>";
			$return .= "<div class=\"clr\"></div>";
			
			
			break;
		case 'REGION':
			global $_REGION_CHOICES;
			if(empty($_REGION_CHOICES) && !file_exists(TEMPLATEPATH . '/regions.php')) {
				wp_generate_constants();
				include_once( TEMPLATEPATH . '/regions.php' );
				asort($_REGION_CHOICES);
			}
			
			$selected = array();
			if(!empty($_REQUEST['regions'])) {
				$tmp = explode('|', $_REQUEST['regions']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_REGION_CHOICES[$s];
				}
			}
			
			$return = preg_replace("/__FILTERID__/", strtolower($filter)."_popup", $return);
			$return = preg_replace("/__FILTERDESC__/", "Select the regions you wish to see from below and click here to see Search Results", $return);
			
			$checked = "";
			if(empty($selected)) $checked = "checked";
			$return .= '<div class="regionslist">
							<div class="all">
								<label for="id_regions_1"><input name="regions" value="All" id="id_regions_1" type="checkbox" '.$checked.'/>All</label>
							</div>';
			
			$fltr_cnt = count($_REGION_CHOICES);
			
			if($fltr_cnt%4!=0) $fltr_cnt++;
			while($fltr_cnt%4!=0) {
				$fltr_cnt++;
			}
			
			$items_per_col = $fltr_cnt/4;
			$cnt = 0;
			$return .= "<div class=\"col\"><ul>";
			if(!empty($_REGION_CHOICES)) {
				foreach($_REGION_CHOICES AS $iso=>$c) {
					$checked = "";
					if(isset($selected[$iso])) $checked = "checked";
					$cnt++;
					$return .= "<li>
								<input name=\"regions\" id=\"check-region{$cnt}\" class=\"check\" type=\"checkbox\" value=\"{$iso}\" {$checked}/>
								<label for=\"check-region{$cnt}\">{$c}</label>
							</li>";
					if($cnt%$items_per_col==0) {
						$return .= "</ul></div><div class=\"col\"><ul>";
					}
				}
			}
			$return .= "</ul></div>";
			$return .= "<div class=\"clr\"></div>";
			
			
			break;
			
		case 'SECTOR':
			global $_SECTOR_CHOICES;
			if(empty($_SECTOR_CHOICES) && !file_exists(TEMPLATEPATH . '/sectors.php')) {
				wp_generate_constants();
				include_once( TEMPLATEPATH . '/sectors.php' );
				asort($_SECTOR_CHOICES);
			}
			
			$selected = array();
			if(!empty($_REQUEST['sectors'])) {
				$tmp = explode('|', $_REQUEST['sectors']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_SECTOR_CHOICES[$s];
				}
			}
			
			$return = preg_replace("/__FILTERID__/", strtolower($filter)."_popup", $return);
			$return = preg_replace("/__FILTERDESC__/", "Select the sectors you wish to see from below and click here to see Search Results", $return);
			
			$checked = "";
			if(empty($selected)) $checked = "checked";
			$return .= '<div class="sectorslist">
							<div class="all">
								<label for="id_sectors_1"><input name="sectors" value="All" id="id_sectors_1" type="checkbox" '.$checked.'/>All</label>
							</div>';
			
			$fltr_cnt = count($_SECTOR_CHOICES);
			
			if($fltr_cnt%4!=0) $fltr_cnt++;
			while($fltr_cnt%4!=0) {
				$fltr_cnt++;
			}
			
			$items_per_col = $fltr_cnt/4;
			$cnt = 0;
			$return .= "<div class=\"col\"><ul>";
			if(!empty($_SECTOR_CHOICES)) {
				foreach($_SECTOR_CHOICES AS $iso=>$c) {
					$checked = "";
					if(isset($selected[$iso])) $checked = "checked";
					$cnt++;
					$return .= "<li>
								<input name=\"sectors\" id=\"check-sector{$cnt}\" class=\"check\" type=\"checkbox\" value=\"{$iso}\" {$checked}/>
								<label for=\"check-sector{$cnt}\">{$c}</label>
							</li>";
					if($cnt%$items_per_col==0) {
						$return .= "</ul></div><div class=\"col\"><ul>";
					}
				}
			}
			$return .= "</ul></div>";
			$return .= "<div class=\"clr\"></div>";
			
			
			break;
		case 'BUDGET':
			global $_BUDGET_CHOICES;
			
			if($limit>=count($_BUDGET_CHOICES)) {
				return "";
			}
			
			$selected = array();
			if(!empty($_REQUEST['budgets'])) {
				$tmp = explode('|', $_REQUEST['budgets']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_BUDGET_CHOICES[$s];
				}
			}
			
			$return = preg_replace("/__FILTERID__/", strtolower($filter)."_popup", $return);
			$return = preg_replace("/__FILTERDESC__/", "Select the budget you wish to see from below and click here to see Search Results", $return);
			
			$checked = "";
			if(empty($selected)) $checked = "checked";
			$return .= '<div class="budgetlist">
							<div class="all">
								<label for="id_budgets_1"><input name="budgets" value="All" id="id_budgets_1" type="checkbox" '.$checked.'/>All</label>
							</div>';
			
			$fltr_cnt = count($_BUDGET_CHOICES);
			
			if($fltr_cnt%4!=0) $fltr_cnt++;
			while($fltr_cnt%4!=0) {
				$fltr_cnt++;
			}
			
			$items_per_col = $fltr_cnt/4;
			$cnt = 0;
			$return .= "<div class=\"col\"><ul>";
			foreach($_BUDGET_CHOICES AS $iso=>$c) {
				$checked = "";
				if(isset($selected[$iso])) $checked = "checked";
				$cnt++;
				$return .= "<li>
							<input name=\"budgets\" id=\"check-budget{$cnt}\" class=\"check\" type=\"checkbox\" value=\"{$iso}\" {$checked}/>
							<label for=\"check-budget{$cnt}\">{$c}</label>
						</li>";
				if($cnt%$items_per_col==0) {
					$return .= "</ul></div><div class=\"col\"><ul>";
				}
			}
			$return .= "</ul></div>";
			$return .= "<div class=\"clr\"></div>";
			
			
			break;
		default:
			
			break;
	}
	
	
					
					
	$return .= '		</div>
					</div>
				</div>';
	
	return $return;
}

function wp_generate_constants() {
	$activities_url = SEARCH_URL . "activities/?format=json&organisations=41120&limit=0";
	$content = file_get_contents($activities_url);
	$result = json_decode($content);
	$meta = $result->meta;
	$count = $meta->total_count;
	
	$start=0;
	$limit=50;
	$countries = array();
	$sectors = array();
	$regions = array();
	while($start<$count) {
		$activities_url = SEARCH_URL . "activities/?format=json&organisations=41120&start={$start}&limit={$limit}";
		$result = json_decode($content);
		$objects = $result->objects;
		$activities = objectToArray($objects);
		
		foreach($activities AS $a) {
			if(!empty($a['recipient_country'])) {
				foreach($a['recipient_country'] AS $c) {
					if(isset($countries[$c['iso']])) continue;
					$countries[$c['iso']] = $c['name'];
				}
			}
			if(!empty($a['activity_sectors'])) {
				foreach($a['activity_sectors'] AS $s) {
					if(isset($sectors[$s['code']])) continue;
					$sectors[$s['code']] = $s['name'];
				}
			}
			if(!empty($a['recipient_region'])) {
				foreach($a['recipient_region'] AS $r) {
					if(isset($regions[$r['code']])) continue;
					$regions[$r['code']] = $r['name'];
				}
			}
		}
		
		$start+=$limit;
	}
	$to_write = '<?php
$_COUNTRY_ISO_MAP = array(
';
	if(!empty($countries)) {
		
		foreach($countries AS $key=>$value) {
			if(empty($value) || $value=='#N/A') continue;
			$name = addslashes($value);
			$to_write .= "'{$key}' => '{$name}',\n";
		}
		
	}
	$to_write .= ');
?>';
	$fp = fopen(TEMPLATEPATH . '/countries.php', 'w+');
	fwrite($fp, $to_write);
	fclose($fp);
	$to_write = '<?php
$_SECTOR_CHOICES = array(
';
	if(!empty($sectors)) {
		
		foreach($sectors AS $key=>$value) {
			if(empty($value) || $value=='#N/A') continue;
			$name = addslashes($value);
			$to_write .= "'{$key}' => '{$name}',\n";
		}
		
	}
	$to_write .= ');
?>';
	$fp = fopen(TEMPLATEPATH . '/sectors.php', 'w+');
	fwrite($fp, $to_write);
	fclose($fp);
	
	$to_write = '<?php
$_REGION_CHOICES = array(
';
	if(!empty($regions)) {
		
		foreach($regions AS $key=>$value) {
			if(empty($value) || $value=='#N/A') continue;
			$name = addslashes($value);
			$to_write .= "'{$key}' => '{$name}',\n";
		}
	}
	
	$to_write .= ');
?>';
	$fp = fopen(TEMPLATEPATH . '/regions.php', 'w+');
	fwrite($fp, $to_write);
	fclose($fp);
}

function wp_generate_paging($meta) {
	//fix the paging 
	$total_count = $meta->total_count;
	$offset = $meta->offset;
	$limit = $meta->limit;
	$per_page = 20;
	$total_pages = ceil($total_count/$limit);
	$cur_page = $offset/$limit + 1;
	
	$paging_block = "<ul class='menu pagination'><li><a href='javascript:void(0);' class='start'><span>&laquo;</span></a></li><li><a href='javascript:void(0);' class='limitstart'><span>&larr; </span></a></li>";
	$page_limit = 7;
	$fromPage = $cur_page - 3;
	if($fromPage<=0) $fromPage = 1;
	$loop_limit = ($total_pages>$page_limit?($fromPage + $page_limit - 1):$total_pages);
		

	for($i=$fromPage; $i<=$loop_limit; $i++) {
		$paging_block .= "<li>";
		if($cur_page==i) {
			$paging_block .= "<a href='javascript:void(0);' class='active'><span id='cur_page'>{$i}</span></a>";
		} else {
			$paging_block .= "<a href='javascript:void(0);' class='page'><span>{$i}</span></a>";
		}
		$paging_block .= "</li>";
	}
	if(($fromPage+$loop_limit)<($total_pages-3)) {
		if($total_pages>$page_limit) {
			$paging_block .= "<li>...</li>";
		}
		
		for($i=$total_pages-2; $i<=$total_pages; $i++) {
			$paging_block .= "<li>";
			
			$paging_block .= "<a href='javascript:void(0);' class='page'><span>{$i}</span></a>";
		
			$paging_block .= "</li>";
		}
	} else {

		for($i=$loop_limit+1; $i<=$total_pages; $i++) {
			$paging_block .= "<li>";
			if($cur_page==i) {
				$paging_block .= "<a href='javascript:void(0);' class='active'><span id='cur_page'>{$i}</span></a>";
			} else {
				$paging_block .= "<a href='javascript:void(0);' class='page'><span>{$i}</span></a>";
			}
			$paging_block .= "</li>";
		}
		
	}
		
	$paging_block .= "<li><a href='javascript:void(0);' class='endmilit'><span>&rarr; </span></a></li><li><a href='javascript:void(0)' class='end'><span>&raquo;</span></a></li></ul>";
	
	echo $paging_block;
}

function wp_generate_home_map_data() {
		global $_GM_POLYGONS;
		$activities_url = SEARCH_URL . "activities/?format=json&organisations=41120&limit=0";
	
		$content = file_get_contents($activities_url);
		$result = json_decode($content);
		$meta = $result->meta;
		$limit = $meta->total_count;
		$activities_url = SEARCH_URL . "activities/?format=json&organisations=41120&limit={$limit}";
		
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
		
		return $array;
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