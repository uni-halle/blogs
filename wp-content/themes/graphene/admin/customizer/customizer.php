<?php
/**
 * Load the files where we define our options
 */
require( GRAPHENE_ROOTDIR . '/admin/customizer/validators.php' );
require( GRAPHENE_ROOTDIR . '/admin/customizer/active-callback.php' );
require( GRAPHENE_ROOTDIR . '/admin/customizer/options-general.php' );
require( GRAPHENE_ROOTDIR . '/admin/customizer/options-display.php' );
require( GRAPHENE_ROOTDIR . '/admin/customizer/options-colours.php' );
require( GRAPHENE_ROOTDIR . '/admin/customizer/options-advanced.php' );
require( GRAPHENE_ROOTDIR . '/admin/customizer/options-utilities.php' );
require( GRAPHENE_ROOTDIR . '/admin/customizer/options-import.php' );


/**
 * Enqueue script for custom customize control.
 */
function graphene_enqueue_customizer_scripts() {
	global $graphene_settings;
	$version = $graphene_settings['scripts_ver'];

	if ( version_compare( get_bloginfo( 'version' ), '4.9', '<' ) ) {
		wp_enqueue_script( 'graphene-codemirror', 	GRAPHENE_ROOTURI . '/js/codemirror/codemirror.min.js', 	array(), '', false );
		wp_enqueue_style( 'graphene-codemirror', 	GRAPHENE_ROOTURI . '/js/codemirror/codemirror.css', 	array(), '', 'all' );
	}

	wp_enqueue_script( 'graphene-chosen', 		GRAPHENE_ROOTURI . '/js/chosen/chosen.jquery.min.js', 	array(), '', false );
	wp_enqueue_script( 'graphene-customizer', 	GRAPHENE_ROOTURI . '/admin/customizer/customizer.js', 	array( 'jquery', 'customize-controls' ), $version, true );
	wp_enqueue_script( 'jquery-ui-slider' );
	wp_enqueue_script( 'jquery-ui-sortable' );

	wp_enqueue_style( 'graphene-chosen', 		GRAPHENE_ROOTURI . '/js/chosen/chosen.css' );
	wp_enqueue_style( 'graphene-customizer', 	GRAPHENE_ROOTURI . '/admin/customizer/customizer.css', $version );
	wp_enqueue_style( 'jquery-ui-slider', 		GRAPHENE_ROOTURI . '/js/jquery-ui/jquery.ui.custom.css', array(), false );
	wp_enqueue_style( 'font-awesome', 			GRAPHENE_ROOTURI . '/fonts/font-awesome/css/font-awesome.min.css' );
	
	$l10n_data = array(
		'chosen_no_search_result'	=> __( 'Oops, nothing found.', 'graphene' ),
		'is_rtl'					=> is_rtl(),
		'import_select_file'		=> __( 'Please select the exported Graphene options file to import.', 'graphene' ),
		'delete'					=> __( 'Delete', 'graphene' ),
		'optional'					=> __( '(optional)', 'graphene' ),
		'link'						=> __( 'Link', 'graphene' ),
		'no_file'					=> __( 'Please select a settings file to import.', 'graphene' ),
		'ajaxurl'					=> admin_url( 'admin-ajax.php' ),
		'graphene_uri'				=> GRAPHENE_ROOTURI,
		'l10n'	=> array(
			'delete'		=> __( 'Delete', 'graphene' ),
			'select_image'	=> __( 'Select image', 'graphene' ),
			'links_to'		=> __( 'Links to', 'graphene' ),
			'drag_drop'		=> __( 'Drag and drop to reorder', 'graphene' ),
			'new'			=> __( 'New', 'graphene' )
		)
	);

	wp_localize_script( 'graphene-customizer', 'grapheneCustomizer', $l10n_data );

	global $graphene_settings;
	wp_localize_script( 'graphene-customizer', 'graphene_settings', $graphene_settings );
}
add_action( 'customize_controls_enqueue_scripts', 'graphene_enqueue_customizer_scripts', 20 );


/**
 * Enqueue script to preview the changed settings
 */
function graphene_enqueue_customizer_preview_scripts(){
	global $graphene_settings;
	$version = $graphene_settings['scripts_ver'];
	
	wp_enqueue_script( 'graphene-customizer-preview', GRAPHENE_ROOTURI . '/admin/customizer/customizer-preview.js', array( 'jquery' ), $version, true );
	wp_localize_script( 'graphene-customizer-preview', 'grapheneCustomizerPreview', array(
		'sectionNavTitle'	=> __( 'In this section', 'graphene' ),
	) );
}
add_action( 'customize_preview_init', 'graphene_enqueue_customizer_preview_scripts' );


/**
 * Add theme options to WordPress Customizer
 */
