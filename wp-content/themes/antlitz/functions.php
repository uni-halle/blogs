<?php
        // Translations can be filed in the /languages/ directory
        load_theme_textdomain( 'html5reset', TEMPLATEPATH . '/languages' );
 
        $locale = get_locale();
        $locale_file = TEMPLATEPATH . "/languages/$locale.php";
        if ( is_readable($locale_file) )
            require_once($locale_file);
	
	// Add RSS links to <head> section
	automatic_feed_links();
	
	// Clean up the <head>
	function removeHeadLinks() {
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');
    

//Enabling Support for Post Thumbnails
add_theme_support( 'post-thumbnails' ); 

?>