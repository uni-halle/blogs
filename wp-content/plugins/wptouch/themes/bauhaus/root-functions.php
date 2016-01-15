<?php

define( 'BAUHAUS_THEME_VERSION', '1.6.2' );
define( 'BAUHAUS_SETTING_DOMAIN', 'bauhaus' );
define( 'BAUHAUS_DIR', wptouch_get_bloginfo( 'theme_root_directory' ) );
define( 'BAUHAUS_URL', wptouch_get_bloginfo( 'theme_root_url' ) );

// Bauhaus actions
add_action( 'foundation_init', 'bauhaus_theme_init' );
add_action( 'foundation_modules_loaded', 'bauhaus_register_fonts' );
add_action( 'customize_controls_enqueue_scripts', 'bauhaus_enqueue_customizer_script' );

// Bauhaus filters
add_filter( 'wptouch_registered_setting_domains', 'bauhaus_setting_domain' );
add_filter( 'wptouch_setting_defaults_bauhaus', 'bauhaus_setting_defaults' );
add_filter( 'wptouch_setting_defaults_foundation', 'bauhaus_foundation_setting_defaults' );

add_filter( 'wptouch_body_classes', 'bauhaus_body_classes' );
add_filter( 'wptouch_post_classes', 'bauhaus_post_classes' );

// Bauhaus GUI Settings
add_filter( 'foundation_settings_header', 'bauhaus_header_settings' );
add_filter( 'foundation_settings_blog', 'bauhaus_blog_settings' );
add_filter( 'wptouch_post_footer', 'bauhaus_footer_version' );

add_filter( 'wptouch_has_post_thumbnail', 'bauhaus_handle_has_thumbnail' );
add_filter( 'wptouch_the_post_thumbnail', 'bauhaus_handle_the_thumbnail' );
add_filter( 'wptouch_get_post_thumbnail', 'bauhaus_handle_get_thumbnail' );
add_filter( 'wptouch_setting_version_compare', 'bauhaus_setting_version_compare', 10, 2 );

function bauhaus_setting_domain( $domain ) {
	$domain[] = BAUHAUS_SETTING_DOMAIN;
	return $domain;
}

function bauhaus_get_settings() {
	return wptouch_get_settings( BAUHAUS_SETTING_DOMAIN );
}

function bauhaus_setting_version_compare( $version, $domain ) {
	if ( $domain == BAUHAUS_SETTING_DOMAIN ) {
		return BAUHAUS_THEME_VERSION;
	}

	return $version;
}

function bauhaus_footer_version(){
	echo '<!--Bauhaus v' . BAUHAUS_THEME_VERSION . '-->';
}

function bauhaus_setting_defaults( $settings ) {

	// Bauhaus menu default
	$settings->bauhaus_menu_style = 'off-canvas';


	// Theme colors
	$settings->bauhaus_background_color = '#f9f9f8';
	$settings->bauhaus_header_color = '#2d353f';
	$settings->bauhaus_link_color = '#0376a8';
	$settings->bauhaus_post_page_header_color = '#4ad6a7';

	// Blog
	$settings->bauhaus_show_taxonomy = false;
	$settings->bauhaus_show_date = true;
	$settings->bauhaus_show_author = false;
	$settings->bauhaus_show_search = true;
	$settings->bauhaus_show_comment_bubbles = true;
	$settings->bauhaus_use_infinite_scroll = false;

	$settings->bauhaus_use_thumbnails = 'index_single_page';
	$settings->bauhaus_thumbnail_type = 'featured';
	$settings->bauhaus_thumbnail_custom_field = '';

	return $settings;
}

function bauhaus_foundation_setting_defaults( $settings ) {
	$settings->typography_sets = 'lato_roboto';
	return $settings;
}

