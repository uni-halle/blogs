<?php
/*
Plugin Name: Avenue Categories
Description: Sidebar Widget to display Categories
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_categories($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/categories.php');
}

register_sidebar_widget('Avenue Categories', 'widget_avenue_categories');
?>