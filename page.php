<?php get_header(); ?>
<?php
$back_url = $_REQUEST['back_url'];
if(empty($back_url) && !empty($_SERVER['HTTP_REFERER'])) $back_url = $_SERVER['HTTP_REFERER'];

$project_id = $_REQUEST['id'];

$activity = wp_get_activity($project_id);

?>
<a name="top"></a>
<!--START MAINBODY-->
    <div id="opunh-mainbody">
    <?php 
    if(!is_page(2)){?>
    	<?php get_sidebar(); ?>
    
     <!--COL-RIGHT-->
          <div class="colRight">
            <div class="content">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
			<h2><span><?php the_title(); ?></span></h2>
			<?php //include (TEMPLATEPATH . '/inc/meta.php' ); ?>
			<div class="contentext">
				<?php the_content(); ?>
				<?php wp_link_pages(array('before' => 'Pages: ', 'next_or_number' => 'number')); ?>
			</div>
			<?php //edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
		</div>
		<?php endwhile; endif; ?>
        </div>
        <div class="clr"></div>
      </div>
    <?php }
	else{?>
	<iframe id="secretIFrame" src="" style="display:none; visibility:hidden;"></iframe>
    <a href="<?php echo $back_url; ?>" class="backbutton">Back to Search Results</a>
            <div class="clr"></div>
        	<!--SEARCH TITLE BAR-->
            	<div class="projecttitle">
                 	<h2><span><?php echo $activity->titles[0]->title; ?></span></h2>
                    	<ul class="shrtdetail">
							<?php if(!empty($activity->recipient_country)) {?>
								<li><span>Countries:</span><?php
									$sep = '';
									$countries = "";
									$cSep = "";
									foreach($activity->recipient_country AS $country) {
										echo  $sep . "<a href='?s=&countries={$country->iso}'>" . $country->name . "</a>";
										$countries .= $cSep . $country->iso;
										$sep = ', ';
										$cSep = '|';
									}
								?></li>
							<?php } ?>
							<?php if(!empty($activity->activity_sectors)) {?>
								<li><span>Principal Sector:</span><?php
									$sep = '';
									foreach($activity->activity_sectors AS $sector) {
										if($sector->name=='No information available') {
											echo $sector->name;
										} else {
											echo  $sep . "<a href='?s=&sectors={$sector->code}'>" . $sector->name . "</a>";
										}
										$sep = ', ';
									}
								?></li>
							<?php } ?>
                        </ul>
                        <ul class="menu actions">
                           <li><a href="#" id="<?php echo $activity->iati_identifier; ?>" class="export"><span>Export</span></a></li>
                           <li><a href="#" class="share"><span>SHARE</span></a></li>
                           <li><a href="javascript:bookmarksite('Promoting Sustainable Transport Solutions for East Africa', 'http://www.openunh-projectdetail.com')" class="bookmark"><span>BOOKMARK</span></a></li>
                           <li><a href="<?php echo get_option('home'); ?>/?page_id=42" class="whistleb"><span>WHISTLEBLOWER</span></a></li>
                        </ul>
                </div>
            <!--END SEARCH TITLE BAR-->
            <!--COL-LEFT-->
              <div class="colLeftlg">
                <div class="projectsum">
                  <ul>
					<?php if(!empty($activity->iati_identifier)) {?><li><span>IATI identifier : </span><?php echo $activity->iati_identifier; ?></li><?php } ?>
                    <?php if(!empty($activity->reporting_organisation->org_name)) {?><li class="row1"><span>Reporting organisation :</span> <?php echo $activity->reporting_organisation->org_name; ?></li><?php } ?>
                    <?php if(!empty($activity->start_actual)) {?><li><span>Start-date :</span> <?php echo $activity->start_actual; ?></li><?php } ?>
                    <?php if(!empty($activity->activity_sectors[0]->code)) {?><li class="row1"><span>Sector code :</span> <?php echo $activity->activity_sectors[0]->code; ?></li><?php } ?>
                    <?php if(!empty($activity->date_updated)) {?><li><span>Last updated :</span> <?php echo $activity->date_updated; ?></li><?php } ?>
                    <?php if(!empty($activity->start_planned)) {?><li class="row1"><span>Start date planned :</span> <?php echo $activity->start_planned; ?></li><?php } ?>
                    <?php if(!empty($activity->end_planned)) {?><li><span>End date planned: </span> <?php echo $activity->end_planned; ?></li><?php } ?>
                    <?php if(!empty($activity->end_actual)) {?><li class="row1"><span>End date actual :</span> <?php echo $activity->end_actual; ?></li><?php } ?>
                    <?php if(!empty($activity->collaboration_type->name)) {?><li><span>Collaboration type :</span> <?php echo $activity->collaboration_type->code; ?>. <?php echo $activity->collaboration_type->name; ?></li><?php } ?>
                    <?php if(!empty($activity->default_flow_type->name)) {?><li class="row1"><span>Flow type :</span> <?php echo $activity->default_flow_type->name; ?></li><?php } ?>
                    <?php if(!empty($activity->default_aid_type->name)) {?><li><span>Aid type :</span> <?php echo (empty($activity->default_aid_type->code)?'':$activity->default_aid_type->code .'.'); ?> <?php echo $activity->default_aid_type->name; ?></li><?php } ?>
                    <?php if(!empty($activity->default_finance_type->name)) {?><li class="row1"><span>Finance type :</span> <?php echo $activity->default_finance_type->name; ?></li><?php } ?>
                    <?php if(!empty($activity->default_tied_status_type->name)) {?><li><span>Tying status :</span> <?php echo $activity->default_tied_status_type->name?></li><?php } ?>
                    <?php if(!empty($activity->activity_status->name)) {?><li class="row1"><span>Activity status :</span> <?php echo $activity->activity_status->name?></li><?php } ?>
                    <?php if(!empty($activity->reporting_organisation->org_name)) {?><li><span>Name participating organisation :</span> <?php echo $activity->reporting_organisation->org_name; ?></li><?php } ?>
                    <?php if(!empty($activity->reporting_organisation->ref)) {?><li class="row1"><span>Organisation reference code :</span> <?php echo $activity->reporting_organisation->ref; ?></li><?php } ?>
                  </ul>
                </div>
              </div>
             <!--COL-RIGHT-->
              <div class="colRightlg">
                <div class="projectdetails">
                    <!--DESCRIPTION-->
                        <div class="longdetail">
                        	<h4>Description</h4>
                            <div class="wrap desc">
                            	<div id="prjctMap" class="map">
									<div id="map_canvas" style="width: 230px; height: 140px;"></div>
                                </div>
                              <p><?php echo $activity->descriptions[0]->description; ?></p>
							  <p>&nbsp;</p>
                         
                          </div>
                        </div>
                    <!--DOCUMENTS-->
                    	<div class="longdetail">
                       	 	<h4>Documents</h4>
                             <div class="wrap docs">
								<h2>No information available</h2>
                             </div>
                        </div>
                   <!--COMMITMENTS-->
                    <div class="longdetail">
                        <h4>Commitments</h4>
                         <div class="wrap comit">
							<h2>No information available</h2>                             
                         </div>
                    </div>
                    
                </div>
              </div>
		<script type="text/javascript" charset="utf-8">
			function initPageMap(country) {
				var url = "<?php bloginfo('template_directory') ?>/map_search.php?countries=<?php echo $countries ?>";

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
					var myLatLng = new google.maps.LatLng(-3.2013100765,-9.64460607187);
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
						map.setCenter(polygon.getBounds().getCenter());
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
	<?php }	?>
    
	<div class="clr"></div>
  </div>
    <!--END MAINBODY-->
<?php get_footer(); ?>
