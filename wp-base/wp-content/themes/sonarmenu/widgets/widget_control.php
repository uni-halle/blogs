<?php
/*
Plugin Name: Avenue Control
Description: Sidebar Widget to display Loginout and Register Button
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_control($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/control.php');
}

register_sidebar_widget('Avenue Control', 'widget_avenue_control');
?>