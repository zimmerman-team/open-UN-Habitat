<?php
/*
Template Name: Sitemap
*/
?>
<?php get_header(); ?>

<!--START BODY-->
<div id="opunh-mainbody">
          <!--TITLE PAGE-->
            <div class="fpgagetitle">
                <h2><span>Sitemap</span></h2>
            </div>
          <!--END TITLE PAGE-->
			<!--SITEMAP-->
        <div class="sitemap">
		<ul id="utilityNav">
			<li><a href="http://www.unhabitat.org" target="_blank">UN-Habitat Site</a></li>
			<li><a href="<?php echo get_option('home'); ?>/?page_id=25">Sitemap</a></li>
			<li><a href="<?php echo get_option('home'); ?>/?page_id=23">FAQ's</a></li>
			<li><a href="<?php echo get_option('home'); ?>/?page_id=32">Contacts</a></li>
		</ul>

		<ul id="primaryNav" class="col4">
			<li id="home"><a href="<?php echo get_option('home'); ?>">Home</a></li>
			<li><a href="<?php echo get_option('home'); ?>/?s=">Projects</a>
				<ul>
					<li><a href="<?php echo get_option('home'); ?>/?s=" title="Search">Search</a></li>
					<li><a href="<?php echo get_option('home'); ?>/?page_id=51&amp;type=cn">Country Summary</a></li>
					<li><a href="<?php echo get_option('home'); ?>/?page_id=54&amp;type=sc">Section Summary</a></li>
					<li><a href="#">Visualization</a></li>
				</ul>
			</li>
			<li><a href="<?php echo get_option('home'); ?>/?page_id=23">Faqs</a></li>	
            <li><a href="<?php echo get_option('home'); ?>/?page_id=21">About Open UN-Habitat</a></li>	
            <li><a href="<?php echo get_option('home'); ?>/?page_id=32">Contacts</a></li>	
		</ul>

	</div>
            <!--END SITEMAP-->		
        <div class="clr"></div>
  	</div>
<!--END BODY-->
<?php get_footer(); ?>