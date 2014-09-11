</div> <!-- End page /-->

<div id="footer-wrap-outer">
<div id="footer-wrap">
	<div id="footer">
	
	<?php if ( get_option("show_rss") ) : ?>
	<div id="subscribe">
	<a href="<?php bloginfo('rss2_url'); ?>"><?php _e('Subscribe RSS', 'elegant-grunge') ?></a>
	</div>
	<?php endif; ?>
	
   <?php get_sidebar('footer'); ?>

	<div class="clear"></div>
	<div class="legal"><?php echo get_option("copyright"); ?></div>
	<div class="credit"><?php printf(__('%1$s Theme by %2$s.', 'elegant-grunge'), '<a href="http://wordpress.org" target="_blank">WordPress</a>', '<a href="http://michael.tyson.id.au/wordpress" target="_blank">Michael Tyson</a>') ?></div>
	<?php wp_footer(); ?>
	</div>
</div>
</div>

</body>
</html>