<?php
/**
 * @file
 * Creates a custom post type called person.
 */

function create_post_type_person() {
	$labels = array(
		'name' => __( 'Persons' ),
		'singular_name' => __( 'Person' )
	);
	$args = array(
		'labels' => $labels,
		'description' => 'Create entities with info about a person for
		reuse across the site.',
		'public' => true,
		'exclude_from_search' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-admin-users',
		'supports' => array( 'title' ),
		'taxonomies' => array( 'orma_person' ),
	);


	register_post_type( 'person', $args );
}

/*
function add_custom_meta_boxes_person( $post ) {
	add_meta_box(
		''
	)
}*/

add_action( 'init', 'create_post_type_person' );
