<?php

// function: post_type BEGIN
function post_type() {
	$labels = array(
		'name' => _x( 'Portfolio', 'post type general name', 'maja'), 
		'singular_name' => _x('Portfolio', 'post type singular name', 'maja'),
		'add_new' => _x('Add Item', 'portfolio', 'maja'), 
		'edit_item' => __('Edit Portfolio Item', 'maja'),
		'new_item' => __('New Portfolio Item', 'maja'), 
		'view_item' => __('View Portfolio', 'maja'),
		'search_items' => __('Search Portfolio', 'maja'), 
		'not_found' =>  __('No Portfolio Items Found', 'maja'),
		'not_found_in_trash' => __('No Portfolio Items Found In Trash', 'maja'),
		'parent_item_colon' => '' 
	);
	
	// Set Up The Arguements
	$args = array(
		'labels' => $labels, 
		'public' => true, 
		'publicly_queryable' => true, 
		'show_ui' => true, 
		'query_var' => true, 
		'rewrite' => true, 
		'capability_type' => 'post', 
		'hierarchical' => false, 
		'menu_position' => null, 
		'exclude_from_search' => true,
		'supports' => array('title','editor','thumbnail'),
		'menu_icon' => MAJA_THEME_DIR . '/images/portfolio.png',	 
	);
	
	// Register The Post Type
	register_post_type('portfolio',$args);
	
	
} // function: post_type END

// function: portfolio_filter BEGIN
function portfolio_filter() {
	// Register the Taxonomy
	register_taxonomy("filter", 
	
	// Assign the taxonomy to be part of the portfolio post type
	array("portfolio"), 
	
	// Apply the settings for the taxonomy
	array(
		"hierarchical" => true, 
		"label" => __( "Filter", 'maja' ), 
		"singular_label" => __( "Filter", 'maja' ), 
		"rewrite" => array( 'slug' => 'filter', 'hierarchical' => true)
		)
	); 
} // function: portfolio_filter END


add_action('init', 'post_type');
add_action( 'init', 'portfolio_filter', 0 );

// add some more appropriate columns to the the "Portfolio Overview" page	
add_filter("manage_edit-portfolio_columns", "portfolio_edit_columns");
add_action("manage_posts_custom_column",  "portfolio_columns_display");
 
function portfolio_edit_columns($portfolio_columns) {
	$portfolio_columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => "Project Title",
		"description" => "Description"
	);
	return $portfolio_columns;
}
 
function portfolio_columns_display($portfolio_columns) {
	switch ($portfolio_columns)
	{
		case "description":
			the_excerpt();
			break;			
	}
}	

?>