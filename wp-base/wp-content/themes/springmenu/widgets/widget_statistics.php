<?php
/*
Plugin Name: Avenue Statistics
Description: Sidebar Widget to display Blogstatistics
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_statistics($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/statistics.php');
}

register_sidebar_widget('Avenue Statistics', 'widget_avenue_statistics');
?>