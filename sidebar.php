<!--COL-LEFT-->
  	<div class="colLeft">
    	<div class="leftwrap">     		
        <div class="leftmenu">
        	 <h4>Quick Links</h4>  		
        	<!--UTIL MENU-->
            <?php
				if(wp_nav_menu( array( 'theme_location' => 'left-menu', 'fallback_cb' => 'false') )) {
					echo wp_nav_menu( array( 
					'sort_column' => 'menu_order', 
					'container_class' => 'menu-header', 
					'theme_location' => 'left-menu' , 
					'echo' => '0',
					'menu_class'  => 'submenu',
					'link_before'     => '<span>',
					'link_after'     => '</span>' 
					));
				}
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