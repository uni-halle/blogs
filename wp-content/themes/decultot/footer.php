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

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="full-width section">
			<div class="center-content">

				<div id="footer_icons">

					<figure>

						<a href="http://www.uni-halle.de/" target="_blank">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/UniHalle_Siegel_links_schwarz_mit_Text-01.png"
							     alt="Martin Luther Universität Halle-Wittenberg">
						</a>
					</figure>
					<figure>
						<a href="http://www.germanistik.uni-halle.de/" target="_blank">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/Germanistisches-Institut.png" alt="Germanistisches Institut">
						</a>
					</figure>
					<figure>
						<a href="http://www.izea.uni-halle.de" target="_blank">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/IZEA-Logo-Untertitel-Web.png" alt="IZEA">
						</a>
					</figure>
					<figure>
						<a href="http://www.humboldt-foundation.de/" target="_blank">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/AvH_Logo_rgb.png"
							     alt="Alexander von Humboldt Stiftung">
						</a>
					</figure>

				</div>

			</div>	<!-- .center-content -->
		</div>

		<div class="center-content">

			<div class="col col-14">
				<nav class="footer-navigation" role="navigation" aria-label="Footer-Hauptmenü">
					<?php
						wp_nav_menu( array(
							'theme_location' => 'footer-menu',
							'menu'          => 'Footer Menu',
							'menu_class'    => 'footer-nav'
						) );
					?>
				</nav>
			</div>

			<div class="col col-34">
				<div class="col italic">
					Alexander-von-Humboldt-Professur für neuzeitliche Schriftkultur und europäischen Wissenstransfer
				</div>
				<div class="col col-12">
					<p>Prof. Dr. Elisabeth Décultot</p>
					<p>Martin-Luther-Universität Halle-Wittenberg</p>
					<p>Philosophische Fakultät II</p>
					<p>Germanistisches Institut</p>
					<p>06099 Halle (Saale)</p>
				</div>
				<div class="col col-12">
					<p>Telefon: 0345 55-23594 (Sekretariat: Yvonne Reinhardt)</p>
					<p>Telefax: 0345 55-27067</p>
					<p><a href="mailto:yvonne.reinhardt@germanistik.uni-halle.de">yvonne.reinhardt@germanistik.uni-halle.de</a></p>
				</div>

			</div>

		</div>	<!-- .center-content -->


		<?php if ( has_nav_menu( 'social' ) ) : ?>
			<div class="center-content">
				<nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Social Links Menu', 'twentysixteen' ); ?>">
					<?php
						wp_nav_menu( array(
							'theme_location' => 'social',
							'menu_class'     => 'social-links-menu',
							'depth'          => 1,
							'link_before'    => '<span class="screen-reader-text">',
							'link_after'     => '</span>',
						) );
					?>
				</nav><!-- .social-navigation -->
			</div>	<!-- .center-content -->
		<?php endif; ?>
	</footer><!-- .site-footer -->
</div><!-- .site -->

<?php wp_footer(); ?>
</body>
</html>