function graphene_customize_register( $wp_customize ) {

	/* Register all settings */
	global $graphene_defaults;
	$transport_settings = graphene_get_customizer_transport_settings();
	$validator_settings = graphene_get_customizer_validator_settings();

	/* Register Graphene settings */
	foreach ( $graphene_defaults as $setting => $default ) {
		$wp_customize->add_setting( 'graphene_settings[' . $setting . ']', array(
			'type' 				=> 'option',
			'default'			=> $default,
			'transport' 		=> $transport_settings[$setting],
			'sanitize_callback'	=> $validator_settings[$setting],
		) );
	}

	/* Change WordPress options transport */
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
	/* Register custom controls */
	graphene_add_customizer_controls( $wp_customize );
	
	/* Options panel */	
	$panels = apply_filters( 'graphene_customizer_panels', array(
		10 => array(
			'id'	=> 'graphene-general',
			'title'	=> __( 'Graphene: General', 'graphene' )
		),
		20 => array(
			'id'	=> 'graphene-display',
			'title'	=> __( 'Graphene: Display', 'graphene' ),
		),
		30 => array(
			'id'	=> 'graphene-colours',
			'title'			=> __( 'Graphene: Colours', 'graphene' ),
			'description'	=> '<p>' . __( "Changing colours for your website involves a lot more than just trial and error. Simply mixing and matching colours without regard to their compatibility may do more damage than good to your site's aesthetics.", 'graphene' ) . '</p><p>' . sprintf( __( "It's generally a good idea to stick to colours from colour pallettes that are aesthetically pleasing. Try the %s website for a kickstart on some colour palettes you can use.", 'graphene' ), '<a href="http://www.colourlovers.com/palettes/">COLOURlovers</a>' ) . '</p>',
		),
		40 => array(
			'id'	=> 'graphene-advanced',
			'title'	=> __( 'Graphene: Advanced', 'graphene' ),
		),
		50 => array(
			'id'	=> 'graphene-utilities',
			'title'	=> __( 'Graphene: Utilities', 'graphene' ),
		),
	) );

	ksort( $panels );
	foreach ( $panels as $panel ) $wp_customize->add_panel( $panel['id'], $panel );
	
	/* Register the options controls */
	graphene_customizer_general_options( $wp_customize );
	graphene_customizer_display_options( $wp_customize );
	graphene_customizer_colour_options( $wp_customize );
	graphene_customizer_advanced_options( $wp_customize );
	graphene_customizer_utilities( $wp_customize );

}
add_action( 'customize_register', 'graphene_customize_register' );


/**
 * Define the options for each setting
 */
function graphene_get_customizer_transport_settings(){
	global $graphene_defaults;
	
	/* By default set all settings to refresh transport */
	$transport_settings = array();
	foreach ( $graphene_defaults as $setting => $default ) {
		$transport_settings[$setting] = 'refresh';
	}
	
	/* Selectively set settings to postMessage transport */
	$settings = array(

		'header_text_align',
		'section_nav_title',

		'header_img_height',
		'slider_height',
		'slider_height_mobile',
		'copy_text',

		'mentions_bar_title',
		'mentions_bar_desc',

		'container_width',
		'column_width',
		
		'top_bar_bg',
		'menu_primary_bg',
		'menu_primary_item',
		'menu_primary_active_bg',
		'menu_primary_active_item',
		'menu_primary_dd_item',
		'menu_primary_dd_active_item',

		'menu_sec_border',
		'menu_sec_bg',
		'menu_sec_item',
		'menu_sec_active_bg',
		'menu_sec_active_item',
		'menu_sec_dd_item',
		'menu_sec_dd_active_item',
		
		'content_wrapper_bg',
		'content_bg',
		'meta_border',
		'content_font_colour',
		'title_font_colour',
		'link_colour_normal',
		'link_colour_hover',
		'sticky_border',
		'child_page_content_bg',

		'widget_item_bg',
		'widget_list',
		'widget_header_border',

		'slider_caption_bg',
		'slider_caption_text',
		'slider_card_bg',
		'slider_card_text',
		'slider_card_link',

		'button_bg',
		'button_label',
		'label_bg',
		'label_text',

		'archive_bg',
		'archive_border',
		'archive_label',
		'archive_text',
		
		'footer_bg',
		'footer_text',
		'footer_link',
		'footer_widget_bg',
		'footer_widget_border',
		'footer_widget_text',
		'footer_widget_link',

		'comments_bg',
		'comments_border',
		'comments_box_shadow',
		'comments_text',
		'author_comments_border',
	);
	foreach ( $settings as $setting ) {
		$transport_settings[$setting] = 'postMessage';
	}
	
	return apply_filters( 'graphene_get_customizer_transport_settings', $transport_settings );
}


