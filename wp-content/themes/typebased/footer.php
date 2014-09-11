<div id="footer" class="wrap">

	<div class="left-col">
		<div class="popular wrap">
			<h2>Most Popular Articles</h2>
			<ul>
                <!-- Gets the posts with the most comments -->
				<?php include(TEMPLATEPATH . '/includes/popular.php' ); ?>                    
			</ul>
		</div>
	</div>
	
	<div id="subscribe" class="right-col">
		<h2>Subscribe to RSS</h2>
		<p class="rss">If you enjoyed the post, make sure you subscribe to the RSS feed.</p>
		<p>
		<a href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>">Articles</a><br />
		<a href="<?php bloginfo('comments_rss2_url'); ?>">Comments</a>
		</p>
	</div>
	
<div id="copyright" class="wrap">

	<div class="left-col">
		<p>&copy; 2008 TypeBased. All Rights Reserved.</p>
	</div>
	
	<div class="right-col">
		<p>Powered by WordPress. Design by <a href="http://woothemes.com"><img src="<?php bloginfo('template_directory'); ?>/<?php woo_style_path(); ?>/logo-footer.jpg" width="87" height="21" alt="woo themes" /></a></p>
	</div>

</div>

</div>
<?php wp_footer(); ?>
<?php if ( get_option('woo_google_analytics') <> "" ) { echo stripslashes(get_option('woo_google_analytics')); } ?>
</body>
</html>