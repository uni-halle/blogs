<?php global $options;
foreach ($options as $value) {
	if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
}
?>
	<div class="clear"></div>
	<div id="footer">
		<div class="ftext">
		<?php bloginfo('name'); ?> &copy; <?php echo date('Y'); ?> All rights reserved.<br /> <a href="http://www.flisterz.com/wordpress-themes/">Feusional</a> theme by <a href="http://www.flisterz.com">flisterz</a> <span>&amp;</span> <a href="http://www.kreativuse.com">Kreativuse</a>  
		</div>
		
		<div class="searchbox fright">
			<form method="get" id="searchform" class="fleft" action="<?php bloginfo('home'); ?>/">
	 		<input type="text" value="" name="s" id="searchformfield" class="fleft"/>
	 		<button type="submit" id="searchformsubmit" class="fright">Find!</button>
	 		</form>
		</div>
		 
	</div><!-- end of #footer -->
	
	<?php if($feusional_notwitter == "false"){
		echo '<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>';
		echo '<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/'.$feusional_twitter.'.json?callback=twitterCallback2&amp;count=1";?>></script>';
		} ?>
	<?php wp_footer(); ?>
</div><!-- end of #wrapper -->
</body>
</html>