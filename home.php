<?php get_header(); ?>
<!--START MAINBODY-->
    <div id="opunh-mainbody">
        <div class="mainsearch">
			<div class="searchbox">
				<?php get_search_form(); ?>
			</div>
			  <p class="posttext">Enter a keyword, country or region to find projects that UN-Habitat is undertaking in various sectors</p>
		</div>
			<!--SEARCH MAP-->
		<div class="searchmapwrap">
			<div class="searchmap">
				<div id="map_canvas" style="width: 960px; height: 340px;"></div>

				
				
			</div>
				<!--text blurb-->
			<div class="textblurb rounded-corners">
				<a href="javascript:void(0);" id="close"><span>Close</span></a>
				<?php
					$id = 71;
					$p = get_page($id);
					echo apply_filters('the_content', $p->post_content);
				?>
			</div>
			<div class="maptabs">
				<ul>
					<?php 
						$countries = $_COUNTRY_ISO_MAP;
						unset($countries['WW']);
						$regions = $_REGION_CHOICES;
					?>
					<li class="country"><a href="<?php echo get_option('home'); ?>?s=&countries=<?php echo implode('|', array_keys($countries));?>" class="active"><span>View by Country</span></a></li>
					<li class="region"><a href="<?php echo get_option('home'); ?>?s=&regions=<?php echo implode('|', array_keys($regions));?>" ><span>View by Regions</span></a></li>
					<li class="global-pr"><a href="<?php echo get_option('home'); ?>/?s=&amp;countries=WW"><span>Worldwide Projects</span></a></li>
				</ul>
			</div>
		</div>
		  <!--END SEARCH MAP-->
			   
		  <!--QUCK FIND-->
		<div class="qf-wrap">
			<div class="qf-header"><span>Quick Find</span></div>
			<div class="qf-links">
				<ul class="menu tabs">
					<li><a class="overlay" href="#country_popup"><span>By Country</span></a></li>
					<li><a class="overlay" href="#region_popup"><span>By Region</span></a></li>
					<li><a class="overlay" href="#budget_popup"><span>By Budget</span></a></li>
					<li><a class="overlay" href="#sector_popup"><span>By Sector</span></a></li>
				 <!--	<li><a href="#"><span>By Tags</span></a></li>-->
				</ul>
				<ul class="menu global">
					<li><a href="<?php echo get_option('home'); ?>/?s=" title="View all Projects">All Projects</a></li>
				</ul>
			</div>
		</div>
        <!--COUNTRIES-->
		<?php echo  wp_generate_filter_popup('country'); ?>    
        <!--END COUNTRIES-->
		<!--REGIONS-->
		<?php echo  wp_generate_filter_popup('region'); ?>    
        <!--END REGIONS-->
		<!--SECTORS-->
		<?php echo  wp_generate_filter_popup('sector'); ?>    
        <!--END SECTORS-->
		<!--SECTORS-->
		<?php echo  wp_generate_filter_popup('budget'); ?>    
        <!--END SECTORS-->
        <!--END QUCK FIND-->
          
          <!--SUMMARY-->
        <div class="sm-wrap sitewidth">
			<!--Country Summary-->
			<div class="col col1">
				<?php
					$id = 51;
					$p = get_page($id);
					echo apply_filters('the_content', $p->post_content);
				?>
			</div>
			<!--Section Summary-->
			<div class="col col2">
				<?php
					$id = 54;
					$p = get_page($id);
					echo apply_filters('the_content', $p->post_content);
				?>
			</div>
			<!--Visualization Summary-->
			<div class="col col3">
				<?php
					$id = 57;
					$p = get_page($id);
					echo apply_filters('the_content', $p->post_content);
				?>
			</div>
			<!--Popular Searchlist-->
			<div class="col col4">
				<h4>Popular Searches</h4>
				<ul class="popsearch">
					<li><a href="?s=Kenya">Kenya</a></li>
					<li><a href="?s=Education">Education</a></li>
					<li><a href="?s=Education">Education</a></li>
					<li><a href="?s=Transparency">Transparency</a></li>
					<li><a href="?s=settlements">Settlements</a></li>
					<li><a href="?s=strategy">Strategy</a></li>
					
				</ul>
			</div>
        </div>
        <!--END SUMMARY-->
        <div class="clr"></div>
</div>
    <!--END MAINBODY-->
<?php get_footer(); ?>