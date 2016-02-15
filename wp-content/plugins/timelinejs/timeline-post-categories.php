<?php 



function get_timeline_event_time_object($date) {
	$date = new DateTime($date);

	$datetime = array(
			'day'		=>	$date->format('d'),
			'month'		=>	$date->format('m'),
			'year'		=>	$date->format('Y'),
			'hour'		=>	$date->format('H'),
			'minute'	=>	$date->format('i'),
			'second'	=>	$date->format('s')
		);

	
	return $datetime;
}


function get_posts_as_timeline_events($category=false) {


	// var_dump($category);exit;

	//get posts
	$args = array(
		'post_type'			=>	'post',
		'posts_per_page'	=>	-1
		);

	$group = '';
	if($category != false) {
		$args['cat'] = $category->term_id;
		$group = $category->name;
	}

	$posts = new WP_Query($args);

	$events = array();
	foreach($posts->posts as $post) {
		$events[] = create_timeline_event_from_post($post, $group);
	}

	return $events;

}


function create_timeline_event_from_post($post, $group) {

	$meta = get_metadata('post', $post->ID, '', true);
	$post->meta = $meta;

	$start_date = get_field('tle_start_date', $post->ID);
	$start_date = ($start_date != null) ? get_timeline_event_date($start_date) : false;

	$start_time = get_field('tle_specify_start_time', $post->ID);
	$start_time = ($start_time != null) ? get_timeline_event_time($start_time, 'start') : false;

	if($start_date != false && $start_time != false) {
		$start_date = array_merge($start_date, $start_time);
	}

	$end_date = get_field('tle_specify_end_date', $post->ID);
	$end_date = ($end_date != null) ? get_timeline_event_date($end_date) : false;
	
	if($end_date != false) {
		$end_time = get_field('tle_specify_end_time', $post->ID);
		$end_time = ($end_time != null) ? get_timeline_event_time($end_time, 'end') : false;
	}

	if($end_date != false && $end_time != false) {
		$end_date = array_merge($end_date, $end_time);
	}

	$image = get_field('tle_media_image', $post->ID);
	$image = ($image != null) ? $image : false;

	$external = get_field('tle_media_external_content', $post->ID);
	$external = ($external != null) ? $external : '';

	$media_url = ($image != false) ? $image : $external;

	$credit = get_field('tle_media_credit', $post->ID);
	$credit = ($credit != null) ? $credit : false;

	$caption = get_field('tle_media_caption', $post->ID);
	$caption = ($caption != null) ? $caption : false;

	$thumbnail = get_field('tle_media_thumbnail', $post->ID);
	$thumbnail = ($thumbnail != null) ? $thumbnail : false;

	$display_date = get_field('tle_display_date', $post->ID);
	$display_date = ($display_date != null) ? $display_date : '';

	$background_image = get_field('tle_background_image', $post->ID);
	$background_image = ($background_image != null) ? array('url' => $background_image) : false;

	$background_colour = get_field('tle_background_colour', $post->ID);
	$background_colour = ($background_colour != null) ? array('color' => $background_colour) : '';

	$background = ($background_image != false) ? $background_image : $background_colour;

	$object = array_filter(array(
		'start_date'	=>	get_timeline_event_time_object($post->post_date),
		// 'end_date'		=>	
		'display_date'	=>	$display_date,
		'text'	=>	array(
			'headline'	=>	'<a href="'.site_url().'/'.$post->post_name.'">'.$post->post_title.'</a>',
			'text'		=>	$post->post_content,
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

	return $object;

}


add_shortcode('timeline_post_category', 'render_timeline_block');

function render_timeline_block($atts) {

	$category = '';
	if(isset($atts['name'])) {
		$category = $atts['name'];

		$category = get_category_by_slug($category);


		$width = '100%';
		if(isset($atts['width'])) {
			$width = $atts['width'];
		}

		$height = '800px';
		if(isset($atts['height'])) {
			$height = $atts['height'];
		}

		$div_id = 'timeline_x';
		if(isset($atts['id'])) {
			$div_id = $atts['id'];
		}

		$events = get_posts_as_timeline_events($category);

		$image = get_field('tlpc_media_image', 'category_'.$category->term_id);
		$image = ($image != null) ? $image : false;

		$external = get_field('tlpc_external_media_content', 'category_'.$category->term_id);
		$external = ($external != null) ? $external : '';

		$media_url = ($image != false) ? $image : $external;

		$credit = get_field('tlpc_media_credit', 'category_'.$category->term_id);
		$credit = ($credit != null) ? $credit : false;

		$caption = get_field('tlpc_media_caption', 'category_'.$category->term_id);
		$caption = ($caption != null) ? $caption : false;

		$timeline_object = array(
			'title'		=>	array(
					'text'		=>	array(
						'headline'	=>	$category->cat_name,
						'text'		=>	$category->description
						),
					'media'	=>	array(
							'url'		=>	$media_url,
							'caption'	=>	$caption,
							'credit'	=>	$credit
						)
				),
			'scale'		=>	'human',
			'events'	=>	$events
			);

		$additional_options = array(
			'start_at_end'	=> true
			);

		$timeline_object = json_encode($timeline_object);
		$additional_options = json_encode($additional_options);

		echo '<div id="timeline-'.$div_id.'" style="width:'.$width.'; height:'.$height.';"></div>';
		include 'templates/timeline-javascript-template.php';

	} else {

		echo 'Invalid Category Name';
		
	}

}

get_field('display_post_categories_as_a_timeline', 'option');


if(is_plugin_active('advanced-custom-fields-pro/acf.php')) {
	add_filter('category_template', 'timeline_posts_templates');
} else {
	add_filter('category_template', 'timeline_posts_noafcpro_templates');
}


function timeline_posts_templates($template) {

	global $post;
	
	$post_categories_as_timeline = get_field('display_post_categories_as_a_timeline', 'option');
	$inline_template = get_field('display_post_categories_as_inline_timeline', 'option');
	$specific_categories = get_field('display_only_these_post_categories_as_a_timeline', 'option');

	if($inline_template) {
		$timeline_template = dirname(__FILE__) . '/templates/post-category-inline-template.php';
	} else {
		$timeline_template = dirname(__FILE__) . '/templates/post-category-template.php';
	}

	if($post_categories_as_timeline == 'all') {
		$template = $timeline_template;
	} else if ($post_categories_as_timeline == 'specific' && in_array(get_queried_object_id(), $specific_categories)) {
		$template = $timeline_template;
	}

	return $template;

}

function timeline_posts_noafcpro_templates($template) {

	global $post;
	
	$post_categories_as_timeline = get_option('display_post_categories_as_a_timeline');
	$inline_template = get_option('display_post_categories_as_inline_timeline');
	$specific_categories = get_option('display_only_these_post_categories_as_a_timeline');


	if($inline_template) {
		$timeline_template = dirname(__FILE__) . '/templates/post-category-inline-template.php';
	} else {
		$timeline_template = dirname(__FILE__) . '/templates/post-category-template.php';
	}

	if($post_categories_as_timeline == 'all') {
		$template = $timeline_template;
	} else if ($post_categories_as_timeline == 'specific' && in_array(get_queried_object_id(), $specific_categories)) {
		$template = $timeline_template;
	}

	return $template;

}


?>