<?php
/**
 * The footer template.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
					<?php do_action( 'avada_after_main_content' ); ?>

				</div>  <!-- fusion-row -->
			</main>  <!-- #main -->
			<?php do_action( 'avada_after_main_container' ); ?>

			<?php global $social_icons; ?>

			<?php
			/**
			 * Get the correct page ID.
			 */
			$c_page_id = Avada()->fusion_library->get_page_id();
			?>

			<?php
			/**
			 * Only include the footer.
			 */
			?>
			<?php if ( ! is_page_template( 'blank.php' ) ) : ?>
				<?php $footer_parallax_class = ( 'footer_parallax_effect' === Avada()->settings->get( 'footer_special_effects' ) ) ? ' fusion-footer-parallax' : ''; ?>

				<div class="fusion-footer<?php echo esc_attr( $footer_parallax_class ); ?>">
					<?php get_template_part( 'templates/footer-content' ); ?>
				</div> <!-- fusion-footer -->
			<?php endif; // End is not blank page check. ?>

			<?php
			/**
			 * Add sliding bar.
			 */
			?>
			<?php if ( Avada()->settings->get( 'slidingbar_widgets' ) && ! is_page_template( 'blank.php' ) ) : ?>
				<?php get_template_part( 'sliding_bar' ); ?>
			<?php endif; ?>
		</div> <!-- wrapper -->

		<?php
		/**
		 * Check if boxed side header layout is used; if so close the #boxed-wrapper container.
		 */
		$page_bg_layout = ( $c_page_id ) ? get_post_meta( $c_page_id, 'pyre_page_bg_layout', true ) : 'default';
		?>
		<?php if ( ( ( 'Boxed' === Avada()->settings->get( 'layout' ) && 'default' === $page_bg_layout ) || 'boxed' === $page_bg_layout ) && 'Top' !== Avada()->settings->get( 'header_position' ) ) : ?>
			</div> <!-- #boxed-wrapper -->
		<?php endif; ?>
		<?php if ( ( ( 'Boxed' === Avada()->settings->get( 'layout' ) && 'default' === $page_bg_layout ) || 'boxed' === $page_bg_layout ) && 'framed' === Avada()->settings->get( 'scroll_offset' ) && 0 !== intval( Avada()->settings->get( 'margin_offset', 'top' ) ) ) : ?>
			<div class="fusion-top-frame"></div>
			<div class="fusion-bottom-frame"></div>
			<?php if ( 'None' !== Avada()->settings->get( 'boxed_modal_shadow' ) ) : ?>
				<div class="fusion-boxed-shadow"></div>
			<?php endif; ?>
		<?php endif; ?>
		<a class="fusion-one-page-text-link fusion-page-load-link"></a>

		<?php wp_footer(); ?>

		<?php
		/**
		 * Echo the scripts added to the "before </body>" field in Theme Options.
		 * The 'space_body' setting is not sanitized.
		 * In order to be able to take advantage of this,
		 * a user would have to gain access to the database
		 * in which case this is the least on your worries.
		 */
		echo Avada()->settings->get( 'space_body' ); // WPCS: XSS ok.
		?>
	</body>
</html>
