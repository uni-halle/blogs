<?php 

/*
Plugin Name: Timelines
Plugin URI: 
Description: TimelineJS is Knight Lab's most widely used product. TimelineJS is an open-source tool that enables anyone to build visually rich, interactive timelines.
Version: 1.05
Author: Ben Parry
Author URI: http://uiux.me
*/

if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! function_exists( 'is_plugin_active' ) )
     require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

function timeline_communicate_dependency_notice() {
	if(is_plugin_active('advanced-custom-fields/acf.php') == false && is_plugin_active('advanced-custom-fields-pro/acf.php') == false) {
    	echo'<div class="error"><p>Timelines requires the plugin Advanced Custom Fields or Advanced Custom Fields Pro to be installed & active.</p></div>'; 
    }
}

add_action( 'admin_notices', 'timeline_communicate_dependency_notice' );

if(is_plugin_active('advanced-custom-fields/acf.php') || is_plugin_active('advanced-custom-fields-pro/acf.php')) {

include 'timeline-posttype.php';
include 'timeline-post-categories.php';

if(is_plugin_active('advanced-custom-fields-pro/acf.php')) {
	include 'timeline-acf-pro-fields.php';
} else { 
	include 'timeline-acf-fields.php';
}


add_shortcode( 'timeline', 'timeline_block' );

function timeline_block($atts) {

	$width = '100%';
	if(isset($atts['width'])) {
		$width = $atts['width'];
	}

	$height = '800px';
	if(isset($atts['height'])) {
		$height = $atts['height'];
	}

	$timeline = get_page_by_path($atts['name'],OBJECT,'timeline');

	if($timeline != false) {

		$events = get_field('tl_timeline_events', $timeline->ID);

		if($events != null) {
			$events = get_timeline_events($events);
		}

		$scale = get_field('tl_timeline_scale', $timeline->ID);
		if($scale == null) {
			$scale = 'human';
		}

		$image = get_field('tl_timeline_media', $timeline->ID);
		$image = ($image != null) ? $image : false;

		$external = get_field('tl_timeline_media_external_content', $timeline->ID);
		$external = ($external != null) ? $external : '';

		$media_url = ($image != false) ? $image : $external;

		$credit = get_field('tl_timeline_media_credit', $timeline->ID);
		$credit = ($credit != null) ? $credit : false;

		$caption = get_field('tl_timeline_media_caption', $timeline->ID);
		$caption = ($caption != null) ? $caption : false;

		$language = get_field('tl_timeline_language', $timeline->ID);
		if($language == null) {
			$language = 'en';
		}

		$timeline_object = array(
			'title'		=>	array(
					'text'		=>	array(
						'headline'	=>	$timeline->post_title,
						'text'		=>	$timeline->post_content
						),
					'media'	=>	array(
							'url'		=>	$media_url,
							'caption'	=>	$caption,
							'credit'	=>	$credit
						)
				),
			'scale'		=>	$scale,
			'events'	=>	$events,
			'language'	=>	$language
			);

		$timeline_object = json_encode($timeline_object);

		$div_id = $timeline->ID;

		echo '<div id="timeline-'.$div_id.'" style="width:'.$width.'; height:'.$height.';"></div>';
		include 'templates/timeline-javascript-template.php';

	} else {

		echo 'Invalid Timeline Name';

	}

}

function get_timeline_events($events) {

	$object = array();
	foreach($events as $event) {

		$meta = get_metadata('post', $event->ID);
		$event->meta = $meta;

		$start_date = get_field('tle_start_date', $event->ID);
		$start_date = ($start_date != null) ? get_timeline_event_date($start_date) : false;

		$start_time = get_field('tle_specify_start_time', $event->ID);
		$start_time = ($start_time != null) ? get_timeline_event_time($start_time, 'start') : false;

		if($start_date != false && $start_time != false) {
			$start_date = array_merge($start_date, $start_time);
		}

		$end_date = get_field('tle_specify_end_date', $event->ID);
		if($end_date != false) {
			$end_date = get_field('tle_end_date', $event->ID);
			$end_date = ($end_date != null) ? get_timeline_event_date($end_date) : false;
		}
		
		if($end_date != false) {
			$end_time = get_field('tle_specify_end_time', $event->ID);
			$end_time = ($end_time != null) ? get_timeline_event_time($end_time, 'end') : false;
		}

		if($end_date != false && $end_time != false) {
			$end_date = array_merge($end_date, $end_time);
		}

		$image = get_field('tle_media_image', $event->ID);
		$image = ($image != null) ? $image : false;

		$external = get_field('tle_media_external_content', $event->ID);
		$external = ($external != null) ? $external : '';

		$media_url = ($image != false) ? $image : $external;

		$credit = get_field('tle_media_credit', $event->ID);
		$credit = ($credit != null) ? $credit : false;

		$caption = get_field('tle_media_caption', $event->ID);
		$caption = ($caption != null) ? $caption : false;

		$thumbnail = get_field('tle_media_thumbnail', $event->ID);
		$thumbnail = ($thumbnail != null) ? $thumbnail : false;

		$display_date = get_field('tle_display_date', $event->ID);
		$display_date = ($display_date != null) ? $display_date : '';

		$group = get_field('tle_group', $event->ID);
		$group = ($group != null) ? $group : false;

		$background_image = get_field('tle_background_image', $event->ID);
		$background_image = ($background_image != null) ? array('url' => $background_image) : false;

		$background_colour = get_field('tle_background_colour', $event->ID);
		$background_colour = ($background_colour != null) ? array('color' => $background_colour) : '';

		$background = ($background_image != false) ? $background_image : $background_colour;

		$object[] = array_filter(array(
				'start_date'	=>	$start_date,
				'end_date'		=>	$end_date,
				'display_date'	=>	$display_date,
				'text'	=>	array(
					'headline'	=>	$event->post_title,
					'text'		=>	$event->post_content,
					),
				'media'	=> array(
					'url'		=>	$media_url,
					'caption'	=>	$caption,
					'credit'	=>	$credit,
					'thumbnail'	=>	$thumbnail,
					),
				'group'			=>	$group,
				'background'	=>	$background
			));

	}

	return $object;

}



function get_timeline_event_date($event_date) {

	$date = array(
		'day'	=>	date('d',strtotime($event_date)),
		'month'	=>	date('m',strtotime($event_date)),
		'year'	=>	date('Y',strtotime($event_date))
		);
	return $date;
}

function get_timeline_event_time($event, $type = 'start') {

	$time = false;

	if(isset($event->meta['tle_specify_'.$type.'_time'])) {

		$hour = get_field('tle_'.$type.'_hour');
		$hour = ($hour != null) ? $hour : '';

		$minute = get_field('tle_'.$type.'_minute');
		$minute = ($minute != null) ? $minute : '';

		$second = get_field('tle_'.$type.'_second');
		$second = ($second != null) ? $second : '';

		$millisecond = get_field('tle_'.$type.'_millisecond');
		$millisecond = ($millisecond != null) ? $millisecond : '';

		$time = array(
			'hour'			=>	$hour,
			'minute'		=>	$minute,
			'second'		=>	$second,
			'millisecond'	=>	$millisecond,
			);

	}

	return array_filter($time);

}

if(is_plugin_active('advanced-custom-fields-pro/acf.php')) {
	// Add options page
	if( function_exists('acf_add_options_page') ) {
		acf_add_options_page('Timeline Settings');
	}
} else {

	add_action('admin_menu', 'timeline_settings_menu');
	add_action( 'admin_init', 'timeline_settings_options' );

	function timeline_settings_options() {
		register_setting( 'timeline-settings-group', 'display_post_categories_as_inline_timeline' );
		register_setting( 'timeline-settings-group', 'display_post_categories_as_a_timeline' );
		register_setting( 'timeline-settings-group', 'only_display_other_timeline_hotswap' );
		register_setting( 'timeline-settings-group', 'display_only_these_post_categories_as_a_timeline' );
		
	}

	function timeline_settings_menu() {
		add_options_page('Timeline Settings', 'Timeline Settings', 'manage_options', 'timeline-settings.php', 'timeline_settings_page');
	}

	function timeline_settings_page() {
		include 'templates/admin/timeline-settings-page.php';
	}


}

//load timeline library and styles
function timeline_external_resources() {
	wp_enqueue_style( 'timeline-stylesheet', 'https://cdn.knightlab.com/libs/timeline3/latest/css/timeline.css' );
	wp_enqueue_script( 'timeline-javascript', 'https://cdn.knightlab.com/libs/timeline3/latest/js/timeline-min.js', array(), '1.0.0', false );
}
add_action( 'wp_enqueue_scripts', 'timeline_external_resources' );

// templates and things
add_filter('single_template', 'timeline_custom_templates');
function timeline_custom_templates($single) {

	global $post;
    if ('timeline' == get_post_type(get_queried_object_id())) {
        $single = dirname(__FILE__) . '/templates/timeline-template.php';
    }
    return $single;
}


}

?>