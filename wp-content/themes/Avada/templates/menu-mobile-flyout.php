<?php
/**
 * Mobile flyout menu template.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

$header_content_3 = Avada()->settings->get( 'header_v4_content' );
?>
<div class="fusion-flyout-menu-icons fusion-flyout-mobile-menu-icons">

	<?php if ( class_exists( 'WooCommerce' ) && Avada()->settings->get( 'woocommerce_cart_link_main_nav' ) ) : ?>
		<?php
		global $woocommerce;

		$cart_link_text  = '';
		$cart_link_class = '';
		if ( Avada()->settings->get( 'woocommerce_cart_counter' ) && $woocommerce->cart->get_cart_contents_count() ) {
			$cart_link_text  = '<span class="fusion-widget-cart-number">' . $woocommerce->cart->get_cart_contents_count() . '</span>';
			$cart_link_class = ' fusion-widget-cart-counter';
		}
		?>
		<div class="fusion-flyout-cart-wrapper">
			<a href="<?php echo esc_attr( get_permalink( get_option( 'woocommerce_cart_page_id' ) ) ); ?>" class="fusion-icon fusion-icon-shopping-cart<?php echo esc_attr( $cart_link_class ); ?>" aria-hidden="true" aria-label="<?php esc_attr_e( 'Toggle Shopping Cart', 'Avada' ); ?>"><?php echo $cart_link_text; // WPCS: XSS ok. ?></a>
		</div>
	<?php endif; ?>

	<?php if ( 'menu' === Avada()->settings->get( 'slidingbar_toggle_style' ) && Avada()->settings->get( 'mobile_slidingbar_widgets' ) ) : ?>
		<?php $sliding_bar_label = esc_attr__( 'Toggle Sliding Bar', 'Avada' ); ?>
		<div class="fusion-flyout-sliding-bar-toggle">
			<a href="#" class="fusion-toggle-icon fusion-icon fusion-icon-sliding-bar" aria-label="<?php echo $sliding_bar_label; ?>"></a>
		</div>
	<?php endif; ?>

	<?php if ( ( 'v4' == Avada()->settings->get( 'header_layout' ) || 'Top' != Avada()->settings->get( 'header_position' ) ) && ( 'Tagline And Search' == $header_content_3 || 'Search' == $header_content_3 ) ) : ?>
		<div class="fusion-flyout-search-toggle">
			<div class="fusion-toggle-icon">
				<div class="fusion-toggle-icon-line"></div>
				<div class="fusion-toggle-icon-line"></div>
				<div class="fusion-toggle-icon-line"></div>
			</div>
			<a class="fusion-icon fusion-icon-search" aria-hidden="true" aria-label="<?php esc_attr_e( 'Toggle Search', 'Avada' ); ?>" href="#"></a>
		</div>
	<?php endif; ?>

	<?php // Make sure mobile menu toggle is not loaded when ubermenu is used. ?>
	<?php if ( ! function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) || ( function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) && ! ubermenu_get_menu_instance_by_theme_location( 'main_navigation' ) ) ) : ?>
		<a class="fusion-flyout-menu-toggle" aria-hidden="true" aria-label="<?php esc_attr_e( 'Toggle Menu', 'Avada' ); ?>" href="#">
			<div class="fusion-toggle-icon-line"></div>
			<div class="fusion-toggle-icon-line"></div>
			<div class="fusion-toggle-icon-line"></div>
		</a>
	<?php endif; ?>
</div>

<?php if ( ( 'v4' == Avada()->settings->get( 'header_layout' ) || 'Top' != Avada()->settings->get( 'header_position' ) ) && ( 'Tagline And Search' == $header_content_3 || 'Search' == $header_content_3 ) ) : ?>
	<div class="fusion-flyout-search">
		<?php get_search_form(); ?>
	</div>
<?php endif; ?>

<div class="fusion-flyout-menu-bg"></div>
<?php

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
