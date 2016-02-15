<?php 

if ( ! defined( 'ABSPATH' ) ) exit;

function cp_register_timeline_event() {
	$labels = array(
		'name'               => _x( 'Timeline Event', 'post type general name' ),
		'singular_name'      => _x( 'Timeline Event', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'timeline-event' ),
		'add_new_item'       => __( 'Add New Timeline Event' ),
		'edit_item'          => __( 'Edit Timeline Event' ),
		'new_item'           => __( 'New Timeline Event' ),
		'all_items'          => __( 'All Timeline Events' ),
		'view_item'          => __( 'View Timeline Event' ),
		'search_items'       => __( 'Search Timeline Events' ),
		'not_found'          => __( 'No Timeline Events found' ),
		'not_found_in_trash' => __( 'No Timeline Events found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Timeline Events'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Timeline Event',
		'public'        => true,
		'menu_position' => 4,
		'menu_icon' => 'dashicons-calendar-alt',
		'supports'      => array( 'title', 'editor'),
		'capability_type' => 'post',
		'has_archive'   => true,
		// 'show_in_menu' => 'edit.php?post_type=storymap',
		'taxonomies' 	=> array('event-narrative'),
		'rewrite'       => array( 'slug' => 'timeline-event' ),
	);
	register_post_type( 'timeline-event', $args );	

}

function register_event_narrative_taxonomy() {
    register_taxonomy(
        'event-narrative',
        'event-narrative',
        array(
            'labels' => array(
                'name' => 'Event Narrative',
                'add_new_item' => 'Add New Event Narrative',
                'new_item_name' => "New Event Narrative"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => false
        )
    );
}

add_action( 'init', 'cp_register_timeline_event' );
add_action( 'init', 'register_event_narrative_taxonomy', 0 );

function cp_register_timeline() {
	$labels = array(
		'name'               => _x( 'Timeline', 'post type general name' ),
		'singular_name'      => _x( 'Timeline', 'post type singular name' ),
		'add_new'            => _x( 'Add New Timeline', 'storymap' ),
		'add_new_item'       => __( 'Add New Timeline' ),
		'edit_item'          => __( 'Edit Timeline' ),
		'new_item'           => __( 'New Timeline' ),
		'all_items'          => __( 'All Timelines' ),
		'view_item'          => __( 'View Timeline' ),
		'search_items'       => __( 'Search Timeline' ),
		'not_found'          => __( 'No Timelines found' ),
		'not_found_in_trash' => __( 'No Timelines found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Timelines'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Timeline',
		'public'        => true,
		'menu_position' => 4,
		'menu_icon' => 'dashicons-clock',
		'supports'      => array( 'title', 'editor','thumbnail'),
		'capability_type' => 'post',
		'has_archive'   => false,
		'taxonomies' 	=> array('category'),
		'rewrite'       => array( 'slug' => 'timeline' )
	);
	register_post_type( 'timeline', $args );	

}

add_action( 'init', 'cp_register_timeline' );



?>