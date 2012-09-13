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
                <a target="_blank" href="https://www.facebook.com/pages/UN-HABITAT/127012777443"><img src="<?php bloginfo('template_url'); ?>/images/facebook.png" alt="Facebook" /></a>
                <a target="_blank" href="http://www.flickr.com/photos/66729176@N02/"><img src="<?php bloginfo('template_url'); ?>/images/flickr.png" alt="Flickr" /></a>
                <a target="_blank" href="https://twitter.com/#!/unhabitat"><img src="<?php bloginfo('template_url'); ?>/images/twitter.png" alt="Twitter" /></a>
                <a target="_blank" href="http://www.youtube.com/user/epitunhabitat"><img src="<?php bloginfo('template_url'); ?>/images/youtube.png" alt="Youtube" /></a>
                <a target="_blank" href="http://www.scribd.com/UN-HABITAT"><img src="<?php bloginfo('template_url'); ?>/images/scribd.png" alt="Scribd" /></a>
            </div>
            <p class="copyright ff4"><?php /*echo date("Y");*/ echo " "; bloginfo('name'); ?><br/>
            Content  licensed under a <a href="http://creativecommons.org/licenses/by/3.0/deed.en_US">Creative Commons Attribution 3.0 Unported License</a></p>
        </div>
</div> 
	<?php wp_footer(); ?>

</body>

</html>
