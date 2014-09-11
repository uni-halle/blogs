	<div id="extended-footer">
	
		<div class="col-full">
	
			<div class="block one">
				
				<?php dynamic_sidebar('footer-1'); ?>
				
			</div><!-- /.block -->
			
			<div class="block two">
			
				<?php dynamic_sidebar('footer-2'); ?>
			
			</div><!-- /.block -->
			
			<div class="block three">
				
				<?php dynamic_sidebar('footer-3'); ?>
			
			</div><!-- /.block -->
			
		</div><!-- /.col-full -->
		
	</div><!-- /#extended-footer -->
	
	<div id="footer">
	
		<div class="col-full">
	
			<div id="copyright" class="col-left">
				<p>&copy; <?php echo date('Y'); ?> <?php bloginfo(); ?>. <?php _e('All Rights Reserved.', 'woothemes') ?></p>
			</div>
			
			<div id="credit" class="col-right">
				<p><?php _e('Powered by', 'woothemes') ?> <a href="http://www.wordpress.org">Wordpress</a>. <?php _e('Designed by', 'woothemes') ?> <a href="http://www.woothemes.com"><img src="<?php bloginfo('template_directory'); ?>/images/woothemes.png" width="87" height="21" alt="Woo Themes" /></a></p>
			</div>
			
		</div><!-- /.col-full -->
		
	</div><!-- /#footer -->
	
</div><!-- /#container -->
<?php wp_footer(); ?>
<?php if ( get_option('woo_google_analytics') <> "" ) { echo stripslashes(get_option('woo_google_analytics')); } ?>
</body>
</html>