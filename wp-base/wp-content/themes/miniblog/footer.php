			<!--For Theme Options-->
				<?php
					global $options;
					foreach ($options as $value) {
					if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
					}
				?>
			<!--End of For Theme Options-->
			
			</div>
		<div id="footer">
		<!--LEAVE FOOTER SPONSOR LINKS INTACT OR YOUR LICENSE TO USE THIS THEME IS VOID-->
			&copy; <?php the_time('Y');?>  <a href="<?php bloginfo('siteurl');?>"><?php bloginfo('name');?></a>.  <a href="http://www.seodesign.com/themes.php">Theme</a> by <a href="http://www.seodesign.com/">SEO Design</a>.  Optimized by <a href="http://www.seoimage.com/seo_services.html">SEO Image</a>.
		</div>
		
		<!--Beginning of WP Footer-->
			<?php wp_footer(); ?>
		<!--End of WP Footer-->
		<?php if($mb_sidebar_twitter == "true") { ?>
		<!--For Twitter-->
			<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
			<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $mb_twitter_name;?>.json?callback=twitterCallback2&amp;count=<?php echo $mb_twitter_count;?>"></script>
		<!--End of For Twitter-->
		<?php } ?>
		
	</body>
</html>
<!--End of Footer.php-->