function bauhaus_theme_init() {
	// Foundation modules this theme should load
	foundation_add_theme_support(
		array(
			// Modules w/ settings
			'wptouch-icons',
			'custom-posts',
			'custom-latest-posts',
			'google-fonts',
			'load-more',
			'media',
			'sharing',
			'social-links',
			'featured',
			// Modules w/o settings
			'menu',
			'spinjs',
			'tappable',
			'fastclick',
			'concat'
		)
	);

	// If enabled in Bauhaus settings, load up infinite scrolling
	bauhaus_if_infinite_scroll_enabled();

	// If enabled in Bauhaus settings, load up PushIt off-canvas menu (default)
	bauhaus_if_off_canvas_enabled();

	// Example of how to register a theme menu
	wptouch_register_theme_menu(
		array(
			'name' => 'primary_menu',									// this is the name of the setting
			'friendly_name' => __( 'Header Menu', 'wptouch-pro' ),		// the friendly name, shows as a section heading
			'settings_domain' => BAUHAUS_SETTING_DOMAIN,				// the setting domain (should be the same for the whole theme)
			'description' => __( 'Choose a menu', 'wptouch-pro' ),		// the description
			'tooltip' => __( 'Main menu selection', 'wptouch-pro' ),	// Extra help info about this menu, perhaps?
			'can_be_disabled' => false									// Typically this is always false
		)
	);

	// Example of how to register theme colors
	// (Name, element to add color to, element to add background-color to, settings domain, luma threshold, luma class root – light-*, dark-* )
	foundation_register_theme_color( 'bauhaus_background_color', __( 'Theme background', 'wptouch-pro' ), '', '.page-wrapper', BAUHAUS_SETTING_DOMAIN, WPTOUCH_PRO_LIVE_PREVIEW_SETTING, 150, 'body' );
	foundation_register_theme_color( 'bauhaus_header_color', __( 'Header & Menu', 'wptouch-pro' ),'', 'body, header, .wptouch-menu, .pushit, #search-dropper, .date-circle', BAUHAUS_SETTING_DOMAIN, WPTOUCH_PRO_LIVE_PREVIEW_SETTING, 150, 'header' );
	foundation_register_theme_color( 'bauhaus_link_color', __( 'Links', 'wptouch-pro' ), '.content-wrap a, #slider a p:after', '.dots li.active, #switch .active', BAUHAUS_SETTING_DOMAIN, WPTOUCH_PRO_LIVE_PREVIEW_SETTING );
	foundation_register_theme_color( 'bauhaus_post_page_header_color', __( 'Post/Page Headers', 'wptouch-pro' ), '', '.bauhaus, form#commentform button#submit', BAUHAUS_SETTING_DOMAIN, WPTOUCH_PRO_LIVE_PREVIEW_SETTING, 150, 'post-head' );
}

