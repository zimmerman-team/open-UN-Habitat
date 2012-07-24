<!--COL-LEFT-->
  	<div class="colLeft">
    	<div class="leftwrap">     		
        <div class="leftmenu">
        	<h4>Pages</h4>
        	<!--UTIL MENU-->
	        <?php wp_nav_menu(array(
					'menu'			  =>'left-menu',
				    'container'       => '',
					'fallback_cb'	  =>  false,
					'menu_class'      => 'submenu',
					'link_before'     => '<span>',
					'link_after'     => '</span>',
					'theme_location'  => 'left-menu')
					); 
				?>
    	</div>
        <?php 
		
		$var = $_REQUEST['forum'];
		if(!isset($var)){?>
            <!--Visualization-->
            <div class="vstbox">                        
                <?php
                    $id = 57;
                    $p = get_page($id);
                    echo apply_filters('the_content', $p->post_content);
                ?>
            </div>
            <!--FAQ-->
            <div class="faqbox">
                <?php
                    $id = 60;
                    $p = get_page($id);
                    echo '<h4>'. $p->post_title .'</h4>';
                    echo '<div class="faqwrap">'.apply_filters('the_content', $p->post_content).'</div>';
                ?>  
            </div>
        <?php } ?>
			<div class="clr"></div>
      </div>
  </div>
  <!--END COL LEFT-->