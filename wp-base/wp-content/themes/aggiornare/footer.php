<?php
/**
 * @package WordPress
 * @subpackage Aggiornare
 */
 
 $footerTitle = get_option('aggiornare_footer_title');
 $footerText = get_option('aggiornare_footer_text');
?>

</div>
		
	</div>
	
</div>

<div id="footerWrapper">
		
		<div id="footer">
		<div class="footerWidget">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer - Left Column') ) : ?>
			
				<h3>Meta</h3>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<li><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
					<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
					<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
					<?php wp_meta(); ?>
				</ul>

		<?php endif; ?>
		</div>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer - Right Column') ) : ?>
		
		<?php endif; ?>
			
			
			<div id="explanation">
			<?php if($footerTitle) { ?>
				<h3><?php echo $footerTitle; ?></h3>
			<?php } else { ?>
				<h3><?php bloginfo('name'); ?></h3>
			<?php } ?>
			<?php if($footerText) { ?>
				<p><?php echo $footerText; ?></p>
			<?php } else { ?>
				<p><?php bloginfo('description'); ?></p>
			<?php } ?>
			</div>
					
		</div>
		
		<p class="copyright">Copyright &copy; <?php echo date('Y'); ?> by <?php bloginfo('name'); ?>.  All Rights Reserved.  <a href="<?php bloginfo('rss2_url'); ?>">Subscribe to <?php bloginfo('name'); ?>'s feed</a>.<br /><a href="http://geekdesigngirl.com/" title="GeekDesignGirl">Aggiornare Theme by GeekDesignGirl</a></p>
	<!-- It's completely optional, but if you like the theme I would appreciate it if you keep the credit link at the bottom. -->
	
	<?php wp_footer(); ?>

	</div>
	
</body>
</html>
