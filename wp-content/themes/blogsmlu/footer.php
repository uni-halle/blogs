	<br class="dirtyLittleTrick" />
	
	<div id="footerbar" class="clearfix">

	<?php if ( !function_exists('dynamic_sidebar')
	        || !dynamic_sidebar('Footerbar') ) : ?>
		
		<div class="footerlist">
			<h4><?php _e('Pages','blogsmlu'); ?></h4>
			<?php wp_page_menu('show_home=1'); ?>
		</div>
		
		<div class="footerlist">
			<h4><?php _e('Recent Comments','blogsmlu'); ?></h4>
			<?php recent_comments(); ?>
		</div>
		
		<div class="footerlist">
			<h4><?php _e('Tags','blogsmlu'); ?></h4>
			<?php wp_tag_cloud(); ?>
		</div>
	
	<?php endif; ?>
	
	</div><!-- END #footerbar -->

</div><!-- end #wrapper -->

<div id="footer">
	<p><a href="<?php echo get_settings('home'); ?>/" title="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>"><?php bloginfo('name'); ?></a> <?php _e('is using the blogs@URZ Default Theme','blogsmlu'); ?></p>
	<p>design.code.<a href="http://matthiaskretschmann.com" title="Matthias Kretschmann | Design &amp; Photography">matthias.kretschmann</a></p>
	<p><a class="valid" href="http://validator.w3.org/check?uri=referer" title="Valid XHTML 1.0">xhtml 1.0</a></p>
</div>
	
	<!-- Totally non-semantic, but sexy on the front -->
	<div id="fade-footer"></div>
	
	<script type="text/javascript" src="<?php bloginfo('template_directory') ?>/style/js/effects.js"></script>

	<!-- A bit obtrusive (dont do this at home) so we have access to some php and template tags stuff -->
	
	<script type="text/javascript">
	jQuery(function ($) {
		var $searchfield = $('#s');

	  	//init live search
		$searchfield.liveSearch({url: '<?php echo bloginfo('siteurl'); ?>/index.php?ajax=1&s='});
	});

	</script>
	
	<!-- Every plug-in author who throws in their scripts here in the footer gets a cookie from me -->
	
	<?php wp_footer(); ?>

</body>

<!--
	Design and Theme Development by Matthias Kretschmann | http://matthiaskretschmann.com
-->

</html>
