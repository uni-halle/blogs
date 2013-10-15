	<br class="dirtyLittleTrick" />
	
	<?php if ( !is_front_page() ) { 
		include (TEMPLATEPATH . '/footerbar.php'); 
	} ?>

</div><!-- end #wrapper -->

<div id="footer">
	<p><a href="<?php echo get_settings('home'); ?>/" title="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>"><?php bloginfo('name'); ?></a> nutzt das blogs@MLU Home Theme</p>
	<p>design.code.<a href="http://matthiaskretschmann.com" title="Matthias Kretschmann | Design &amp; Photography">matthias.kretschmann</a></p>
	<p><a class="valid" href="http://validator.w3.org/check?uri=referer" title="Valid XHTML 1.0">xhtml 1.0</a></p>
</div>

	<!-- Totally non-semantic, but sexy on the front -->
	<div id="fade-footer"></div>
	
	<script type="text/javascript" src="<?php bloginfo('template_directory') ?>/style/js/effects.js"></script>

<!--
Every plug-in author who throws in their scripts here in the footer gets a cookie from me
-->
<?php wp_footer(); ?>

</body>

<!--
	Design and Theme Development by Matthias Kretschmann | http://matthiaskretschmann.com
-->

</html>
