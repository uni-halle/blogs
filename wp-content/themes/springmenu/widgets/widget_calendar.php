<?php
/*
Plugin Name: Avenue Calendar
Description: Sidebar Widget to display the Calendar
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_calendar($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/calendar.php');
}

register_sidebar_widget('Avenue Calendar', 'widget_avenue_calendar');
?>