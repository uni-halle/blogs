<?php
/**
 * Before shop item buttons.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       http://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      5.1.0
 */

global $post;

$product_view = '';

if ( isset( $_SERVER['QUERY_STRING'] ) ) {
	parse_str( sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) ), $params );

	if ( isset( $params['product_view'] ) || Avada()->settings->get( 'woocommerce_product_view' ) ) {
		$product_view = ( isset( $params['product_view'] ) ) ? $params['product_view'] : Avada()->settings->get( 'woocommerce_product_view' );
	}
}

$separator_styles_array = explode( '|', Avada()->settings->get( 'grid_separator_style_type' ) );
$separator_styles = '';

foreach ( $separator_styles_array as $separator_style ) {
	$separator_styles .= ' sep-' . $separator_style;
}
?>

<?php if ( 'list' === $product_view && ! is_product() ) : ?>
	<div class="product-excerpt product-<?php echo esc_attr( $product_view ); ?>">
		<div class="fusion-content-sep<?php echo esc_attr( $separator_styles ); ?>"></div>
		<div class="product-excerpt-container">
			<div class="post-content">
				<?php echo do_shortcode( $post->post_excerpt ); ?>
			</div>
		</div>
		<div class="product-buttons">
			<div class="product-buttons-container clearfix"> </div>
<?php elseif ( 'classic' === Avada()->settings->get( 'woocommerce_product_box_design' ) ) : ?>
	<div class="product-buttons">
		<div class="fusion-content-sep<?php echo esc_attr( $separator_styles ); ?>"></div>
		<div class="product-buttons-container clearfix">
<?php endif; ?>
<?php
/* Omit closing PHP tag to avoid "Headers already sent" issues. */
