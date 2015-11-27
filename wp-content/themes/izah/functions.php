<?php
/**
 * Clean Traditional functions and definitions.
 * 
 * @package Clean_Traditional
 * @since CleanTraditional 1.0
 */
define( 'THEME_VERSION', '1.0' );


/**
 * Twenty Fifteen only works in WordPress 4.1 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.1-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}


if ( ! function_exists( 'cleantraditional_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 * 
 * @package Clean_Traditional
 * @since CleanTraditional 1.0
 */
function cleantraditional_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain( 'cleantraditional', get_template_directory() . '/languages' );
	
	add_theme_support( 'title-tag' );
	
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );
	
	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 825, 510, true );	
	
	add_theme_support( 'custom-header', array(
			'default-image' => get_template_directory_uri() 
				. '/img/Logo-Expertenplattform-Demographischer-Wandel-Sachsen-Anhalt.png'
	) );
	
	add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );
	
	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
			'main' => __( 'Hauptnavigation', 'cleantraditional' ),
			'footer-1'  => __( 'Footer 1', 'cleantraditional' ),
			'footer-2'  => __( 'Footer 2', 'cleantraditional' ),
			'footer-3'  => __( 'Footer 3', 'cleantraditional' ),
			'footer-4'  => __( 'Footer 4', 'cleantraditional' ),
			'site-bottom' => __( 'Footer unten', 'cleantraditional' ),
	) );	

	add_editor_style( array( 'style.css', 'fonts/genericons/genericons.css', cleantraditional_fonts_url() ) );
}
endif; // cleantraditional_setup
add_action( 'after_setup_theme', 'cleantraditional_setup' );


/**
 * Register widget area.
 *
 * @package Clean_Traditional
 * @since CleanTraditional 1.0
 */
function cleantraditional_widgets_init() {
	register_sidebar( array(
			'name'          => __( 'Haupt-Sidebar', 'cleantraditional' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Zeigt enthaltene Widgets auf alle Seiten.', 'cleantraditional' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'cleantraditional_widgets_init' );


/**
 * Enqueue scripts and styles.
 * 
 * @package Clean_Traditional
 * @since CleanTraditional 1.0
 */
function cleantraditional_scripts() {
	// Add custom fonts, used in the main stylesheet
	wp_enqueue_style( 'cleantraditional-fonts', cleantraditional_fonts_url(), array(), null );	
	
	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/fonts/genericons/genericons.css', array(), '3.2' );	
	
	// Load main stylesheet
	wp_enqueue_style( 'cleantraditional-style', get_stylesheet_uri() );
	
	// Load js cookie helper
	wp_enqueue_script( 'cleantraditional-js-cookie', get_template_directory_uri() . '/js/vendor/js.cookie.js', array(), '2.0.3', true );	

	// Load js cookie helper
	wp_enqueue_script( 'cleantraditional-js-resp-imgmaps', get_template_directory_uri() . '/js/vendor/jquery.rwdImageMaps.min.js', array('jquery'), '1.5', true );
	
	// Load own custom js
	wp_enqueue_script( 'cleantraditional-js-grunticons', get_template_directory_uri() . '/fonts/grunticon/grunticon.loader.js', array(), '2.1.2' );	
	
	// Load own plugins incl. jQuery
	wp_enqueue_script( 'cleantraditional-js-plugins', get_template_directory_uri() . '/js/plugins.js', array('jquery', 'cleantraditional-js-cookie', 'cleantraditional-js-resp-imgmaps'), THEME_VERSION , true );

	// Load own custom js
	wp_enqueue_script( 'cleantraditional-js-custom', get_template_directory_uri() . '/js/main.js', array(), THEME_VERSION );
	
	wp_enqueue_script( 'cleantraditional-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20141010', true );
}
add_action( 'wp_enqueue_scripts', 'cleantraditional_scripts' );


if ( ! function_exists( 'cleantraditional_fonts_url' ) ) :
/**
 * Register Google fonts.
 * 
 * @package Clean_Traditional
 * @since CleanTraditional 1.0
 * @return string Google fonts URL for the theme.
 */
function cleantraditional_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Noto Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Noto Sans font: on or off', 'cleantraditional' ) ) {
		$fonts[] = 'Noto Sans:400italic,700italic,400,700';
	}

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Noto Serif, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Noto Serif font: on or off', 'cleantraditional' ) ) {
		$fonts[] = 'Noto Serif:400italic,700italic,400,700';
	}

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Inconsolata, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'cleantraditional' ) ) {
		$fonts[] = 'Inconsolata:400,700';
	}

	/*
	 * Translators: To add an additional character subset specific to your language,
	 * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language.
	 */
	$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'cleantraditional' );

	if ( 'cyrillic' == $subset ) {
		$subsets .= ',cyrillic,cyrillic-ext';
	} elseif ( 'greek' == $subset ) {
		$subsets .= ',greek,greek-ext';
	} elseif ( 'devanagari' == $subset ) {
		$subsets .= ',devanagari';
	} elseif ( 'vietnamese' == $subset ) {
		$subsets .= ',vietnamese';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;


/**
 * JavaScript Detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @package Clean_Traditional
 * @since CleanTraditional 1.0
 */
function cleantraditional_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'cleantraditional_javascript_detection', 0 );


/**
 * Custom template tags for this theme.
 *
 * @package Clean_Traditional
 * @since CleanTraditional 1.0
 */
require get_template_directory() . '/inc/template-tags.php';
