<?php
/**
 * @file
 * Creates a custom taxonomy 'person type' which controls where and how 
 * person posts are rendered.
 */


$labels = array(
	'name' => 'Person Type',
	'singular_name' => 'Person Type',
	'search_items'      => __( 'Search Types' ),
	'all_items'         => __( 'All Types' ),
	'parent_item'       => __( 'Parent Types' ),
	'parent_item_colon' => __( 'Parent Type:' ),
	'edit_item'         => __( 'Edit Type' ),
	'update_item'       => __( 'Update Type' ),
	'add_new_item'      => __( 'Add New Type' ),
	'new_item_name'     => __( 'New Type Name' ),
	'menu_name'         => __( 'Person Type' ),
);

$args = array(
	'labels' => $labels,
	'public' => false,
	'show_ui' => true,
	'hierarchical' => true,
	'rewrite' => false,
);

// Run on init and register this taxonomy for the orma-person post type.
add_action('init', register_taxonomy( 'orma-person', 'person', $args ));
register_taxonomy_for_object_type('orma-person', 'person');
