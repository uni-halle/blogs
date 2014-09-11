<?php
/*
Plugin Name: Avenue Search
Description: Sidebar Widget to display the Search
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_search($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/search.php');
}

register_sidebar_widget('Avenue Search', 'widget_avenue_search');
?>