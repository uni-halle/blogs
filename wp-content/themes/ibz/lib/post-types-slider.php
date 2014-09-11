<?php
/**
 * CUSTOM POST TYPE FOR FLEX SLIDER
 */

add_action('init', 'flex_slider');  

function flex_slider()  {  
  $labels = array(  
    'name' => _x('Slides', 'post type general name', 'maja'),  
    'singular_name' => _x('Slider', 'post type singular name', 'maja'),  
    'add_new' => _x('Add New Slide', 'Slide', 'maja'),  
    'add_new_item' => __('Add New Slide', 'maja'),  
    'edit_item' => __('Edit slide', 'maja'),  
    'new_item' => __('New slide', 'maja'),  
    'view_item' => __('View slide', 'maja'),  
    'search_items' => __('Search slides', 'maja'),  
    'not_found' =>  __('No slides found', 'maja'),  
    'not_found_in_trash' => __('No slides found in Trash', 'maja'),  
    'parent_item_colon' => ''  
  );  
  
  $args = array(  
    'labels' => $labels,  
    'public' => true,  
    'publicly_queryable' => true,  
    'show_ui' => true,  
    'query_var' => true,  
    'rewrite' => true,  
    'capability_type' => 'post', 
	'menu_icon' => MAJA_THEME_DIR . '/images/slider.png',	 
    'hierarchical' => false, 
	'exclude_from_search' => true,	 
    'menu_position' => 5,  
    'supports' => array('title', 'thumbnail')  
  );  
  register_post_type('flex_slider',$args);  
}  

?>