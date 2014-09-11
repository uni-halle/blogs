<?php
/*
Plugin Name: Avenue Meta
Description: Sidebar Widget to display Meta
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_meta($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/meta.php');
}

register_sidebar_widget('Avenue Meta', 'widget_avenue_meta');
?>