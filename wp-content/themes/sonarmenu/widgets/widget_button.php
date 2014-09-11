<?php
/*
Plugin Name: Avenue Blogbutton
Description: Sidebar Widget to display Blogbutton
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_button($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/button.php');
}

register_sidebar_widget('Avenue Blogbutton', 'widget_avenue_button');
?>