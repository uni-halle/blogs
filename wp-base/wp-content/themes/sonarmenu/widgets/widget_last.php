<?php
/*
Plugin Name: Avenue Last Comments
Description: Sidebar Widget to display new Comments
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_last($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/last.php');
}

register_sidebar_widget('Avenue Last Comments', 'widget_avenue_last');
?>