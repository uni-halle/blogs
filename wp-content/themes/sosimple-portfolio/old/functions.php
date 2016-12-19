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



