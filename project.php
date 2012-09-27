<?php
/*
Template Name: Project Details
*/
?>
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
	<iframe id="secretIFrame" src="" style="display:none; visibility:hidden;"></iframe>
    <ul id="actions" class="menu actions">
                           <li><a href="#" id="<?php echo $activity->iati_identifier; ?>" class="export"><span>Export</span></a></li>
                           <li><a class="addthis_button share" href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=ra-50408ec91245851b" addthis:url="<?php echo get_option('home'); ?>?page_id=2&amp;id=<?php echo $_REQUEST['id'];?>&amp;back_url=<?php echo $_REQUEST['back_url'];?>"><span>SHARE</span></a></li>
                           <li><a href="javascript:bookmarksite('<?php echo addslashes($activity->titles[0]->title); ?>', '<?php echo get_option('home'); ?>?page_id=2&amp;id=<?php echo $_REQUEST['id'];?>&amp;back_url=<?php echo $_REQUEST['back_url'];?>')" class="bookmark"><span>BOOKMARK</span></a></li>
                           <li><a href="<?php echo get_option('home'); ?>/?page_id=42" class="whistleb"><span>WHISTLEBLOWER</span></a></li>
                        </ul>
    <a href="<?php echo $back_url; ?>" class="backbutton">Back to Search Results</a>
            <div class="clr"></div>
        	<!--SEARCH TITLE BAR-->
            	<div class="projecttitle">
                 	<h2><span><?php echo $activity->titles[0]->title; ?></span></h2>
                    	<ul class="shrtdetail">
							<?php if(!empty($activity->recipient_country)) {?>
								<li><p class="label">Countries:</p><p class="list"><?php
									$sep = '';
									$countries = "";
									$cSep = "";
									foreach($activity->recipient_country AS $country) {
										echo  $sep . "<a href='".get_bloginfo('url')."/?s=&amp;countries={$country->iso}'>" . $country->name . "</a>";
										$countries .= $cSep . $country->iso;
										$sep = ', ';
										$cSep = '|';
									}
								?></p></li>
							<?php } ?>
							<?php if(!empty($activity->activity_sectors)) {?>
								<li><p class="label">Principal Sector:</p><p class="list"><?php
									$sep = '';
									foreach($activity->activity_sectors AS $sector) {
										if($sector->name=='No information available') {
											echo $sector->name;
										} else {
											echo  $sep . "<a href='".get_bloginfo('url')."/?s=&amp;sectors={$sector->code}'>" . $sector->name . "</a>";
										}
										$sep = ', ';
									}
								?></p></li>
							<?php } ?>
							<?php if(!empty($activity->statistics->total_budget)) {?>
								<li><p class="label">Budget:</p><p class="list"><span> US$ <?php echo format_custom_number($activity->statistics->total_budget)  ?></span></p></li>
							<?php } ?>
                        </ul> 
                        <div class="clr"></div>  
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
									<div id="map_canvas" style="width: 330px; height: 210px;"></div>
                                </div>
                              <p><?php echo $activity->descriptions[0]->description; ?></p>
							  <p>&nbsp;</p>
                         
                          </div>
                        </div>
                    <!--DOCUMENTS-->
                    	<div class="longdetail">
                       	 	<h4>Documents</h4>
                             <div class="wrap docs">
								<?php if(empty($activity->documents)) {?>
									<h2>No information available</h2>
								<?php } else { ?>
									
									<ul>
									<?php
									foreach($activity->documents AS $doc) {
										$class	= "";
										if(!empty($doc->format)) {
											$class = " class='" . str_replace('application/', '', $doc->format) . "'";
										}
										echo "<li{$class}>";
										echo "<a href='{$doc->url}'>";
										//echo substr($doc->url, strrpos($doc->url,'/')+1);
										if(empty($doc->title)) {
											echo $activity->titles[0]->title;
										} else {
											echo $doc->title;
										}
										$s = array('bytes', 'kb', 'MB', 'GB', 'TB', 'PB');
										$bytes = strlen(file_get_contents($doc->url));
										$e = floor(log($bytes)/log(1024));
							 
										//CREATE COMPLETED OUTPUT
										$filesize = sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e))));
										echo "<span>" . $filesize . "</span>";
										echo "</a>";
										echo "</li>";
									} 
									?>
									</ul>
									
								<?php } ?>
                             </div>
                        </div>
                   <!--COMMITMENTS-->
                    <div class="longdetail">
                        <h4>Commitments</h4>
                         <div class="wrap comit">
							<?php if(empty($activity->activity_transactions)) {?>
							<h2>No information available</h2> 
							<?php } else { ?>
								<table cellpadding="0" cellspacing="0" class="comitWrap">
									<tr>
									<?php 
										$cnt = 0;
										foreach($activity->activity_transactions AS $at) {
											$cnt++;
											$align = 'left';
											if($cnt==2) $align = 'right';
											$currency = '';
											switch($at->currency) {
												case 'USD':
													$currency = 'US$ ';
													break;
												case 'EUR':
													$currency = '&euro; ';
													break;
												case 'GBP':
													$currency = '£ ';
													break;
											}
											$value = format_custom_number($at->value);
											$provider_org = EMPTY_LABEL;
											if(!empty($activity->reporting_organisation->org_name)) {
												$provider_org = $activity->reporting_organisation->org_name;
											}
											$reciver_org = EMPTY_LABEL;
											if(!empty($activity->participating_organisations)) {
												$reciver_org = $activity->participating_organisations[0]->org_name;
											}
											echo "<td width='50%' align='{$align}'>
														<table cellspacing='0' cellpadding='0' class='comitTable'>
															  <tr>
																<th>Activity</th>
																<td>{$activity->titles[0]->title}</td>
															  </tr>
															  <tr>
																<th>Provider org</th>
																<td>{$provider_org}</td>
															  </tr>
															  <tr>
																<th>Receiver org</th>
																<td>{$reciver_org}</td>
															  </tr>
															  <tr>
																<th>Value</th>
																<td>{$currency}{$value}</td>
														  </tr>
															  <tr>
																<th>Transaction date</th>
																<td>{$at->transaction_date}</td>
															  </tr>
													  </table>
												  </td>";
									
											if($cnt==2) {
												$cnt=0;
												echo "</tr><tr>";
											
											}
										}
									?>
									</tr>
								</table>
							<?php }?>
                         </div>
                    </div>

                   <!--RELATED PROJECTS-->
                    <div class="longdetail">
                        <h4>Related Projects</h4>
                         <div class="wrap rel">
							<h2>No information available</h2>                             
                         </div>
                    </div>
                    
                </div>
              </div>
		<script type="text/javascript" charset="utf-8">
		<!--
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
					var myLatLng = new google.maps.LatLng(9.795678,26.367188);
					var myOptions = {
						zoom : 2,
						center : myLatLng,
						mapTypeId : google.maps.MapTypeId.TERRAIN,
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
								"<a href='<?php echo get_bloginfo('url'); ?>/?s=" + keyword + "&countries=" + this.iso2 + "'>"+this.total_cnt+" project(s)</a>" +
							"</dd>" +
							"<a href='<?php echo get_bloginfo('url'); ?>/?s=" + keyword + "&countries=" + this.iso2 + "'>show all activities for this country</a>" +
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
			// -->
		</script>
	<div class="clr"></div>
  </div>
    <!--END MAINBODY-->
    <script type="text/javascript">var addthis_config = {"data_track_addressbar":true,"ui_click":true};</script>
    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=mwongzie"></script>
<?php get_footer(); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
