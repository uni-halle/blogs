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
 * Bootstrap conflict with other plugins
 */ 
function graphene_compat_scripts(){

	/* Dequeue Bootstrap from other plugins */
	global $wp_scripts;
	foreach ( $wp_scripts->queue as $script_handle ) {
		if ( ! in_array( $script_handle, array( 'bootstrap', 'bootstrap-hover-dropdown', 'bootstrap-submenu' ) ) ) {
			if ( stripos( $script_handle, 'bootstrap' ) !== false ) {

				$bootstrap_handles = array( 'bootstrap' );

				/* Dequeue Bootstrap queued by other plugins as well */
				foreach ( $wp_scripts->queue as $other_script_handle ) {
					if ( stripos( $other_script_handle, 'bootstrap' ) !== false &&  $script_handle != $other_script_handle ) {
						$other_script_filename = basename( $wp_scripts->registered[$other_script_handle]->src );
						if ( in_array( $other_script_filename, array( 'bootstrap.js', 'bootstrap.min.js' ) ) ) {
							wp_dequeue_script( $other_script_handle );
							$bootstrap_handles[] = $other_script_handle;
						}
					}
				}

				/* Update the Bootstrap dependency for registered scripts */
				foreach ( $wp_scripts->registered as $registered_handle => $script ) {
					if ( array_intersect( $bootstrap_handles, $script->deps ) ) {
						foreach ( $bootstrap_handles as $bootstrap_handle ) {
							$key = array_search( $bootstrap_handle, $script->deps );
							if ( $key !== false ) $wp_scripts->registered[$registered_handle]->deps[$key] = 'bootstrap';
						}
					}
				}

				break;
			}
		}
	}
}
add_action( 'wp_print_scripts', 'graphene_compat_scripts' );