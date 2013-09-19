<?php
/*
Plugin Name: Avenue Topical
Description: Sidebar Widget to display last Entries
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_topical($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/topical.php');
}

register_sidebar_widget('Avenue Last Entries', 'widget_avenue_topical');
?>