// Example of how to register Google font pairings
// (Apply to (Headings or Body), Google font Pretty Name, kerning, weights)
function bauhaus_register_fonts() {
	if ( foundation_is_theme_using_module( 'google-fonts' ) ) {
		foundation_register_google_font_pairing(
			'lato_roboto',
			foundation_create_google_font( 'heading', 'Lato', 'sans-serif', array( '300', '600' ) ),
			foundation_create_google_font( 'body', 'Roboto', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'droidserif_roboto',
			foundation_create_google_font( 'heading', 'Droid Serif', 'serif', array( '400', '700' ) ),
			foundation_create_google_font( 'body', 'Roboto', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'baumans_ubuntu',
			foundation_create_google_font( 'heading', 'Baumans', 'sans-serif', array( '400', '700' ) ),
			foundation_create_google_font( 'body', 'Ubuntu', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'alegreya_roboto',
			foundation_create_google_font( 'heading', 'Alegreya', 'serif', array( '400', '700' ) ),
			foundation_create_google_font( 'body', 'Roboto', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'fjalla_cantarell',
			foundation_create_google_font( 'heading', 'Fjalla One', 'sans-serif', array( '400' ) ),
			foundation_create_google_font( 'body', 'Open Sans', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'grandhotel_crimson',
			foundation_create_google_font( 'heading', 'Domine', 'sans-serif', array( '400' ) ),
			foundation_create_google_font( 'body', 'News Cycle', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'muli_montserrat',
			foundation_create_google_font( 'heading', 'Montserrat', 'sans-serif', array( '400' ) ),
			foundation_create_google_font( 'body', 'Muli', 'sans-serif', array( '400', '400italic' ) )
		);
	}
}

function bauhaus_body_classes( $classes ) {
	$settings = bauhaus_get_settings();

	$classes[] = 'circles';

	if ( !$settings->bauhaus_show_comment_bubbles ) {
		$classes[] = 'no-com-bubbles';
	}

	if ( $settings->bauhaus_menu_style == 'drop-down' ) {
		$classes[] = 'drop-down';
	} else {
		$classes[] = 'off-canvas';
	}

	return $classes;
}

function bauhaus_post_classes( $classes ) {
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_use_thumbnails != 'none' ) {
	  $classes[] = 'show-thumbs';
	} else {
	  $classes[] = 'no-thumbs';
	}

	return $classes;
}


function bauhaus_enqueue_customizer_script() {
	wp_enqueue_script(
		'bauhaus-customizer-js',
		BAUHAUS_URL . '/bauhaus-customizer.js',
		array( 'jquery' ),
		BAUHAUS_THEME_VERSION,
		false
	);
}

// Admin Settings

function bauhaus_header_settings( $header_settings ) {

	$header_settings[] = wptouch_add_pro_setting(
		'list',
		'bauhaus_menu_style',
		__( 'Menu animation style', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.3',
		array(
			'off-canvas' => __( 'Off-canvas', 'wptouch-pro' ),
			'drop-down' => __( 'Drop-down', 'wptouch-pro' )
		),
		BAUHAUS_SETTING_DOMAIN
	);

	$header_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_show_search',
		__( 'Show search in header', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.0',
		false,
		BAUHAUS_SETTING_DOMAIN
	);

	return $header_settings;
}

// Hook into Foundation page section for Blog and add settings
function bauhaus_blog_settings( $blog_settings ) {

	$blog_settings[] = wptouch_add_setting(
		'list',
		'bauhaus_use_thumbnails',
		__( 'Post thumbnails', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.0',
		array(
			'none' => __( 'No thumbnails', 'wptouch-pro' ),
			'index' => __( 'Blog listing only', 'wptouch-pro' ),
			'index_single' => __( 'Blog listing, single posts', 'wptouch-pro' ),
			'index_single_page' => __( 'Blog listing, single posts & pages', 'wptouch-pro' ),
			'all' => __( 'All (blog, single, pages, search & archive)', 'wptouch-pro' )
		),
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_pro_setting(
		'radiolist',
		'bauhaus_thumbnail_type',
		__( 'Thumbnail Type', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_ADVANCED,
		'1.0',
		array(
			'featured' => __( 'Post featured images', 'wptouch-pro' ),
			'custom_field' => __( 'Post custom field', 'wptouch-pro' )
		),
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_pro_setting(
		'text',
		'bauhaus_thumbnail_custom_field',
		__( 'Thumbnail custom field name', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_ADVANCED,
		'1.0',
		false,
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_show_taxonomy',
		__( 'Show post categories and tags', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.0',
		false,
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_show_date',
		__( 'Show post date', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.0',
		false,
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_show_author',
		__( 'Show post author', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.0',
		false,
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_show_comment_bubbles',
		__( 'Show comment bubbles on posts', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.0.5',
		false,
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_pro_setting(
		'checkbox',
		'bauhaus_use_infinite_scroll',
		__( 'Use infinite scrolling for blog', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.0',
		false,
		BAUHAUS_SETTING_DOMAIN
	);

	return $blog_settings;
}

function bauhaus_handle_has_thumbnail( $does_have_it ) {
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_thumbnail_type == 'custom_field' ) {
		if ( $settings->bauhaus_thumbnail_custom_field ) {
			global $post;

			$possible_image = get_post_meta( $post->ID, $settings->bauhaus_thumbnail_custom_field, true );
			return strlen( $possible_image );
 		}
	}

	return $does_have_it;
}

function bauhaus_handle_the_thumbnail( $current_thumbnail ) {
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_thumbnail_type == 'custom_field' ) {
		global $post;

		$image = get_post_meta( $post->ID, $settings->bauhaus_thumbnail_custom_field, true );
		echo $image;
	}

	return $current_thumbnail;
}

function bauhaus_handle_get_thumbnail( $current_thumbnail ) {
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_thumbnail_type == 'custom_field' ) {
		global $post;

		$image = get_post_meta( $post->ID, $settings->bauhaus_thumbnail_custom_field, true );
		return $image;
	}

	return $current_thumbnail;
}

function bauhaus_if_infinite_scroll_enabled(){
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_use_infinite_scroll ) {
		foundation_add_theme_support( 'infinite-scroll' );
	}
}

function bauhaus_if_off_canvas_enabled(){
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_menu_style == 'off-canvas' ) {
		foundation_add_theme_support( 'pushit' );
	}
}