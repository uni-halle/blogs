<?php
if (!is_admin()) add_action( 'wp_print_scripts', 'woothemes_add_javascript' );
function woothemes_add_javascript( ) {
	wp_enqueue_script('jquery');    
	//wp_enqueue_script( 'tabs', get_bloginfo('template_directory').'/includes/js/tabs.js', array( 'jquery' ) );
}
?>