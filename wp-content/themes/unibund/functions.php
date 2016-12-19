<?php


	/*function add_post_formats() {
		add_theme_support( 'post-formats', array( 'gallery' ) );
	}
	

	add_action( 'after_setup_theme', 'add_post_formats', 20 );*/


	// CATEGORY SINGLE TEMPLATES :: single-{category_slug}.php
	
	
	
	/*
	add_filter( 'single_template',
	    create_function( '$t', 'foreach( (array) get_the_category() as $cat ) {
	    	
	        if ( file_exists(STYLESHEETPATH . "/template-parts/content-{$cat->slug}-single.php") ) return STYLESHEETPATH . "/template-parts/content-{$cat->slug}-single.php";
	    } return $t;' ) );
		*/
	
	/*---------------------------
	 - 
	 - 		REMOVE CODE
	 - 
	 ----------------------------*/
	
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'start_post_rel_link');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'adjacent_posts_rel_link');
		
	
	
	/*---------------------------
	 - 
	 - 		REMOVE POST FORMATS
	 - 
	 ----------------------------*/
	 
	/*add_action( 'init', 'remove_posttype_support', 10 );
	function remove_posttype_support() {
	    remove_post_type_support( 'post', 'post-formats' );
	}*/
	add_action('init','avia_child_theme_setup');
	function avia_child_theme_setup(){
		add_theme_support( 'post-formats', array( 0) );
	}



	/*---------------------------
	-
	- 		SHOW FUTURE POSTS
	-
	----------------------------*/

	function show_future_posts($posts)
	{
		global $wp_query, $wpdb;
		if(is_single() && $wp_query->post_count == 0)
		{
			$posts = $wpdb->get_results($wp_query->request);
		}
		return $posts;
	}
	add_filter('the_posts', 'show_future_posts');



	/*---------------------------
	 - 
	 - 		NAVIGATION
	 - 
	 ----------------------------*/
	
	function register_menu() {
  		register_nav_menu('header-menu',__( 'Header Menu' ));
	}
	add_action( 'init', 'register_menu' );
	
	function unregister_menu() {
    	unregister_nav_menu( 'social' );
	}
	add_action( 'after_setup_theme', 'unregister_menu', 20 );
	
	
	
	
	/*---------------------------
	 - 
	 - 		WIDGETS
	 - 
	 ----------------------------*/
	
	
	function remove_widgets() {
		unregister_sidebar( 'sidebar-1' );
		unregister_sidebar( 'sidebar-2' );
		unregister_sidebar( 'sidebar-3' );
	}
	add_action( 'widgets_init', 'remove_widgets', 11 );
	
	
	function add_header_widgets() {
		register_sidebar(array(
			'name'          => 'Header',
			'id'            => 'header-1',
			'description'   => '',
			'before_widget' => '<div class="header-1">',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
			) );
			
		register_sidebar(array(
			'name'          => 'Header2',
			'id'            => 'header-2',
			'description'   => '',
			'before_widget' => '<div class="header-2">',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
			) );
			
				
		}
	add_action( 'widgets_init', 'add_header_widgets' );



	
	
	/*---------------------------
	 - 
	 - 		CSS + JS
	 - 
	 ----------------------------*/

	// Enqueue Scripts/Styles Fancybox
	function twentytwelve_add_fancybox() {
	    wp_enqueue_script( 'fancybox', '/wp-content/themes/unibund/apps/fancybox/js/jquery.fancybox.js', array( 'jquery' ), false, true );
	    wp_enqueue_style( 'fancybox-style', '/wp-content/themes/unibund/apps/fancybox/css/jquery.fancybox.css' );
	}
	add_action( 'wp_enqueue_scripts', 'twentytwelve_add_fancybox' );

	// Enqueue Scripts/Styles Core
	function twentytwelve_add_core() {
		wp_enqueue_style( 'fonts-rubik', 'https://fonts.googleapis.com/css?family=Rubik:400,500', false );
	    wp_enqueue_style( 'core-css', '/wp-content/themes/unibund/css/style.css' );
		wp_enqueue_style( 'start-css', '/wp-content/themes/unibund/css/start.css' );
		wp_enqueue_style( 'navigation-css', '/wp-content/themes/unibund/css/navigation.css' );
		wp_enqueue_script( 'core-js', '/wp-content/themes/unibund/js/script.js', array( 'jquery' ), false, true );
	}
	add_action( 'wp_enqueue_scripts', 'twentytwelve_add_core' );
	
	
	// Dequeue
	function wp_dequeue_google_fonts() {
    	wp_dequeue_style( 'twentysixteen-fonts' );
	}
	add_action( 'wp_enqueue_scripts', 'wp_dequeue_google_fonts', 20 );
	
	
	/*---------------------------
	 - 
	 - 		Lightbox
	 - 
	 ----------------------------*/
	 
	function rc_add_rel_attribute($link) {
		global $post;
		return str_replace('<a href', '<a rel="group" href', $link);
	}
	add_filter('wp_get_attachment_link', 'rc_add_rel_attribute');
	
	
	/*---------------------------
	 - 
	 - 		RTE
	 - 
	 ----------------------------*/
	function wpdocs_theme_add_editor_styles() {
    	add_editor_style( 'custom-editor-style.css' );
	}
	add_action( 'admin_init', 'wpdocs_theme_add_editor_styles' );
	
	
	
	function my_mce_buttons_1($buttons) {   

    $buttons[] = 'superscript';
    $buttons[] = 'subscript';
    $buttons[] = 'tablecontrols';

    return $buttons;
}
add_filter('mce_buttons_3', 'my_mce_buttons_3'); 
	
	
	
?>