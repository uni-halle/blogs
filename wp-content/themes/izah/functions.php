<?php
/**
 * Clean Traditional functions and definitions.
 * 
 * @package WordPress
 * @subpackage Clean_Traditional
 * @since CleanTraditional 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 * 
 * @package WordPress
 * @subpackage Clean_Traditional
 * @since CleanTraditional 1.0
 */
if ( ! isset( $theme_version ) ) {
	$theme_version = '1.0';
}


if ( ! function_exists( 'cleantraditional_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 * 
 * @package WordPress
 * @subpackage Clean_Traditional
 * @since CleanTraditional 1.0
 */
function cleantraditional_setup() {
	
}
endif; // cleantraditional_setup
add_action( 'after_setup_theme', 'cleantraditional_setup' );


/**
 * Enqueue scripts and styles.
 * 
 * @package WordPress
 * @subpackage Clean_Traditional
 * @since CleanTraditional 1.0
 */
function cleantraditional_scripts() {
	// Add custom fonts, used in the main stylesheet
	wp_enqueue_style( 'cleantraditional-fonts', cleantraditional_fonts_url(), array(), null );	
	
	// Load main stylesheet
	wp_enqueue_style( 'cleantraditional-style', get_stylesheet_uri() );
	
	// Load js cookie helper
	wp_enqueue_script( 'cleantraditional-js-cookie', get_template_directory_uri() . '/js/vendor/js.cookie.js', array(), '2.0.3', true);	

	// Load own custom js
	wp_enqueue_script( 'cleantraditional-js-grunticons', get_template_directory_uri() . '/fonts/grunticon/grunticon.loader.js', array(), '2.1.2');	
	
	// Load own plugins incl. jQuery
	wp_enqueue_script( 'cleantraditional-js-plugins', get_template_directory_uri() . '/js/plugins.js', array('jquery', 'cleantraditional-js-cookie'), '1.0' , true);

	// Load own custom js
	wp_enqueue_script( 'cleantraditional-js-custom', get_template_directory_uri() . '/js/main.js');
}
add_action( 'wp_enqueue_scripts', 'cleantraditional_scripts' );


if ( ! function_exists( 'cleantraditional_fonts_url' ) ) :
/**
 * Register Google fonts.
 * 
 * @package WordPress
 * @subpackage Clean_Traditional
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
