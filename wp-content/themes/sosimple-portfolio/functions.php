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