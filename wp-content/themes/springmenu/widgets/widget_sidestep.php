<?php
/*
Plugin Name: Avenue Sidestep
Description: Sidebar Widget to display Navigation Links in the Sidebar
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_sidestep($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/sidestep.php');
}

register_sidebar_widget('Avenue Sidestep', 'widget_avenue_sidestep');
?>