<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package Aadya
 * @subpackage Aadya
 * @since Aadya 1.0.0
 */
?>
</div> <!-- #main-row-->
</div> <!-- .container #main-container-->
</div> <!-- #content-wrap -->

<footer>
		<?php
		/* A sidebar in the footer? Yep. You can customize
		 * your footer with three columns of widgets.
		 */
			get_sidebar( 'footer' );		
			$aadya_copyrighttxt = of_get_option('copyright_text');
			$aadya_copyrighttxt = ! empty($aadya_copyrighttxt) ? $aadya_copyrighttxt : __('&copy; ', 'aadya') . __(date('Y')) . sprintf(' <a href="%1$s" title="%2$s" rel="home">%3$s</a>', esc_url(home_url( '/' )), get_bloginfo( 'name' ), get_bloginfo( 'name' ));
			$d=sprintf('<a href="%1$s" title="%2$s" rel="home">%3$s</a>', esc_url(home_url( '/' )), get_bloginfo( 'name' ), get_bloginfo( 'name' ));			
		?>
<div id="footer">
  <div class="container footer-nav">	
	<div class="pull-left">
	<?php 
		wp_nav_menu( array( 'theme_location' => 'footer-menu', 'menu_class' => 'list-inline', 'depth' =>1, 'container' => false, 'fallback_cb' => false ) ); 
	?>
	</div>	

	<div class="pull-right hidden-xs">
	<p class="text-muted credit"><?php echo $aadya_copyrighttxt;?> <?php echo aadya_get_branding();?> </p>
	</div> 	
	<div class="pull-left visible-xs">
	<p class="text-muted credit"><?php echo $aadya_copyrighttxt;?> <?php echo aadya_get_branding();?> </p>
	</div> 		
  </div>
</div>
</footer> 
</div> <!-- #bodywrap -->
<?php wp_footer(); ?>
</body>
</html>