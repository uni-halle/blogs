<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "off-canvas-wrap" div and all content after.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */
?>

<div class="footer-container">

	<div class="be-gradient-2-invers spacer-v-4">
		<div class="grid-container">
			<div class="grid-x grid-margin-x">
			</div>
		</div>
	</div>	
	<footer class="footer">
		<?php dynamic_sidebar( 'footer-widgets' ); ?>
		
	</footer>
		<div class="footerspace">
			<div class="footer-upper be-background-1">

				<div class="grid-container">
					<div class="grid-x">
						<div class="small-12 medium-3 large-3 cell be-background-light footer-cutout">
							<h3 class="be-col-1">Be-Up : Geburt aktiv</h3>
							<p class="be-col-1">Effekt der Umgebung auf die Geburt und das Wohlbefinden von Mutter und Kind</p>
						</div>
						<div class="small-12 medium-2 large-2 cell">
						</div>
						<div class="small-12 medium-4 cell contact">
							<h3 class="light">Kontakt</h3>
							<p class="light">Frau Dr. med. rer. medic Gertrud M. Ayerle<br>Frau Elke Mattern M.Sc.<br><a href="mailto:kontakt@be-up-studie.de" style="color:white;">kontakt@be-up-studie.de</a></p>
						</div>
						<div class="small-12 medium-1 cell">
						</div>
						<div class="small-12 medium-2 cell">
							<div class="footer-menu">
								<?php //foundationpress_top_bar_r(); ?>
								<?php echo do_shortcode('[menu name=”footermenu”]'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="footer-lower" style="margin-bottom:2rem;">
				<div class="grid-container">
					<div class="grid-x align-middle">
						<div class="small-12 medium-2 cell" style="margin-bottom:0;">
							<a href="https://www.bmbf.de/" target="_blank" Title="Logo Bundesministerium für Bildung und Forschung">
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/logos/logo_bmbf_o.png" alt="Logo Bundesministerium für Bildung und Forschung">
							</a>
						</div>
					</div>
					<div class="grid-x align-middle">
						<div class="small-12 medium-2 cell" style="margin-top:0;">
							<a href="https://www.bmbf.de/" target="_blank" Title="Logo Bundesministerium für Bildung und Forschung">
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/logos/logo_bmbf_u.png" alt="Logo Bundesministerium für Bildung und Forschung">
							</a>
						</div>
						<div class="small-12 medium-3 cell" style="margin-top:0;">
						</div>
						<div class="small-12 medium-4 cell" style="margin-top:0;">
							<a href="http://www.uni-halle.de/" target="_blank" Title="Martin-Luther-Universität Halle-Wittenberg">
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/logos/logo_mlu.png" alt="Logo Martin-Luther-Universität Halle">
							</a>
						</div>
						<div class="small-12 medium-1 cell" style="margin-top:0;">
						</div>
						<div class="small-12 medium-2 cell" style="margin-top:0;">
							<a href="https://www.hs-gesundheit.de/" target="_blank" Title="Hochschule für Gesundheit">
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/logos/logo_hsg-neu.png" alt="Logo Hochschule für Gesundheit">
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>





</div>

<?php if ( get_theme_mod( 'wpt_mobile_menu_layout' ) === 'offcanvas' ) : ?>
	</div><!-- Close off-canvas content -->
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
