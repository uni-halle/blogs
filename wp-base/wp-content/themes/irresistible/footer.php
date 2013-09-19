
	</div><!-- / #wrap -->

	<div id="footer">
		
		<div id="footerWrap">
		
			<p id="copy">Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php bloginfo('name'); ?></a>. All rights reserved.</p>
			
			<ul id="footerNav">
			
				<?php wp_list_pages('sort_column=menu_order&title_li=&depth=1'); ?>
				<li><a href="http://www.woothemes.com" title="Irresistible Theme by WooThemes"><img src="<?php bloginfo('template_directory'); ?>/images/img_woothemes.jpg" width="87" height="21" alt="WooThemes" /></a></li>			
			</ul>
		
		</div><!-- / #footerWrap -->
	
	</div><!-- / #footer -->

<?php wp_footer(); ?>
<?php if ( get_option('woo_google_analytics') <> "" ) { echo stripslashes(get_option('woo_google_analytics')); } ?>

</body>
</html>