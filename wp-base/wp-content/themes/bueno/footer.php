<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package bueno
 */
?>
			</div>
		</div>
	</div><!-- #main -->

	<div class="container">
		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="footer-inner">
				<div class="site-info">
					<div class="footer-text">
						<?php 
						$footer_text = esc_attr(of_get_option('footer_text'));
						if ('' != $footer_text) {
							echo stripslashes(htmlspecialchars_decode($footer_text));
						} else { ?>
							<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'bueno' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'bueno' ), 'WordPress' ); ?></a>
						<?php } ?>
					</div>
					<?php if ('true' == of_get_option('footer_menu')) {
						wp_nav_menu( array( 
							'container'       => 'ul', 
			                'menu_class'      => 'footer-menu', 
			                'menu_id'         => 'footer-nav',
			                'depth'           => 0,
			                'theme_location' => 'footer' 
						) ); 
					}
					?>
					<div class="clear"></div>
					<div id="toTop"><i class="icon-angle-up"></i></div>
				</div>
			</div>
			<div class="footer_bg_holder"></div>
			<div class="footer_border_holder"></div>
		</footer><!-- #colophon -->
	</div>
</div><!-- .page-wrapper -->

<?php wp_footer(); ?>
</body>
</html>