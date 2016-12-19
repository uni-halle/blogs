<?php
/**
 * Theme Name child theme functions and definitions
	 */
	/*—————————————————————————————————————————*/
	/* Include the parent theme style.css
	/*—————————————————————————————————————————*/
	 
	add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
	function theme_enqueue_styles() {
	    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}


/***post type Beispiel*****/


add_action( 'init', 'create_post_type' );
function create_post_type() {
  register_post_type( 'beispiele',
    array(
      'labels' => array(
        'name' => __( 'Beispiele' ),
        'singular_name' => __( 'Beispiel' )
      ),
      'public' => true,
      'has_archive' => false,
      'supports' => array('title','editor','post-thumbnails')
    )
  );
}