<?php get_header(); ?>
<a name="top"></a>
<!--START MAINBODY-->
    <div id="opunh-mainbody">
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
				<?php if(is_page(119)) {?>
					<iframe width="800" scrolling="auto" height="600" frameborder="0" allowtransparency="false" src="<?php bloginfo('template_url'); ?>/visualisation.php" id="advanced_iframe"></iframe>
				<?php } ?>
				<?php wp_link_pages(array('before' => 'Pages: ', 'next_or_number' => 'number')); ?>
			</div>
			<?php //edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
		</div>
		<?php endwhile; endif; ?>
        </div>
        <div class="clr"></div>
      </div>
   
	<div class="clr"></div>
  </div>
    <!--END MAINBODY-->
<?php get_footer(); ?>
