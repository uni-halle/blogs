<?php
/*
Plugin Name: Modernizr
Plugin URI: http://www.ramoonus.nl/wordpress/modernizr/
Description: Modernizr is a small JavaScript library that detects the availability of native implementations for next-generation web technologies
Version: 2.8.4
Author: Ramoonus
Author URI: http://www.ramoonus.nl/
*/

function rw_modernizr() {

		// @since 2.8.4
		if ( wp_script_is( 'modernizr', 'enqueued' ) ) {
			wp_dequeue_script( 'modernizr' );
			wp_deregister_script( 'modernizr' );
		}

		wp_enqueue_script('modernizr', plugins_url('/js/modernizr-custom.js', __FILE__), array('jquery'), '2.8.4', false);
}
add_action('wp_enqueue_scripts', 'rw_modernizr');