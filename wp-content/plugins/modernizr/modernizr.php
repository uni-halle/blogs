<?php
/*
Plugin Name: Modernizr
Plugin URI: http://www.ramoonus.nl/wordpress/modernizr/
Description: Modernizr is a small JavaScript library that detects the availability of native implementations for next-generation web technologies
Version: 3.5.0
Author: Ramoonus
Author URI: http://www.ramoonus.nl/
*/

function rw_modernizr() {

		// @since 2.8.4
		if ( wp_script_is( 'modernizr', 'enqueued' ) ) {
			wp_dequeue_script( 'modernizr' );
			wp_deregister_script( 'modernizr' );
		}

        // @version 3.5.0
		wp_enqueue_script('modernizr', plugins_url('/js/modernizr.js', __FILE__), array('jquery'), '3.5.0', false);
}
add_action('wp_enqueue_scripts', 'rw_modernizr');