<?php
/*
Plugin Name: Avenue Archive
Description: Sidebar Widget to display the Archive
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_history($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/history.php');
}

register_sidebar_widget('Avenue Archive', 'widget_avenue_history');
?>