<?php
/*
Plugin Name: Avenue Feed
Description: Sidebar Widget to display RSS Feeds
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_feed($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/feed.php');
}

register_sidebar_widget('Avenue Feeds', 'widget_avenue_feed');
?>