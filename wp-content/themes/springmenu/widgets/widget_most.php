<?php
/*
Plugin Name: Avenue Most Commented
Description: Sidebar Widget to display most commented posts
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_most($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/most.php');
}

register_sidebar_widget('Avenue Most Commented', 'widget_avenue_most');
?>