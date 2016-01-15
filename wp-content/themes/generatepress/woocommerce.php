<?php
/**
 * The template is specifically for WooCommerce.
 *
 * This is the template that is used by WooCommerce.
 *
 * @package GeneratePress
 */

get_header(); ?>

	<?php 
	do_action( 'woocommerce_before_main_content' );
		if ( function_exists( 'woocommerce_content' ) ) :
			woocommerce_content(); 
		endif;
	do_action( 'woocommerce_after_main_content' ); 
	?>

<?php 
do_action('woocommerce_sidebar');
get_footer();