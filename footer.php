	</div>
    <div id="opunh-footerwrap">
        <div id="opunh-footer">
            <?php wp_nav_menu(array(
					'menu'			  =>'footer-menu',
				    'container'       => '',
					'fallback_cb'	  =>  false,
					'menu_class'      => 'menu footmenu',
					'link_before'     => '<span>',
					'link_after'     => '</span>',
					'theme_location'  => 'footer-menu')
					); 
			?>
            <div class="socialprofiles">
                <a href="#"><img src="<?php bloginfo('template_url'); ?>/images/facebook.png" alt="Facebook" /></a>
                <a href="#"><img src="<?php bloginfo('template_url'); ?>/images/flickr.png" alt="Flickr" /></a>
                <a href="#"><img src="<?php bloginfo('template_url'); ?>/images/twitter.png" alt="Twitter" /></a>
                <a href="#"><img src="<?php bloginfo('template_url'); ?>/images/youtube.png" alt="Youtube" /></a>
                <a href="#"><img src="<?php bloginfo('template_url'); ?>/images/scribd.png" alt="Scribd" /></a>
            </div>
            <p class="copyright ff4">&copy;<?php echo date("Y"); echo " "; bloginfo('name'); ?><br/>All rights reserved</p>
        </div>
</div> 
<a href="<?php echo get_option('home'); ?>/?forum=feedback" title="Give us your feedback"><img src="<?php bloginfo('template_url'); ?>/images/feedback.png" class="feedback"/></a>
	<?php wp_footer(); ?>

</body>

</html>
