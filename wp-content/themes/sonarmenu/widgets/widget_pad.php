<?php
/*
Plugin Name: Avenue Music
Description: Sidebar Widget to display a Support Image
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_music($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/pad.php');
}

register_sidebar_widget('Avenue Music', 'widget_avenue_music');
?>