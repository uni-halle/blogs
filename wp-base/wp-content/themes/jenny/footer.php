<div id="footer">
	<div class="left">
	<?php wp_nav_menu( array( 'menu' => 'Footer Navigation', 'container' => 'div','container_id' => 'footer-navi', 'depth' => '1', 'theme_location' => 'footer-menu',  'fallback_cb' => '' ) ); ?>
	<p>
	<?php if (get_option('p2h_footer_text') != '') { ?>
	<?php echo(stripslashes (get_option('p2h_footer_text')));?>
	<?php } ?>
	</p>
	<p><a href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo ('name');?>"><?php bloginfo ('name');?></a> <?php _e('powered by','jenny'); ?> <a href="http://www.wordpress.org"><?php _e('WordPress','jenny'); ?></a> and <a href="http://www.speckygeek.com/wordpress-themes/" title="Free WordPress theme"><?php _e('Jenny','jenny'); ?></a>.</p>
	</div>
	<div class="right">
	<p class="right top-arrow"><a href="#wrapper">&uarr;</a></p>
	</div>
</div><!--#footer-->

</div><!--#wrapper -->

<?php if (get_option('p2h_analytics_code') != '') { ?>
<?php echo(stripslashes (get_option('p2h_analytics_code')));?>
<?php } ?>
		
<script type="text/javascript"> Cufon.now(); </script>

<!-- Do not remove this, it's required for certain plugins which generally use this hook to reference JavaScript files. -->
<?php wp_footer(); ?>	
</body>
</html>
