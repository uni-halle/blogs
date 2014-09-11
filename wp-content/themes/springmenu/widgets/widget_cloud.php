<?php
/*
Plugin Name: Avenue Tag Cloud
Description: Sidebar Widget to display the Tag Cloud
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_cloud($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/cloud.php');
}

register_sidebar_widget('Avenue Tag Cloud', 'widget_avenue_cloud');
?>