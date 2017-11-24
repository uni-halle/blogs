<?php
if ( ! function_exists( 'the_remaining_content' ) ) :
/**
 * the_remaining_content() added in WP 3.6
 *
 * @package Graphene
 * @since 1.9
 */
function the_remaining_content(){
	the_content();
}
endif;


/**
 * Bootstrap conflict with Booking Calendar plugin
 */ 
function graphene_compat_booking_calendar_scripts(){
	if ( ! function_exists( 'wpbc_plugin_url' ) ) return;

	/* Don't enqueue Bootstrap as we've already included it */
	wp_dequeue_script( 'wpdevelop-bootstrap' );
	wp_dequeue_script( 'wpbc-wpdevelop-bootstrap' );
	wp_enqueue_script( 'wpbc-wpdevelop-bootstrap',  wpbc_plugin_url( '/js/wpbc_bs_no_conflict.js' ), array( 'bootstrap' ) );
}
add_action( 'wp_print_scripts', 'graphene_compat_booking_calendar_scripts' );


function graphene_compat_booking_calendar_styles(){
	if ( ! function_exists( 'wpbc_plugin_url' ) ) return;

	/* Don't enqueue Bootstrap as we've already included it */
	wp_dequeue_style( 'wpdevelop-bts' );
}
add_action( 'wp_print_styles', 'graphene_compat_booking_calendar_styles' );