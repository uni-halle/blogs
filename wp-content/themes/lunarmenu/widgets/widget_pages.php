<?php
/*
Plugin Name: Avenue Pages
Description: Sidebar Widget to display Pages
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_pages($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/pages.php');
}

register_sidebar_widget('Avenue Pages', 'widget_avenue_pages');
?>