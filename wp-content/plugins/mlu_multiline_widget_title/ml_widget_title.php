<?php

/*
Plugin Name: Mehrzeiliger Widget-Titel
Description: Zeilenumbrüche in Widget-Titel mittels [br]
Author:      Robert Jäckel
Author URI:  mailto:robert.jaeckel@itz.uni-halle.de?subject=WP-Plugin ML-Widget-Title
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );



add_filter('widget_title','mlu_widget_title',10,3);
function mlu_widget_title($title) {
	$title = str_replace("[br]", "<br/>", $title);
	return $title;
}

