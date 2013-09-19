<!-- footer template -->
<div id="appendix" class="clearfix">
<div class="app_widget">
<!-- sidebar_bottom_left -->
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar_bottom_left') ) : ?>
<div class="widget">
<h4><a href="#" title="Toggle" class="hide_widget">Find it!</a></h4>
<?php include (TEMPLATEPATH . '/searchform.php'); ?>
</div>
<?php endif; // endif widget ?>
<p class="wp-bookmark"><a class="wp-logo" href="http://wordpress.org"><img src="<?php bloginfo('template_directory'); ?>/img/wordpress-logo.png" alt="WordPress"/></a></p>
<div id="credits">Theme Design by <a href="http://devolux.nh2.me"><strong>devolux.nh2.me</strong></a></div>
</div>
<div class="app_widget">
<!-- sidebar_bottom_middle -->
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar_bottom_middle') ) : ?>
<div id="tag_cloud" class="widget">
<h4><a href="#" title="Toggle" class="hide_widget">Tag Cloud</a></h4>
<div><?php wp_tag_cloud(); ?></div>
</div>
<?php endif; // endif widget ?>
</div>
<div class="app_widget">
<!-- sidebar_bottom_right -->
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar_bottom_right') ) : ?>
<div  id="archives" class="widget">
<h4><a href="#" title="Toggle" class="hide_widget">Archives</a></h4>
<ul><?php wp_get_archives('type=monthly&limit=12'); ?></ul>
</div>
<?php endif; // endif widget ?><a id="totop" href="#">To top</a>
</div>
</div>
</div>
<!-- wp_footer -->
<?php wp_footer(); ?>
</body>
</html>
