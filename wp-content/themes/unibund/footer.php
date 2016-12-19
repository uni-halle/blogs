<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

			</div><!-- .site-content -->
		</div><!-- .site-inner -->
	</div><!-- .site -->

	<section class="bottom1">
		<div class="site-inner">
			<div class="site-content">
				<div class="logo"><a href="http://www.uni-halle.de/" target="_blank"><img src="/wp-content/themes/unibund/img/logo_footer1.png" /></a></div>
				<div class="logo"><a href="https://www.uni-jena.de/" target="_blank"><img src="/wp-content/themes/unibund/img/logo_footer2.png" /></a></div>
				<div class="logo"><a href="http://www.zv.uni-leipzig.de/" target="_blank"><img src="/wp-content/themes/unibund/img/logo_footer3.png" /></a></div>
			</div>	
		</div>	
	</section>
	<footer class="bottom2">
		<div class="site-inner">
			<div class="site-content">
				<?php if ( has_nav_menu( 'primary' ) ) : ?>
					<nav class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Primary Menu', 'twentysixteen' ); ?>">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'primary',
								'menu_class'     => 'footer-menu',
							 ) );
						?>
					</nav><!-- .main-navigation -->
				<?php endif; ?>
	
				
				
			</div>	
		</div>
	</footer><!-- .site-footer -->


<?php wp_footer(); ?>
</body>
</html>
