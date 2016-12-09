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
	    wp_enqueue_script( 'core-js', '/wp-content/themes/unibund/js/script.js', array( 'jquery' ), false, true );
	    wp_enqueue_style( 'core-css', '/wp-content/themes/unibund/css/style.css' );
	}
	add_action( 'wp_enqueue_scripts', 'twentytwelve_add_core' );




	function rc_add_rel_attribute($link) {
		global $post;
		return str_replace('<a href', '<a rel="group" href', $link);
	}
	add_filter('wp_get_attachment_link', 'rc_add_rel_attribute');



	
	
	
	
	
	
	
	
?>