<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">

	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />	
	<?php if (is_search()) { ?>
	   <meta name="robots" content="noindex, nofollow" /> 
	<?php } ?>

	<title>
		
		   <?php
		      if (function_exists('is_tag') && is_tag()) {
		         single_tag_title("Tag Archive for &quot;"); echo '&quot; - '; }
		      elseif (is_archive()) {
		         wp_title(''); echo ' Archive - '; }
		      elseif (is_search()) {
		         echo 'Search for &quot;'.wp_specialchars($s).'&quot; - '; }
		      elseif (!(is_404()) && (is_single()) || (is_page())) {
		         wp_title(''); echo ' - '; }
		      elseif (is_404()) {
		         echo 'Not Found - '; }
		      if (is_home()) {
		         bloginfo('name'); echo ' - '; bloginfo('description'); }
		      else {
		          bloginfo('name'); }
		      if ($paged>1) {
		         echo ' - page '. $paged; }
		   ?>
	</title>
	
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <!--STYLE FILES-->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
    <link href="<?php bloginfo('template_url'); ?>/css/style.css" rel="stylesheet" type="text/css" />
	<link href="<?php bloginfo('template_url'); ?>/assets/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
	<link href="<?php bloginfo('template_url'); ?>/assets/jeegoocontext/skins/cm_blue/style.css" rel="stylesheet" type="text/css" />
	
	<!--SCRIPT FILES-->
	<script src="<?php bloginfo('template_url'); ?>/js/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_url'); ?>/assets/jeegoocontext/jquery.jeegoocontext.min.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/script.js" type="text/javascript"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/selector.js" type="text/javascript"></script>
	<?php if(is_home()){?>
	<script type="text/javascript" charset="utf-8">
		function initialize() {
			
			
			var baseUrl = top.location.pathname.toString(),
				url = baseUrl + "wp-content/themes/openunh/map_search.php";
		
			
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
			
			
			function initMap(data) {
				var myLatLng = new google.maps.LatLng(-3.2013100765,-9.64460607187);
				var myOptions = {
					zoom : 2,
					center : myLatLng,
					mapTypeId : google.maps.MapTypeId.ROADMAP,
					scrollwheel: false,
					streetViewControl : false
				};

				var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);	
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
						total_activities_url: "?countries="+idx,
						iso2 : idx
					});
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
						"<img src=/media/images/flags/" + this.iso2.toLowerCase() + ".gif />" +
						this.country + 
					"</h2>" +
					"<dl>" +
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
			script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize";
			document.body.appendChild(script);
		});
	</script>
		
	<?php } ?>	
	
		
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

	<?php wp_head(); ?>
	
</head>

<body id="bd" class="ff3">
	
	<div id="opunh-wrapper">

		<!--START HEADER-->
        <div id="opunh-header">
        	<h1 id="logo"><a href="<?php echo get_option('home'); ?>/"><span><?php bloginfo('name'); ?></span></a></h1>
			<ul class="menu mainsite">
                <li><a href="http://www.unhabitat.org/" target="_blank"><span>Go to UN-Habitat main website</span></a></li>
            </ul>
            <ul class="menu lang">
                <li class="active"><a href="#"><span>EN</span></a></li>
                <li><a href="#"><span>FR</span></a></li>
                <li><a href="#"><span>ES</span></a></li>
            </ul>
            <!--UTIL MENU-->
	        <?php wp_nav_menu(array(
					'menu'			  =>'top-menu',
				    'container'       => '',
					'fallback_cb'	  =>  false,
					'menu_class'      => 'menu utilmenu',
					'link_before'     => '<span>',
					'link_after'     => '</span>',
					'theme_location'  => 'top-menu')
					); 
				?>
                <?php if(!is_home()){?>
                     <!--START SEARCH-->
                    	<div class="searchwrap">
                        	<?php get_search_form(); ?>
                    	</div>
                    <!--END SEARCH-->
             	<?php } ?>
            <!--START MAIN MENU-->
                <div class="mainmenu">
                	<?php wp_nav_menu(array(
					'menu'			  =>'main-menu',
				    'container'       => '',
					'fallback_cb'	  =>  false,
					'menu_class'      => 'menu',
					'link_before'     => '<span>',
					'link_after'     => '</span>',
					'theme_location'  => '')
					); 
					?>
                </div>
            <!--END MAIN MENU-->
        </div>
	<!--END HEADER-->

