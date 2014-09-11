<?php
/*
Plugin Name: Avenue Teaser
Description: Sidebar Widget to display Bloginfo and Feeds
Author: Webfunk
Version: 1.0
Author URI: http://www.web-funk.de
*/

if ( !function_exists('register_sidebar_widget') )
	return;

function widget_avenue_teaser($args) {
	global $wpdb, $post;
	extract($args);
	include(TEMPLATEPATH . '/sidebar/teaser.php');
}

register_sidebar_widget('Avenue Teaser', 'widget_avenue_teaser');
?>