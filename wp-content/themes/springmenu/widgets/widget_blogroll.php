<?php
/*
Plugin Name: Avenue Blogroll
Description: Sidebar Widget to display the Blogroll
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_blogroll($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/blogroll.php');
}

register_sidebar_widget('Avenue Blogroll', 'widget_avenue_blogroll');
?>