/**
 * Define the validator for each setting
 */
function graphene_get_customizer_validator_settings(){
	global $graphene_defaults;
	
	/* By default set all settings to no validator */
	$validator_settings = array();
	foreach ( $graphene_defaults as $setting => $default ) {
		$validator_settings[$setting] = '';
	}
	
	/**
	 * Selectively set validator functions 
	 */

	/* Slider options */
	$validator_settings['slider_type']					= 'sanitize_text_field';
	$validator_settings['slider_specific_posts'] 		= 'sanitize_text_field';
    $validator_settings['slider_specific_categories']	= 'graphene_validate_multiple_select';
	$validator_settings['slider_exclude_categories']	= 'sanitize_text_field';
	$validator_settings['slider_content']				= 'sanitize_text_field';
	$validator_settings['slider_exclude_posts'] 		= 'sanitize_text_field';
	$validator_settings['slider_exclude_posts_cats']	= 'graphene_validate_multiple_select';
	$validator_settings['slider_postcount'] 			= 'absint';
	$validator_settings['slider_height'] 				= 'absint';
	$validator_settings['slider_interval']				= 'graphene_validate_numeric';
	$validator_settings['slider_trans_duration'] 		= 'graphene_validate_numeric';
	
	/* Front page options */
	$validator_settings['frontpage_posts_cats'] 	= 'graphene_validate_multiple_select';
	$validator_settings['front_page_blog_columns']	= 'absint';
	
	/* Footer options */
	$validator_settings['copy_text'] 	= 'wp_kses_post';
	
	/* Excerpt options */
	$validator_settings['excerpt_html_tags'] = 'wp_kses_post';
	
	/* Footer widget options */
	$validator_settings['footerwidget_column']	= 'absint';
		
	/* Miscellaneous options */
	$validator_settings['favicon_url']	= 'esc_url_raw';
	$validator_settings['custom_css']	= 'graphene_validate_css';

	/* Advanced options */
	$validator_settings['widget_hooks'] = 'graphene_validate_multiple_checkboxes';

	/* Column width */
	$validator_settings['column_width'] = 'graphene_validate_json';

	/* Social profiles */
	$validator_settings['social_profiles'] = 'graphene_validate_json';

	/* Brand icons */
	$validator_settings['brand_icons'] = 'graphene_validate_json';
	
	return apply_filters( 'graphene_get_customizer_validator_settings', $validator_settings );
}


/**
 * Filter the returned settings from the database for live preview in customizer
 */
function graphene_customizer_filter_settings( $graphene_settings ){
		
	if ( isset( $_POST['customized'] ) ) {
		$customized_settings = json_decode( wp_unslash( $_POST['customized'] ), true );
		foreach ( $customized_settings as $setting => $value ) {
			if ( stripos( $setting, 'graphene_settings' ) === 0 ) {
				$setting = str_replace( 'graphene_settings[', '', str_replace( ']', '', $setting ) );
				$graphene_settings[$setting] = $value;
			}
		}

		if ( ! is_array( $graphene_settings['widget_hooks'] ) ) $graphene_settings['widget_hooks'] = explode( ',', $graphene_settings['widget_hooks'] );
		if ( ! is_array( $graphene_settings['social_profiles'] ) ) $graphene_settings['social_profiles'] = json_decode( $graphene_settings['social_profiles'], true );
		if ( ! is_array( $graphene_settings['brand_icons'] ) ) $graphene_settings['brand_icons'] = json_decode( $graphene_settings['brand_icons'], true );
	}
	return $graphene_settings;
}
add_filter( 'graphene_settings', 'graphene_customizer_filter_settings', 999 );


/**
 * Register multiple similar options at once
 */
function graphene_add_customizer_options( $args = array(), $wp_customize ) {
	$defaults = array(
		'type'		=> '',
		'options'	=> '',
		'section'	=> '',
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	if ( $type == 'checkbox' ) {
		foreach ( $options as $option => $option_args ) {

			$option_args['type'] = $type;
			$option_args['section'] = $section;

			$wp_customize->add_control( 'graphene_settings[' . $option . ']', $option_args );
		}
	}

	if ( $type == 'colour' ) {
		foreach ( $options as $option => $option_args ) {
			$option_args['section'] = $section;
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'graphene_settings[' . $option . ']', $option_args ) );
		}
	}
}


/**
 * Purge Autoptimize page cache after Customizer changeset is published
 */
function graphene_autoptimize_cache_purge( $wp_customize ){
	if ( class_exists( 'autoptimizeCache' ) ) {
		autoptimizeCache::clearall();
		autoptimize_flush_pagecache();
	}
}
add_action( 'customize_save_after', 'graphene_autoptimize_cache_purge' );