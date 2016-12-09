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

/*** entfernt überflüssige Beitrags-Optionen  ***/

function remove_stuff () {
  remove_theme_support ('post-formats');
  remove_theme_support ('post-thumbnails');
}
add_action( 'init','remove_stuff' );

function remove_custom_meta(){
  remove_meta_box ('postcustom','post','normal');
}
add_action('admin_menu','remove_custom_meta');


/*** blendet Standard Editor in Beiträgen aus  ***/

add_action( 'init', 'my_custom_init' );
function my_custom_init() {
	remove_post_type_support( 'post', 'editor' );
	remove_post_type_support( 'beispiele', 'editor' );
}

