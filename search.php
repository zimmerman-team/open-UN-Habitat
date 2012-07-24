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
                </div>
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
                                    <li class="list"><a href="<?php echo get_option('home'); ?><?php echo str_replace(array(" ", "&layout=m"),array("+", ""),$_SERVER['REQUEST_URI']); ?>" title="View as List"><span>View as List</span></a></li>
                                    <li class="map"><a href="<?php echo get_option('home'); ?><?php echo str_replace(" ","+",$_SERVER['REQUEST_URI']); ?>&layout=m" class="active" title="View as Map"><span>View as Map</span></a></li>
                                </ul>
                            </div>
                            <div class="searchcriteria" <?php echo ($has_filter?'':' style="display: none;"')?>>
                                <div class="clearresults">
                                    <a href="javascript:void(0);" id="clearSearchBtn">Clear Search<span>X</span></a>
                                </div>
                                <ul>
                                   <?php 
									if(!empty($_REQUEST['countries'])) {
										echo "<li><span>Countries:</span>";
										$sep = '';
										$tmp = explode('|', $_REQUEST['countries']);
										foreach($tmp AS &$s) {
											echo $sep .'<a href="#">'.$_COUNTRY_ISO_MAP[$s].'</a>';
											$sep = ', ';
										}
										echo "</li>";
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
                            <iframe width="720" height="500" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/?ie=UTF8&amp;ll=26.431228,-71.015625&amp;spn=126.703667,346.289063&amp;t=m&amp;z=2&amp;output=embed"></iframe>
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
                                <li class="list"><a href="<?php echo get_option('home'); ?><?php echo str_replace(array(" ", "&layout=m"),array("+", ""),$_SERVER['REQUEST_URI']); ?>" class="active" title="View as List"><span>View as List</span></a></li>
                                <li class="map"><a href="<?php echo get_option('home'); ?><?php echo str_replace(" ","+",$_SERVER['REQUEST_URI']); ?>&layout=m" title="View as Map"><span>View as Map</span></a></li>
                            </ul>
                        </div>
                        <div class="searchcriteria" <?php echo ($has_filter?'':' style="display: none;"')?>>
                        	<div class="clearresults">
                            	<a href="javascript:void(0);" id="clearSearchBtn">Clear Search<span>X</span></a>
                            </div>
                            <ul>
								<?php 
									if(!empty($_REQUEST['countries'])) {
										echo "<li><span>Countries:</span>";
										$sep = '';
										$tmp = explode('|', $_REQUEST['countries']);
										foreach($tmp AS &$s) {
											echo $sep .'<a href="#">'.$_COUNTRY_ISO_MAP[$s].'</a>';
											$sep = ', ';
										}
										echo "</li>";
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
                            <li class="sortbydetails sortby_det"><a href="javascript:void(0);"><span>Details</span></a></li>
                        </ul> 
                        <div id="contxmenu_det2">
                              <ul>
                                <li<?php if(in_array("all",$details)){?> class="active"<?php }?>><a href="<?php echo get_option('home'); ?>/?s=<?php echo str_replace(" ","+",$_REQUEST['s']); ?>&details[]=all">On</a></li>
                                <li<?php if(in_array("none",$details)){?> class="active"<?php }?>><a href="<?php echo get_option('home'); ?>/?s=<?php echo str_replace(" ","+",$_REQUEST['s']); ?>&details[]=none">Off</a></li>
                                <li class="customlink<?php if(count($details)>0 && count($details)<4 && !in_array("all",$details) && !in_array("none",$details)){?> active<?php }?>" >
                                    <a href="javascript:void(0);">Custom</a>
                                    <form action="<?php echo get_option('home'); ?>/?s=<?php echo str_replace(" ","+",$_REQUEST['s']); ?>" method="post" name="detailForm" id="detailForm">
                                        <label><input name="details[]" <?php if(in_array("all",$details) ||in_array("country",$details) ){?>checked="checked"<?php } ?>value="country" id="detail_1" type="checkbox">Country</label>
                                        <label><input name="details[]" <?php if(in_array("all",$details) || in_array("subject",$details) ){?>checked="checked"<?php } ?>value="subject" id="detail_2" type="checkbox">Subject</label>
                                        <label><input name="details[]" <?php if(in_array("all",$details) || in_array("budget",$details) ){?>checked="checked"<?php } ?>value="budget" id="detail_3" type="checkbox">Budget</label>
                                        <label><input name="details[]" <?php if(in_array("all",$details) || in_array("sector",$details) ){?>checked="checked"<?php } ?>value="sector" id="detail_4" type="checkbox">Sector</label>
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
					<input type="hidden" id="total_results" value="<?php echo $meta->total_count; ?>">
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
<?php get_footer(); ?>
