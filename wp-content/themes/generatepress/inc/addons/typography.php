<?php
/*
 WARNING: This is a core Generate file. DO NOT edit
 this file under any circumstances. Please do all modifications
 in the form of a child theme.
 */

/**
 * If Generate Typography isn't activated, set the defaults
 *
 * This file is a core Generate file and should not be edited.
 *
 * @package  WordPress
 * @author   Thomas Usborne
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     http://www.generatepress.com
 */
 
if ( !function_exists('generate_get_default_fonts') && !function_exists('generate_font_css') && !function_exists('generate_display_google_fonts') ) :
	/**
	 * Set default options
	 */
	function generate_get_default_fonts()
	{
		$generate_font_defaults = array(
			'font_body' => 'Open Sans:300,300italic,regular,italic,600,600italic,700,700italic,800,800italic',
			'body_font_weight' => 'normal',
			'body_font_transform' => 'none',
			'body_font_size' => '17',
			'font_site_title' => 'inherit',
			'site_title_font_weight' => 'bold',
			'site_title_font_transform' => 'none',
			'site_title_font_size' => '45',
			'font_site_tagline' => 'inherit',
			'site_tagline_font_weight' => 'normal',
			'site_tagline_font_transform' => 'none',
			'site_tagline_font_size' => '15',
			'font_navigation' => 'inherit',
			'navigation_font_weight' => 'normal',
			'navigation_font_transform' => 'none',
			'navigation_font_size' => '15',
			'font_widget_title' => 'inherit',
			'widget_title_font_weight' => 'normal',
			'widget_title_font_transform' => 'none',
			'widget_title_font_size' => '20',
			'font_heading_1' => 'inherit',
			'heading_1_weight' => '300',
			'heading_1_transform' => 'none',
			'heading_1_font_size' => '40',
			'font_heading_2' => 'inherit',
			'heading_2_weight' => '300',
			'heading_2_transform' => 'none',
			'heading_2_font_size' => '30',
			'font_heading_3' => 'inherit',
			'heading_3_weight' => 'normal',
			'heading_3_transform' => 'none',
			'heading_3_font_size' => '20',
			'font_heading_4' => 'inherit',
			'heading_4_weight' => 'normal',
			'heading_4_transform' => 'none',
			'heading_4_font_size' => '15',
		);
		
		return apply_filters( 'generate_font_option_defaults', $generate_font_defaults );
	}
	/**
	 * Generate the CSS in the <head> section using the Theme Customizer
	 * @since 0.1
	 */
	function generate_font_css()
	{

		$generate_settings = wp_parse_args( 
			get_option( 'generate_settings', array() ), 
			generate_get_default_fonts() 
		);
		
		// Calculate the sub navigation font size based on the navigation font size
		if ( $generate_settings['navigation_font_size'] >= 17 ) :
			$subnav_font_size = $generate_settings['navigation_font_size'] - 3;
		else :
			$subnav_font_size = $generate_settings['navigation_font_size'] - 2;
		endif;
		
		// Set a fallback for the fonts
		$fallback = apply_filters( 'generate_fallback_font','sans-serif' );
		
		// Create all of our font family entries
		$body_family = ( 'inherit' == $generate_settings['font_body'] ) ? current(explode(':', $generate_settings['font_body'])) : current(explode(':', $generate_settings['font_body'])) . ' ,' . $fallback;
		$site_title_family = ( 'inherit' == $generate_settings['font_site_title'] ) ? current(explode(':', $generate_settings['font_site_title'])) : current(explode(':', $generate_settings['font_site_title'])) . ' ,' . $fallback;
		$site_tagline_family = ( 'inherit' == $generate_settings['font_site_tagline'] ) ? current(explode(':', $generate_settings['font_site_tagline'])) : current(explode(':', $generate_settings['font_site_tagline'])) . ' ,' . $fallback;
		$navigation_family = ( 'inherit' == $generate_settings['font_navigation'] ) ? current(explode(':', $generate_settings['font_navigation'])) : current(explode(':', $generate_settings['font_navigation'])) . ' ,' . $fallback;
		$widget_family = ( 'inherit' == $generate_settings['font_widget_title'] ) ? current(explode(':', $generate_settings['font_widget_title'])) : current(explode(':', $generate_settings['font_widget_title'])) . ' ,' . $fallback;
		$h1_family = ( 'inherit' == $generate_settings['font_heading_1'] ) ? current(explode(':', $generate_settings['font_heading_1'])) : current(explode(':', $generate_settings['font_heading_1'])) . ' ,' . $fallback;
		$h2_family = ( 'inherit' == $generate_settings['font_heading_2'] ) ? current(explode(':', $generate_settings['font_heading_2'])) : current(explode(':', $generate_settings['font_heading_2'])) . ' ,' . $fallback;
		$h3_family = ( 'inherit' == $generate_settings['font_heading_3'] ) ? current(explode(':', $generate_settings['font_heading_3'])) : current(explode(':', $generate_settings['font_heading_3'])) . ' ,' . $fallback;
		
		$space = ' ';
		
		// Start the magic
		$visual_css = array(
		
			// Body font
			'body, button, input, select, textarea' => array(
				'font-family' => $body_family,
				'font-weight' => $generate_settings['body_font_weight'],
				'text-transform' => $generate_settings['body_font_transform'],
				'font-size' => $generate_settings['body_font_size'] . 'px'
			),
			
			// Main title font
			'.main-title' => array(
				'font-family' => $site_title_family,
				'font-weight' => $generate_settings['site_title_font_weight'],
				'text-transform' => $generate_settings['site_title_font_transform'],
				'font-size' => $generate_settings['site_title_font_size'] . 'px'
			),
			
			// Main tagline font
			'.site-description' => array(
				'font-family' => $site_tagline_family,
				'font-weight' => $generate_settings['site_tagline_font_weight'],
				'text-transform' => $generate_settings['site_tagline_font_transform'],
				'font-size' => $generate_settings['site_tagline_font_size'] . 'px'
			),
			
			// Navigation font
			'.main-navigation a, .menu-toggle' => array(
				'font-family' => $navigation_family,
				'font-weight' => $generate_settings['navigation_font_weight'],
				'text-transform' => $generate_settings['navigation_font_transform'],
				'font-size' => $generate_settings['navigation_font_size'] . 'px'
			),
			
			'.main-navigation .main-nav ul ul li a' => array(
				'font-size' => $subnav_font_size . 'px'
			),
			
			// Widget title font
			'.widget-title' => array(
				'font-family' => $widget_family,
				'font-weight' => $generate_settings['widget_title_font_weight'],
				'text-transform' => $generate_settings['widget_title_font_transform'],
				'font-size' => $generate_settings['widget_title_font_size'] . 'px'
			),
			
			// Heading 1 font
			'h1' => array(
				'font-family' => $h1_family,
				'font-weight' => $generate_settings['heading_1_weight'],
				'text-transform' => $generate_settings['heading_1_transform'],
				'font-size' => $generate_settings['heading_1_font_size'] . 'px'
			),
			
			// Heading 2 font
			'h2' => array(
				'font-family' => $h2_family,
				'font-weight' => $generate_settings['heading_2_weight'],
				'text-transform' => $generate_settings['heading_2_transform'],
				'font-size' => $generate_settings['heading_2_font_size'] . 'px'
			),
			
			// Heading 3 font
			'h3' => array(
				'font-family' => $h3_family,
				'font-weight' => $generate_settings['heading_3_weight'],
				'text-transform' => $generate_settings['heading_3_transform'],
				'font-size' => $generate_settings['heading_3_font_size'] . 'px'
			)
			
		);
		
		// Output the above CSS
		$output = '';
		foreach($visual_css as $k => $properties) {
			if(!count($properties))
				continue;

			$temporary_output = $k . ' {';
			$elements_added = 0;

			foreach($properties as $p => $v) {
				if(empty($v))
					continue;

				$elements_added++;
				$temporary_output .= $p . ': ' . $v . '; ';
			}

			$temporary_output .= "}";

			if($elements_added > 0)
				$output .= $temporary_output;
		}

		return $output;
	}
	
	/**
	 * Enqueue scripts and styles
	 */
	add_action( 'wp_enqueue_scripts', 'generate_typography_scripts', 50 );
	function generate_typography_scripts() {

		wp_add_inline_style( 'generate-style', generate_font_css() );
	
	}
	
	/** 
	 * Add Google Fonts to wp_head if needed
	 * @since 0.1
	 */
	add_action('wp_enqueue_scripts','generate_display_google_fonts', 0);
	function generate_display_google_fonts($google_fonts) {
		
		if ( is_admin() )
			return;
		
		$not_google = array(
			'inherit',
			'Arial,+Helvetica,+sans-serif',
			'Century+Gothic',
			'Comic+Sans+MS',
			'Courier+New',
			'Georgia,+Times+New+Roman,+Times,+serif',
			'Helvetica',
			'Impact',
			'Lucida+Console',
			'Lucida+Sans Unicode',
			'Palatino+Linotype',
			'Tahoma,+Geneva,+sans-serif',
			'Trebuchet+MS,+Helvetica,+sans-serif',
			'Verdana,+Geneva,+sans-serif'
		);

		$generate_settings = wp_parse_args( 
			get_option( 'generate_settings', array() ), 
			generate_get_default_fonts() 
		);
		
		$google_fonts = array();
		if ( !empty($generate_settings) ) :
			foreach($generate_settings as $vkey => $vvalue) {
				if(preg_match('~^font_*~is', $vkey) !== 0 && !empty($vvalue)) {
					$vvalue = str_replace(" ", "+", $vvalue);
					if(!in_array($vvalue, $google_fonts)) {
						$google_fonts[] = $vvalue;
					}
				}
			}
		endif;

		$google_fonts = array_diff($google_fonts, $not_google);
		$google_fonts = implode('|', $google_fonts);
		
		$google_fonts = apply_filters( 'generate_typography_google_fonts', $google_fonts );
		
		$font_args = array(
            'family' => $google_fonts,
            'subset' => urlencode( apply_filters( 'generate_fonts_subset','latin,latin-ext' ) ),
        );
 
        $fonts_url = add_query_arg( $font_args, '//fonts.googleapis.com/css' );
		
		if ( $google_fonts ) { 
			wp_enqueue_style('generate-fonts', $fonts_url, false, GENERATE_VERSION, 'all' );
		}
	}
