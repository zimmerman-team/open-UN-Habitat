<?php get_header(); ?>
<?php
$details=$_REQUEST['details'];
if(isset($details))
	$details=$_REQUEST['details'];
else
	$details=array("all");
?>
<!--START MAINBODY-->
    <div id="opunh-mainbody">
     <!--COL-LEFT-->
		<div class="colLeft sm">
			<div class="filter" id="filterContainer">
				<div class="filterbox">
					<h4>By Country</h4>
					<div class="filtercontent">
						<?php echo wp_generate_filter_html('country'); ?>
					</div>
				</div>
				<!--COUNTRIES-->
				<?php echo  wp_generate_filter_popup('country'); ?>
				<!--END COUNTRIES-->
				<div class="filterbox">
					<h4>By Region</h4>
					<div class="filtercontent">
						<?php echo wp_generate_filter_html('region'); ?>
					</div>
				</div>
				<!--REGIONS-->
				<?php echo  wp_generate_filter_popup('region'); ?>
				<div class="filterbox">
					<h4>By Sector</h4>
					<div class="filtercontent">
						<?php echo wp_generate_filter_html('sector'); ?>
					</div>
				</div>
				<!--SECTORS-->
				<?php echo  wp_generate_filter_popup('sector'); ?>
				<div class="filterbox">
					<h4>By Budget</h4>
					<div class="filtercontent">
						<?php echo wp_generate_filter_html('budget'); ?>
					</div>
				</div>
			</div>
		</div>
	  <!--COL-RIGHT-->
		<div class="colRight">
			<div class="searchresultslist">
				<iframe id="secretIFrame" src="" style="display:none; visibility:hidden;"></iframe>
				<?php 
				$layout=$_REQUEST['layout'];
				wp_generate_results($details, $meta, $projects_html, $has_filter);
				if($layout=="m"){?> 
					<!--SEARCH TITLE BAR-->
							<div class="searchresultstitle">
								<h4>Results <?php echo $meta->offset + 1; ?> - <?php if(($meta->offset + $meta->limit)>$meta->total_count) { echo $meta->total_count; } else { echo ($meta->offset + $meta->limit); } ?> of <?php echo $meta->total_count; ?> </h4>
								<div class="layout">
									<span>view results as:</span>
									<ul>
										<li class="vlist"><a href="<?php echo get_option('home'); ?><?php echo str_replace(array(" ", "&amp;layout=m"),array("+", ""),$_SERVER['REQUEST_URI']); ?>" title="View as List"><span>View as List</span></a></li>
										<li class="vmap"><a href="<?php echo get_option('home'); ?><?php echo str_replace(" ","+",$_SERVER['REQUEST_URI']); ?>&amp;layout=m" class="active" title="View as Map"><span>View as Map</span></a></li>
									</ul>
								</div>
								<div class="searchcriteria" <?php echo ($has_filter?'':' style="display: none;"')?>>
									<div class="clearresults">
										<a href="<?php echo get_option('home'); ?>?s=" id="clearSearchBtn">Clear Search<span>X</span></a>
									</div>
									<ul>
									   <?php
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
											echo "<li><span>Countries:</span>";
											$sep = '';
											$tmp = explode('|', $_REQUEST['countries']);
											$countries = "";
											$cSep = "";
											foreach($tmp AS &$s) {
												echo $sep .'<a href="#">'.$_COUNTRY_ISO_MAP[$s].'</a>';
												$sep = ', ';
												$countries .= $cSep . $s;
												$cSep = '|';
											}
											if(!empty($srch_countries) && !in_array($srch_countries, $tmp)) {
												echo $sep .'<a href="#">'.$_COUNTRY_ISO_MAP[$srch_countries].'</a>';
												$countries .= $cSep . $srch_countries;
											}
											echo "</li>";
										} else {
											if(!empty($srch_countries)) {
												echo "<li><span>Countries:</span>";
												echo '<a href="#">'.$_COUNTRY_ISO_MAP[$srch_countries].'</a>';
												$countries = $srch_countries;
												echo "</li>";
											}
										}
										
										if(!empty($_REQUEST['regions'])) {
											echo "<li><span>Regions:</span>";
											$sep = '';
											$tmp = explode('|', $_REQUEST['regions']);
											foreach($tmp AS &$s) {
												echo $sep .'<a href="#">'.$_REGION_CHOICES[$s].'</a>';
												$sep = ', ';
											}
											echo "</li>";
										}
										
										if(!empty($_REQUEST['sectors'])) {
											echo "<li><span>Sectors:</span>";
											$sep = '';
											$tmp = explode('|', $_REQUEST['sectors']);
											foreach($tmp AS &$s) {
												echo $sep .'<a href="#">'.$_SECTOR_CHOICES[$s].'</a>';
												$sep = ', ';
											}
											echo "</li>";
										}
										
										if(!empty($_REQUEST['budgets'])) {
											echo "<li><span>Budget:</span>";
											$sep = '';
											$tmp = explode('|', $_REQUEST['budgets']);
											foreach($tmp AS &$s) {
												echo $sep .'<a href="#">'.$_BUDGET_CHOICES[$s].'</a>';
												$sep = ', ';
											}
											echo "</li>";
										}
									?>
									</ul>
								</div>
							</div>
						<!--END SEARCH TITLE BAR-->
						<!--SEARCH RESULTS-->
							<div class="searchresultsmap">
							<div id="map">
								<div id="map_canvas" style="width: 720px; height: 500px;"></div>
							</div>
							</div>
						<!--END SEARCH RESULTS-->
					<?php } 
					else {?>
						<!--SEARCH TITLE BAR-->
						<div class="searchresultstitle">
							<h4>Results <?php echo $meta->offset + 1; ?> - <?php if(($meta->offset + $meta->limit)>$meta->total_count) { echo $meta->total_count; } else { echo ($meta->offset + $meta->limit); } ?> of <?php echo $meta->total_count; ?> </h4>
							<div class="layout">
								<span>view results as:</span>
								<ul>
									<li class="vlist"><a href="<?php echo get_option('home'); ?><?php echo str_replace(array(" ", "&amp;layout=m"),array("+", ""),$_SERVER['REQUEST_URI']); ?>" class="active" title="View as List"><span>View as List</span></a></li>
									<li class="vmap"><a href="<?php echo get_option('home'); ?><?php echo str_replace(" ","+",$_SERVER['REQUEST_URI']); ?>&amp;layout=m" title="View as Map"><span>View as Map</span></a></li>
								</ul>
							</div>
							<div class="searchcriteria" <?php echo ($has_filter?'':' style="display: none;"')?>>
								<div class="clearresults">
									<a href="<?php echo get_option('home'); ?>?s=" id="clearSearchBtn">Clear Search<span>X</span></a>
								</div>
								<ul>
									<?php 
									
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
											echo "<li><span>Countries:</span>";
											$sep = '';
											$tmp = explode('|', $_REQUEST['countries']);
											foreach($tmp AS &$s) {
												echo $sep .'<a href="#">'.$_COUNTRY_ISO_MAP[$s].'</a>';
												$sep = ', ';
											}
											if(!empty($srch_countries) && !in_array($srch_countries, $tmp)) {
												echo $sep .'<a href="#">'.$_COUNTRY_ISO_MAP[$srch_countries].'</a>';
												$countries .= $cSep . $srch_countries;
											}
											echo "</li>";
										} else {
											if(!empty($srch_countries)) {
												echo "<li><span>Countries:</span>";
												echo '<a href="#">'.$_COUNTRY_ISO_MAP[$srch_countries].'</a>';
												$countries = $srch_countries;
												echo "</li>";
											}
										}
										
										if(!empty($_REQUEST['regions'])) {
											echo "<li><span>Regions:</span>";
											$sep = '';
											$tmp = explode('|', $_REQUEST['regions']);
											foreach($tmp AS &$s) {
												echo $sep .'<a href="#">'.$_REGION_CHOICES[$s].'</a>';
												$sep = ', ';
											}
											echo "</li>";
										}
										
										if(!empty($_REQUEST['sectors'])) {
											echo "<li><span>Sectors:</span>";
											$sep = '';
											$tmp = explode('|', $_REQUEST['sectors']);
											foreach($tmp AS &$s) {
												echo $sep .'<a href="#">'.$_SECTOR_CHOICES[$s].'</a>';
												$sep = ', ';
											}
											echo "</li>";
										}
										
										if(!empty($_REQUEST['budgets'])) {
											echo "<li><span>Budget:</span>";
											$sep = '';
											$tmp = explode('|', $_REQUEST['budgets']);
											foreach($tmp AS &$s) {
												echo $sep .'<a href="#">'.$_BUDGET_CHOICES[$s].'</a>';
												$sep = ', ';
											}
											echo "</li>";
										}
									?>
								</ul>
							</div>
						</div>
					<!--END SEARCH TITLE BAR-->
					<!--TOOLBAR-->
						<div class="searchtoolbar">
							<a href="#" class="saveresults">Save Search Results</a>
							<ul class="menu orderby">
								<li class="sortbylabel"><span>Sort by:</span></li>
								<li class="sortby sortby_b"><a href="javascript:void(0);"><span>Budget</span></a></li>
								<li class="sortby sortby_d desc"><a href="javascript:void(0);"><span>Start date</span></a></li>
								<li class="sortby sortby_c"><a href="javascript:void(0);"><span>Country</span></a></li>
								<li class="sortbydetails sortby_det"><a href="javascript:void(0);"><span>Details</span></a></li>
							</ul> 
							<div id="contxmenu_det2">
								  <ul>
									<li<?php if(in_array("all",$details)){?> class="active"<?php }?>><a href="<?php echo get_option('home'); ?>/?s=<?php echo str_replace(" ","+",$_REQUEST['s']); ?>&amp;details[]=all">On</a></li>
									<li<?php if(in_array("none",$details)){?> class="active"<?php }?>><a href="<?php echo get_option('home'); ?>/?s=<?php echo str_replace(" ","+",$_REQUEST['s']); ?>&amp;details[]=none">Off</a></li>
									<li class="customlink<?php if(count($details)>0 && count($details)<4 && !in_array("all",$details) && !in_array("none",$details)){?> active<?php }?>" >
										<a href="javascript:void(0);">Custom</a>
										<form action="<?php echo get_option('home'); ?>/?s=<?php echo str_replace(" ","+",$_REQUEST['s']); ?>" method="post" name="detailForm" id="detailForm">
											<label><input name="details[]" <?php if(in_array("all",$details) ||in_array("country",$details) ){?>checked="checked"<?php } ?>value="country" id="detail_1" type="checkbox" />Country</label>
											<label><input name="details[]" <?php if(in_array("all",$details) || in_array("subject",$details) ){?>checked="checked"<?php } ?>value="subject" id="detail_2" type="checkbox" />Subject</label>
											<label><input name="details[]" <?php if(in_array("all",$details) || in_array("budget",$details) ){?>checked="checked"<?php } ?>value="budget" id="detail_3" type="checkbox" />Budget</label>
											<label><input name="details[]" <?php if(in_array("all",$details) || in_array("sector",$details) ){?>checked="checked"<?php } ?>value="sector" id="detail_4" type="checkbox" />Sector</label>
										</form>
										<a href="javascript:void(0);" id="submitButton" class="linkbtn"><strong>Apply</strong></a>|<a id="cancelButton" href="javascript:void(0);" class="linkbtn">Cancel</a>
									</li>
								  </ul>
							  </div>
						</div>
					<!--END TOOLBAR-->
					<!--SEARCH RESULTS-->
						<div class="searchresults" id="resultsContainer">
							
							<?php echo $projects_html; ?>
							
						</div>
						
						<!--Pagination-->
						<input type="hidden" id="total_results" value="<?php echo $meta->total_count; ?>" />
						<div id="pagination">
							<?php echo wp_generate_paging($meta); ?>
						<div class="clr"></div>
					</div>
					<!--END SEARCH RESULTS-->
				<?php }?>
			</div>
		</div>
		<div class="clr"></div>
	</div>
    <!--END MAINBODY-->
	<!--  Budget Context menu -->
	<ul id="contxmenu_b" class="jeegoocontext cm_blue">
		<li class="active"><a id='asc' href="#">Ascending</a></li>
		<li><a id='desc' href="#">Descending</a></li>
	</ul>
	<!--  Date Context menu -->
	<ul id="contxmenu_d" class="jeegoocontext cm_blue">
		<li><a id='asc' href="#">Ascending</a></li>
		<li class="active"><a id='desc' href="#">Descending</a></li>
	</ul>
        <!--  Country Context menu -->
        <ul id="contxmenu_c" class="jeegoocontext cm_blue">
            <li class="active"><a id='asc' href="#">Ascending</a></li>
            <li><a id='desc' href="#">Descending</a></li>
        </ul> 
	<?php 
		$layout=$_REQUEST['layout'];
		if(!empty($_REQUEST['countries'])) {
			$countries = trim($_REQUEST['countries']);
		}
		$search_url = "";
		if(isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
			$query = rawurlencode($_REQUEST['s']);
			$search_url .= "&amp;query={$query}";
		}
		if(!empty($_REQUEST['regions'])) {
			$regions = explode('|', trim($_REQUEST['regions']));
			foreach($regions AS &$c) $c = trim($c);
			$regions = implode('|', $regions);
			$search_url .= "&amp;regions={$regions}";
		}
		
		if(!empty($_REQUEST['sectors'])) {
			$sectors = explode('|', trim($_REQUEST['sectors']));
			foreach($sectors AS &$c) $c = trim($c);
			$sectors = implode('|', $sectors);
			$search_url .= "&amp;sectors={$sectors}";
		}
		
		if(!empty($_REQUEST['budgets'])) {
			$budgets = explode('|', trim($_REQUEST['budgets']));
			//Get the lowest budget from filter and use this one, all the other are included in the range
			ksort($budgets);
			$search_url .= "&amp;budgets={$budgets[0]}";
		}
		if($layout=='m') { 
	?>
	<script type="text/javascript" charset="utf-8">
		function initPageMap(country) {
			url = "<?php bloginfo('template_directory') ?>/map_search.php?countries=<?php echo $countries ?><?php echo $search_url; ?>";
			var countries = '<?php echo $countries ?>';

			$.ajax({
				url: url,
				type: "GET",
				dataType: "json",
				success: function(data){
					initMap(data);
				},
				error: function(msg){
					alert('AJAX error!' + msg);
					return false;
				}
			});


			function initMap(result) {
				var myLatLng = new google.maps.LatLng(9.795678,26.367188);
				var myOptions = {
					zoom : 2,
					center : myLatLng,
					mapTypeId : google.maps.MapTypeId.ROADMAP,
					scrollwheel: false,
					streetViewControl : false
				};

				var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
				if (!google.maps.Polygon.prototype.getBounds) {

					google.maps.Polygon.prototype.getBounds = function(latLng) {

							var bounds = new google.maps.LatLngBounds();
							var paths = this.getPaths();
							var path;
							
							for (var p = 0; p < paths.getLength(); p++) {
									path = paths.getAt(p);
									for (var i = 0; i < path.getLength(); i++) {
											bounds.extend(path.getAt(i));
									}
							}

							return bounds;
					}

				}
				var data = result['objects'];
				for(idx in data) {
					var lats = [];
					var lat_size =  data[idx]['path'].length;

					for (var t=0; t <lat_size; t++) {
						var inner = [];
						for (var i=0; i <data[idx]['path'][t].length; i++) {
							var lat = data[idx]['path'][t][i].split(',');
							inner.push(new google.maps.LatLng(lat[0], lat[1]));
						}
						lats.push(inner);
					}
					var polygon = new google.maps.Polygon({
						paths: lats,
						strokeColor: "#FFFFFF",
						strokeOpacity: 0.8,
						strokeWeight: 2,
						fillColor: "#F96B15",
						fillOpacity: 0.65,
						country: data[idx]['name'],
						total_cnt: data[idx]['total_cnt'],
						total_activities_url: "?countries="+idx,
						iso2 : idx
					});
					if (countries) map.setCenter(polygon.getBounds().getCenter());
					polygon.setMap(map);
					google.maps.event.addListener(polygon, 'click', showInfo);
					infowindow = new google.maps.InfoWindow();
					google.maps.event.addListener(infowindow, 'closeclick', resetColor);
				}
				
				function showInfo(event){
					if (typeof currentPolygon != 'undefined') {
						currentPolygon.setOptions({fillColor: "#F96B15"});
					}
					this.setOptions({fillColor: "#2D6A98"});
					var keyword = $('#s').val();
					
					if(keyword) {
						keyword = encodeURI(keyword);
					}
					var contentString = "" + 
					"<h2>" + 
						"<img src='<?php echo bloginfo('template_url'); ?>/images/flags/" + this.iso2.toLowerCase() + ".gif' />" +
						this.country + 
					"</h2>" +
					"<dl>" +
					"<dt>Total Activities:</dt>" +
					"<dd>" +
						"<a href=?s=" + keyword + "&countries=" + this.iso2 + ">"+this.total_cnt+" project(s)</a>" +
					"</dd>" +
						"<a href=?s=" + keyword + "&countries=" + this.iso2 + ">show all activities for this country</a>" +
					"</dl>";
					
					infowindow.setContent(contentString);
					infowindow.setPosition(event.latLng);
					infowindow.open(map);
					currentPolygon = this;
				}
				
				function resetColor(){
					currentPolygon.setOptions({fillColor: "#F96B15"});
				}
			}
		}
		$(document).ready(function() {
			var script = document.createElement("script");
			script.type = "text/javascript";
			script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=initPageMap";
			document.body.appendChild(script);
		});
	</script>
		
	<?php } ?>
    	<script type="text/javascript">
			var addthis_config = {"data_track_addressbar":true,"ui_click":true};
		</script>
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=mwongzie"></script>
<?php get_footer(); ?>
