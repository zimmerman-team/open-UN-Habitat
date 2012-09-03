<?php
	include( TEMPLATEPATH .'/constants.php' );
	if(file_exists(TEMPLATEPATH . '/countries.php')) {
		$fmdate = filemtime(TEMPLATEPATH . '/countries.php');
		if((time() - $fmdate)>$_RELAOD_FILTERS_TIMEOUT) {
			wp_generate_constants();
		}
		include_once( TEMPLATEPATH . '/countries.php' );
		asort($_COUNTRY_ISO_MAP);
	} else {
		wp_generate_constants();
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

function wp_generate_results($details, &$meta, &$projects_html, &$has_filter) {
	global $_DEFAULT_ORGANISATION_ID, $_PER_PAGE, $_COUNTRY_ISO_MAP;
	$search_url = SEARCH_URL . "activities/?format=json&limit={$_PER_PAGE}";
	if(!empty($_DEFAULT_ORGANISATION_ID)) {
		$search_url .= "&organisations=" . $_DEFAULT_ORGANISATION_ID;
	}
	if(isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
		$query = rawurlencode($_REQUEST['s']);
		
		$srch_countries = array_map('strtolower', $_COUNTRY_ISO_MAP);
		$srch_countries = array_flip($srch_countries);
		$key = strtolower($query);
		if(isset($srch_countries[$key])) {
			$srch_countries = $srch_countries[$key];
		} else {
			$search_url .= "&query={$query}";
			$srch_countries = null;
		}
		
	}
	
	if(!empty($_REQUEST['countries'])) {
		$countries = explode('|', trim($_REQUEST['countries']));
		foreach($countries AS &$c) $c = trim($c);
		$countries = implode('|', $countries);
		$search_url .= "&countries={$countries}";
		$has_filter = true;
		if(!empty($srch_countries)) {
			$search_url .= "|{$srch_countries}";
		}
	} else {
		if(!empty($srch_countries)) {
			$search_url .= "&countries={$srch_countries}";
			$has_filter = true;
		}
		/*
		if($has_filter!==true) {
			$countries = $_COUNTRY_ISO_MAP;
			unset($countries['WW']);
			$search_url .= "&countries=" . implode('|', array_keys($countries));
		}*/
		
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
			
			$return .= '<div class="searchresult row'.($idx%2).'">
                        	<a id="rdetail_'.$idx.'" href="javascript:void(0);" class="moredetail"></a>';
			$return .= '<h3><a href="'.$base_url.'/?page_id=2&amp;id='.$project->iati_identifier.'&amp;back_url='.rawurlencode($back_url).'">'.$project->titles[0]->title.'</a></h3>';			
			
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
					$return .= "No information available";
				} else {
					foreach($project->activity_sectors AS $sector) {
						$return .= $sep . $sector->name;
						$sep = ', ';
					}
				}
				$return .= '</span>';
			}
			
			$return .= '<p class="shortdescription">'.$project->descriptions[0]->description.'</p>';
			$return .= '<div class="resultdetail rdetail_'.$idx.'">';
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
							  <li><span>End date actual: </span>'.$project->end_actual.'</li>
							</ul>
						</div>';
			$return .= '<div class="clr"></div>
						<div class="resultrow">
							<a href="'.$base_url.'/?page_id=42" class="whistleb"><span>WHISTLEBLOWER</span></a>
							<a class="addthis_button share" href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=ra-50408ec91245851b" addthis:url="'.$base_url.'/?page_id=2&amp;id='.$project->iati_identifier.'&amp;back_url='.rawurlencode($back_url).'"><span>SHARE</span></a>
							<a href="javascript:bookmarksite(\''. addslashes($project->titles[0]->title).'\', \''.$base_url.'/?page_id=2&amp;id='.$project->iati_identifier.'&amp;back_url='.rawurlencode($back_url).'\')" class="bookmark"><span>BOOKMARK</span></a>	
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
			if(isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
				$query = rawurlencode($_REQUEST['s']);
				$srch_countries = array_map('strtolower', $_COUNTRY_ISO_MAP);
				$srch_countries = array_flip($srch_countries);
				$key = strtolower($query);
				if(isset($srch_countries[$key])) {
					$srch_countries = $srch_countries[$key];
				} else {
					$srch_countries = null;
				}
			}
			
			if(!empty($_REQUEST['countries'])) {
				$tmp = explode('|', $_REQUEST['countries']);
				foreach($tmp AS &$s) {
					$selected[$s] = $_COUNTRY_ISO_MAP[$s];
				}
				
				if(!empty($srch_countries) && !isset($selected[$srch_countries])) {
					$selected[$srch_countries] = $_COUNTRY_ISO_MAP[$srch_countries];
				}
				
				if(count($selected)>$limit) {
					$limit=count($selected);
					$_data = array_diff($_data, $selected);
				} else {
					$limit -= count($selected);
					$_data = array_diff($_data, $selected);
				}
			} else {
				if(!empty($srch_countries)) {
					$selected[$srch_countries] = $_COUNTRY_ISO_MAP[$srch_countries];
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
			if(empty($selected)) $checked = "checked=\"checked\"";
			$return .= "<li>
							<label for=\"id_countries_{$cnt}\"><input name=\"countries\" value=\"All\" id=\"id_countries_{$cnt}\" type=\"checkbox\" {$checked} />All</label>
						</li>";
			if(!empty($selected)) {
				foreach($selected AS $iso=>$c) {
					$checked = "checked=\"checked\"";
					$cnt++;
					$return .= "<li>
									<label for=\"id_countries_{$cnt}\"><input name=\"countries\" value=\"{$iso}\" id=\"id_countries_{$cnt}\" type=\"checkbox\" {$checked} /><span>{$c}</span></label>
								</li>";
				}
				
				$limit+=$cnt-1;
			}
			foreach($_data AS $iso=>$c) {
				$checked = "";
				if(isset($selected[$iso])) $checked = "checked=\"checked\"";
				$cnt++;
				$return .= "<li>
							<label for=\"id_countries_{$cnt}\"><input name=\"countries\" value=\"{$iso}\" id=\"id_countries_{$cnt}\" type=\"checkbox\" {$checked} /><span>{$c}</span></label>
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
			if(empty($selected)) $checked = "checked=\"checked\"";
			$return .= "<li>
							<label for=\"id_regions_{$cnt}\"><input name=\"regions\" value=\"All\" id=\"id_regions_{$cnt}\" type=\"checkbox\" {$checked} />All</label>
						</li>";
			if(!empty($selected)) {
				foreach($selected AS $iso=>$c) {
					$checked = "checked=\"checked\"";
					$cnt++;
					$return .= "<li>
									<label for=\"id_regions_{$cnt}\"><input name=\"regions\" value=\"{$iso}\" id=\"id_regions_{$cnt}\" type=\"checkbox\" {$checked} /><span>{$c}</span></label>
								</li>";
				}
				
				$limit+=$cnt-1;
			}
			foreach($_data AS $iso=>$c) {
				$checked = "";
				if(isset($selected[$iso])) $checked = "checked=\"checked\"";
				$cnt++;
				$return .= "<li>
							<label for=\"id_regions_{$cnt}\"><input name=\"regions\" value=\"{$iso}\" id=\"id_regions_{$cnt}\" type=\"checkbox\" {$checked} /><span>{$c}</span></label>
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
			if(empty($selected)) $checked = "checked=\"checked\"";
			$return .= "<li>
							<label for=\"id_sectors_{$cnt}\"><input name=\"sectors\" value=\"All\" id=\"id_sectors_{$cnt}\" type=\"checkbox\" {$checked} />All</label>
						</li>";
			if(!empty($selected)) {
				foreach($selected AS $iso=>$c) {
					$checked = "checked=\"checked\"";
					$cnt++;
					$return .= "<li>
									<label for=\"id_sectors_{$cnt}\"><input name=\"sectors\" value=\"{$iso}\" id=\"id_sectors_{$cnt}\" type=\"checkbox\" {$checked} /><span>{$c}</span></label>
								</li>";
				}
				
				$limit+=$cnt-1;
			}
			foreach($_data AS $iso=>$c) {
				$checked = "";
				if(isset($selected[$iso])) $checked = "checked=\"checked\"";
				$cnt++;
				$return .= "<li>
							<label for=\"id_sectors_{$cnt}\"><input name=\"sectors\" value=\"{$iso}\" id=\"id_sectors_{$cnt}\" type=\"checkbox\" {$checked} /><span>{$c}</span></label>
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
			if(empty($selected)) $checked = "checked=\"checked\"";
			$return .= "<li>
							<label for=\"id_budgets_{$cnt}\"><input name=\"budgets\" value=\"All\" id=\"id_budgets_{$cnt}\" type=\"checkbox\" {$checked} />All</label>
						</li>";
			foreach($_data AS $iso=>$c) {
				$checked = "";
				if(isset($selected[$iso])) $checked = "checked=\"checked\"";
				$cnt++;
				$return .= "<li>
							<label for=\"id_budgets_{$cnt}\"><input name=\"budgets\" value=\"{$iso}\" id=\"id_budgets_{$cnt}\" type=\"checkbox\" {$checked} /><span>{$c}</span></label>
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
							<div class="submtBtns rounded-corners" id="popupsubmtBtn">
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
			$return = preg_replace("/__FILTERDESC__/", "SEARCH", $return);
			
			$checked = "";
			if(empty($selected)) $checked = "checked=\"checked\"";
			$return .= '<div class="countrieslist">
							<div class="all">
								<label for="id_countries_0"><input name="countries" value="All" id="id_countries_0" type="checkbox" '.$checked.' />All</label>
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
					if(isset($selected[$iso])) $checked = "checked=\"checked\"";
					$cnt++;
					$return .= "<li>
								<input name=\"countries\" id=\"check-country{$cnt}\" class=\"check\" type=\"checkbox\" value=\"{$iso}\" {$checked} />
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
			$return = preg_replace("/__FILTERDESC__/", "SEARCH", $return);
			
			$checked = "";
			if(empty($selected)) $checked = "checked=\"checked\"";
			$return .= '<div class="regionslist">
							<div class="all">
								<label for="id_regions_0"><input name="regions" value="All" id="id_regions_0" type="checkbox" '.$checked.' />All</label>
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
					if(isset($selected[$iso])) $checked = "checked=\"checked\"";
					$cnt++;
					$return .= "<li>
								<input name=\"regions\" id=\"check-region{$cnt}\" class=\"check\" type=\"checkbox\" value=\"{$iso}\" {$checked} />
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
			$return = preg_replace("/__FILTERDESC__/", "SEARCH", $return);
			
			$checked = "";
			if(empty($selected)) $checked = "checked=\"checked\"";
			$return .= '<div class="sectorslist">
							<div class="all">
								<label for="id_sectors_0"><input name="sectors" value="All" id="id_sectors_0" type="checkbox" '.$checked.' />All</label>
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
					if(isset($selected[$iso])) $checked = "checked=\"checked\"";
					$cnt++;
					$return .= "<li>
								<input name=\"sectors\" id=\"check-sector{$cnt}\" class=\"check\" type=\"checkbox\" value=\"{$iso}\" {$checked} />
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
			$return = preg_replace("/__FILTERDESC__/", "SEARCH", $return);
			
			$checked = "";
			if(empty($selected)) $checked = "checked=\"checked\"";
			$return .= '<div class="budgetlist">
							<div class="all">
								<label for="id_budgets_0"><input name="budgets" value="All" id="id_budgets_0" type="checkbox" '.$checked.' />All</label>
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
				if(isset($selected[$iso])) $checked = "checked=\"checked\"";
				$cnt++;
				$return .= "<li>
							<input name=\"budgets\" id=\"check-budget{$cnt}\" class=\"check\" type=\"checkbox\" value=\"{$iso}\" {$checked} />
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
	global $_DEFAULT_ORGANISATION_ID;
	$activities_url = SEARCH_URL . "activities/?format=json&limit=0";
	if(!empty($_DEFAULT_ORGANISATION_ID)) {
		$activities_url .= "&organisations=" . $_DEFAULT_ORGANISATION_ID;
	}
	$content = file_get_contents($activities_url);
	$result = json_decode($content);
	$meta = $result->meta;
	$count = $meta->total_count;
	
	$start=0;
	$limit=50;
	$countries = array();
	$sectors = array();
	$regions = array();
	$budgets = array();
	$total_budget = 0;
	while($start<$count) {
		$activities_url = SEARCH_URL . "activities/?format=json&start={$start}&limit={$limit}";
		if(!empty($_DEFAULT_ORGANISATION_ID)) {
			$activities_url .= "&organisations=" . $_DEFAULT_ORGANISATION_ID;
		}
		$content = file_get_contents($activities_url);
		$result = json_decode($content);
		$objects = $result->objects;
		$activities = objectToArray($objects);
		
		foreach($activities AS $a) {
			if(!empty($a['recipient_country'])) {
				foreach($a['recipient_country'] AS $c) {
					if(isset($countries[$c['iso']])) continue;
					$countries[$c['iso']] = $c['name'];
					if(!isset($budgets[$c['iso']])) {
						$budgets[$c['iso']] = $a['statistics']['total_budget'];
					} else {
						$budgets[$c['iso']] += $a['statistics']['total_budget'];
					}
					$total_budget += $a['statistics']['total_budget'];
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
$_COUNTRY_BUDGETS = array(
';
$to_write .= "'all' => '{$total_budget}',
";
		if(!empty($budgets)) {
		
			foreach($budgets AS $key=>$value) {
				$to_write .= "'{$key}' => '{$value}',\n";
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
	global $_PER_PAGE;
	//fix the paging 
	$total_count = $meta->total_count;
	$offset = $meta->offset;
	$limit = $meta->limit;
	$per_page = $_PER_PAGE;
	$total_pages = ceil($total_count/$limit);
	$cur_page = $offset/$limit + 1;
	
	$paging_block = "<ul class='menu pagination'><li><a href='javascript:void(0);' class='start'><span>&laquo;</span></a></li><li><a href='javascript:void(0);' class='limitstart'><span>&larr; </span></a></li>";
	$page_limit = 3;
	$show_dots = true;
		

	for($i=1; $i<=$total_pages; $i++) {
		$paging_block .= "<li>";
		if ($i == 1 || $i == $total_pages || ($i >= $cur_page - $page_limit && $i <= $cur_page + $page_limit) ) {
			$show_dots = true;
			if($cur_page==$i) {
				$paging_block .= "<a href='javascript:void(0);' class='active'><span id='cur_page'>{$i}</span></a>";
			} else {
				$paging_block .= "<a href='javascript:void(0);' class='page'><span>{$i}</span></a>";
			}
		
		} else if ($show_dots == true) {
			$show_dots = false;
			$paging_block .= "...";
		}
		$paging_block .= "</li>";
	}
		
	$paging_block .= "<li><a href='javascript:void(0);' class='endmilit'><span>&rarr; </span></a></li><li><a href='javascript:void(0)' class='end'><span>&raquo;</span></a></li></ul>";
	
	echo $paging_block;
}

function wp_generate_home_map_data() {
		global $_GM_POLYGONS, $_DEFAULT_ORGANISATION_ID;
		$activities_url = SEARCH_URL . "activities/?format=json&limit=0";
		if(!empty($_DEFAULT_ORGANISATION_ID)) {
			$activities_url .= "&organisations=" . $_DEFAULT_ORGANISATION_ID;
		}
		$content = file_get_contents($activities_url);
		$result = json_decode($content);
		$meta = $result->meta;
		$limit = $meta->total_count;
		$activities_url = SEARCH_URL . "activities/?format=json&limit={$limit}";
		if(!empty($_DEFAULT_ORGANISATION_ID)) {
			$activities_url .= "&organisations=" . $_DEFAULT_ORGANISATION_ID;
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

function chart_scripts_method() {
    	wp_deregister_script( 'jsapi' );
    	wp_register_script( 'jsapi', 'http://www.google.com/jsapi');
    	wp_enqueue_script( 'jsapi' );
	}   
	add_action('wp_enqueue_scripts', 'chart_scripts_method');
?>