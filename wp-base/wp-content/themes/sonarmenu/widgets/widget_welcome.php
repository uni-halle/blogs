<?php
/*
Plugin Name: Avenue Welcome
Description: Sidebar Widget to display Bloginfo and Feeds
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_welcome($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/welcome.php');
}

register_sidebar_widget('Avenue Welcome', 'widget_avenue_welcome');
?>