endif;

if ( ! function_exists( 'generate_fonts_customize_register' ) ) :
	add_action( 'customize_register', 'generate_default_fonts_customize_register' );
	function generate_default_fonts_customize_register( $wp_customize ) {

		require_once get_template_directory() . '/inc/addons/typography/get-fonts.php';
		require_once get_template_directory() . '/inc/addons/typography/google-font-dropdown-custom-control.php';

		$defaults = generate_get_default_fonts();

		$wp_customize->add_section(
			// ID
			'font_section',
			// Arguments array
			array(
				'title' => __( 'Typography', 'generate' ),
				'capability' => 'edit_theme_options',
				'description' => '',
				'priority' => 30
			)
		);
		
		// Add body fonts
		$wp_customize->add_setting( 
			'generate_settings[font_body]', 
			array(
				'default' => $defaults['font_body'],
				'type' => 'option',
				'sanitize_callback' => 'generate_sanitize_typography'
			)
		);
			
		$wp_customize->add_control( 
			new Generate_Google_Font_Dropdown_Custom_Control( 
				$wp_customize, 
				'google_font_body_control', 
				array(
					'label' => __('Body','generate'),
					'section' => 'font_section',
					'settings' => 'generate_settings[font_body]',
					'priority' => 1
				)
			)
		);
		
		$wp_customize->add_setting( 
			'generate_settings[body_font_weight]', 
			array(
				'default' => $defaults['body_font_weight'],
				'type' => 'option',
				'sanitize_callback' => 'generate_sanitize_font_weight'
			)
		);
			
		$wp_customize->add_control( 
			new Generate_Font_Weight_Custom_Control( 
				$wp_customize, 
				'body_font_weight_control', 
				array(
					'label' => __('Font weight','generate'),
					'section' => 'font_section',
					'settings' => 'generate_settings[body_font_weight]',
					'priority' => 20,
					'type' => 'weight'
				)
			)
		);
		
		$wp_customize->add_setting( 
			'generate_settings[body_font_transform]', 
			array(
				'default' => $defaults['body_font_transform'],
				'type' => 'option',
				'sanitize_callback' => 'generate_sanitize_text_transform'
				
			)
		);
			
		$wp_customize->add_control( 
			new Generate_Text_Transform_Custom_Control( 
				$wp_customize, 
				'body_font_transform_control', 
				array(
					'label' => __('Text transform','generate'),
					'section' => 'font_section',
					'settings' => 'generate_settings[body_font_transform]',
					'priority' => 30,
					'type' => 'transform'
				)
			)
		);
		
		$wp_customize->add_setting( 
			'generate_settings[body_font_size]', 
			array(
				'default' => $defaults['body_font_size'],
				'type' => 'option',
				'sanitize_callback' => 'generate_sanitize_integer'
			)
		);
			
		$wp_customize->add_control( 
			new Generate_Customize_Slider_Control( 
				$wp_customize, 
				'generate_settings[body_font_size]', 
				array(
					'label' => __('Font size','generate'),
					'section' => 'font_section',
					'settings' => 'generate_settings[body_font_size]',
					'priority' => 40
				)
			)
		);
		
		if ( !function_exists( 'generate_fonts_customize_register' ) && ! defined( 'GP_PREMIUM_VERSION' ) ) {

			$wp_customize->add_control(
				new Generate_Customize_Misc_Control(
					$wp_customize,
					'typography_get_addon_desc',
					array(
						'section'     => 'font_section',
						'type'        => 'addon',
						'label'			=> __( 'More Settings','generate' ),
						'url' => 'http://www.generatepress.com/downloads/generate-typography/',
						'description' => sprintf(
							__( 'Looking to add more typography settings?<br /> %s.', 'generate' ),
							sprintf(
								'<a href="%1$s" target="_blank">%2$s</a>',
								esc_url( 'http://www.generatepress.com/downloads/generate-typography/' ),
								__( 'Check out Generate Typography', 'generate' )
							)
						),
						'priority'    => 50
					)
				)
			);
		}
		
		
	}
endif;