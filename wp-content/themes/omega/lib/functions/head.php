<?php
/**
 * Functions for outputting common site data in the `<head>` area of a site.
 */

/* Adds common theme items to <head>. */
add_action( 'wp_head', 'omega_meta_viewport', 1 );
add_action( 'wp_head', 'omega_link_pingback', 3 );

/**
 * Adds the meta viewport to the header.
 *
 * @since  0.9.0
 * @access public
 */
function omega_meta_viewport() {
	echo '<meta name="viewport" content="width=device-width" />' . "\n";
}

/**
 * Adds the pingback link to the header.
 *
 * @since  0.9.0
 * @access public
 * @return void
 */
function omega_link_pingback() {
	if ( 'open' === get_option( 'default_ping_status' ) )
		echo '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '" />' . "\n";
}