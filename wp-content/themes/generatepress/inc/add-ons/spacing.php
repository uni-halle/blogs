<?php
/*
 WARNING: This is a core Generate file. DO NOT edit
 this file under any circumstances. Please do all modifications
 in the form of a child theme.
 */

/**
 * Generate Spacing integration
 *
 * This file is a core Generate file and should not be edited.
 *
 * @package  GeneratePress
 * @author   Thomas Usborne
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     http://www.generatepress.com
 */

if ( !function_exists('generate_spacing_get_defaults') ) :
	function generate_spacing_get_defaults()
	{
		$generate_spacing_defaults = array(
			'header_top' => '40',
			'header_right' => '40',
			'header_bottom' => '40',
			'header_left' => '40',
			'menu_item' => '20',
			'menu_item_height' => '60',
			'sub_menu_item_height' => '10',
			'content_top' => '40',
			'content_right' => '40',
			'content_bottom' => '40',
			'content_left' => '40',
			'separator' => '20',
			'left_sidebar_width' => '25',
			'right_sidebar_width' => '25',
			'widget_top' => '40',
			'widget_right' => '40',
			'widget_bottom' => '40',
			'widget_left' => '40',
			'footer_widget_container_top' => '40',
			'footer_widget_container_right' => '0',
			'footer_widget_container_bottom' => '40',
			'footer_widget_container_left' => '0',
			'footer_top' => '20',
			'footer_right' => '0',
			'footer_bottom' => '20',
			'footer_left' => '0',
		);
		
		return apply_filters( 'generate_spacing_option_defaults', $generate_spacing_defaults );
	}
endif;
if ( !function_exists('generate_spacing_css') ) :
	function generate_spacing_css()
	{
		$spacing_settings = wp_parse_args( 
			get_option( 'generate_spacing_settings', array() ), 
			generate_spacing_get_defaults() 
		);
			
		$space = ' ';
		// Start the magic
		$spacing_css = array (
		
			'.inside-header' => array(
				'padding-top' => ( isset( $spacing_settings['header_top'] ) ) ? $spacing_settings['header_top'] . 'px' : null,
				'padding-right' => ( isset( $spacing_settings['header_right'] ) ) ? $spacing_settings['header_right'] . 'px' : null,
				'padding-bottom' => ( isset( $spacing_settings['header_bottom'] ) ) ? $spacing_settings['header_bottom'] . 'px' : null,
				'padding-left' => ( isset( $spacing_settings['header_left'] ) ) ? $spacing_settings['header_left'] . 'px' : null,
			),
			
			'.separate-containers .inside-article, .separate-containers .comments-area, .separate-containers .page-header, .separate-containers .paging-navigation, .one-container .site-content' => array(
				'padding-top' => ( isset( $spacing_settings['content_top'] ) ) ? $spacing_settings['content_top'] . 'px' : null,
				'padding-right' => ( isset( $spacing_settings['content_right'] ) ) ? $spacing_settings['content_right'] . 'px' : null,
				'padding-bottom' => ( isset( $spacing_settings['content_bottom'] ) ) ? $spacing_settings['content_bottom'] . 'px' : null,
				'padding-left' => ( isset( $spacing_settings['content_left'] ) ) ? $spacing_settings['content_left'] . 'px' : null,
			),
			
			'.ignore-x-spacing' => array(
				'margin-right' => ( isset( $spacing_settings['content_right'] ) ) ? '-' . $spacing_settings['content_right'] . 'px' : null,
				'margin-bottom' => ( isset( $spacing_settings['content_bottom'] ) ) ? $spacing_settings['content_bottom'] . 'px' : null,
				'margin-left' => ( isset( $spacing_settings['content_left'] ) ) ? '-' . $spacing_settings['content_left'] . 'px' : null,
			),
			
			'.ignore-xy-spacing' => array(
				'margin-top' => ( isset( $spacing_settings['content_top'] ) ) ? '-' . $spacing_settings['content_top'] . 'px' : null,
				'margin-right' => ( isset( $spacing_settings['content_right'] ) ) ? '-' . $spacing_settings['content_right'] . 'px' : null,
				'margin-bottom' => ( isset( $spacing_settings['content_bottom'] ) ) ? $spacing_settings['content_bottom'] . 'px' : null,
				'margin-left' => ( isset( $spacing_settings['content_left'] ) ) ? '-' . $spacing_settings['content_left'] . 'px' : null,
			),
			
			'.main-navigation .main-nav ul li a,
			.menu-toggle,
			.menu-toggle .search-item a,
			.menu-toggle .search-item-disabled a' => array(
				'padding-left' => ( isset( $spacing_settings['menu_item'] ) ) ? $spacing_settings['menu_item'] . 'px' : null,
				'padding-right' => ( isset( $spacing_settings['menu_item'] ) ) ? $spacing_settings['menu_item'] . 'px' : null,
				'line-height' => ( isset( $spacing_settings['menu_item_height'] ) ) ? $spacing_settings['menu_item_height'] . 'px' : null,
			),
			
			'.nav-float-right .main-navigation .main-nav ul li a' => array(
				'line-height' => ( isset( $spacing_settings['menu_item_height'] ) ) ? $spacing_settings['menu_item_height'] . 'px' : null,
			),
			
			'.main-navigation .main-nav ul ul li a' => array(
				'padding-left' => ( isset( $spacing_settings['menu_item'] ) ) ? $spacing_settings['menu_item'] . 'px' : null,
				'padding-right' => ( isset( $spacing_settings['menu_item'] ) ) ? $spacing_settings['menu_item'] . 'px' : null,
				'padding-top' => ( isset( $spacing_settings['sub_menu_item_height'] ) ) ? $spacing_settings['sub_menu_item_height'] . 'px' : null,
				'padding-bottom' => ( isset( $spacing_settings['sub_menu_item_height'] ) ) ? $spacing_settings['sub_menu_item_height'] . 'px' : null,
			),
			
			'.main-navigation ul ul' => array(
				'top' => ( isset( $spacing_settings['menu_item_height'] ) ) ? $spacing_settings['menu_item_height'] . 'px' : null
			),
			
			'.navigation-search' => array(
				'height' => ( isset( $spacing_settings['menu_item_height'] ) ) ? $spacing_settings['menu_item_height'] . 'px' : null,
				'line-height' => '0px'
			),
			
			'.navigation-search input' => array(
				'height' => ( isset( $spacing_settings['menu_item_height'] ) ) ? $spacing_settings['menu_item_height'] . 'px' : null,
				'line-height' => '0px'
			),
			
			'.separate-containers .widget-area .widget' => array(
				'padding-top' => ( isset( $spacing_settings['widget_top'] ) ) ? $spacing_settings['widget_top'] . 'px' : null,
				'padding-right' => ( isset( $spacing_settings['widget_right'] ) ) ? $spacing_settings['widget_right'] . 'px' : null,
				'padding-bottom' => ( isset( $spacing_settings['widget_bottom'] ) ) ? $spacing_settings['widget_bottom'] . 'px' : null,
				'padding-left' => ( isset( $spacing_settings['widget_left'] ) ) ? $spacing_settings['widget_left'] . 'px' : null,
			),
			
			'.footer-widgets' => array(
				'padding-top' => ( isset( $spacing_settings['footer_widget_container_top'] ) ) ? $spacing_settings['footer_widget_container_top'] . 'px' : null,
				'padding-right' => ( isset( $spacing_settings['footer_widget_container_right'] ) ) ? $spacing_settings['footer_widget_container_right'] . 'px' : null,
				'padding-bottom' => ( isset( $spacing_settings['footer_widget_container_bottom'] ) ) ? $spacing_settings['footer_widget_container_bottom'] . 'px' : null,
				'padding-left' => ( isset( $spacing_settings['footer_widget_container_left'] ) ) ? $spacing_settings['footer_widget_container_left'] . 'px' : null,
			),
			
			'.site-info' => array(
				'padding-top' => ( isset( $spacing_settings['footer_top'] ) ) ? $spacing_settings['footer_top'] . 'px' : null,
				'padding-right' => ( isset( $spacing_settings['footer_right'] ) ) ? $spacing_settings['footer_right'] . 'px' : null,
				'padding-bottom' => ( isset( $spacing_settings['footer_bottom'] ) ) ? $spacing_settings['footer_bottom'] . 'px' : null,
				'padding-left' => ( isset( $spacing_settings['footer_left'] ) ) ? $spacing_settings['footer_left'] . 'px' : null,
			),
			
			'.right-sidebar.separate-containers .site-main' => array(
				'margin-top' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'margin-right' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'margin-bottom' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'margin-left' => '0px',
				'padding' => '0px'
			),
			
			'.left-sidebar.separate-containers .site-main' => array(
				'margin-top' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'margin-right' => '0px',
				'margin-bottom' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'margin-left' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'padding' => '0px'
			),
			
			'.both-sidebars.separate-containers .site-main' => array(
				'margin' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'padding' => '0px'
			),
			
			'.both-right.separate-containers .site-main' => array(
				'margin-top' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'margin-right' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'margin-bottom' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'margin-left' => '0px',
				'padding' => '0px'
			),
			
			'.separate-containers .site-main' => array(
				'margin-top' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'margin-bottom' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'padding' => '0px'
			),
			
			'.separate-containers .page-header-image, .separate-containers .page-header-content, .separate-containers .page-header-image-single, .separate-containers .page-header-content-single' => array(
				'margin-top' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
			),
			
			'.both-left.separate-containers .site-main' => array(
				'margin-top' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'margin-right' => '0px',
				'margin-bottom' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'margin-left' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'padding' => '0px'
			),
			
			'.separate-containers .inside-right-sidebar, .inside-left-sidebar' => array(
				'margin-top' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'margin-bottom' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
				'padding-top' => '0px',
				'padding-bottom' => '0px'
			),
			
			'.separate-containers .widget, .separate-containers .hentry, .separate-containers .page-header, .widget-area .main-navigation' => array(
				'margin-bottom' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] . 'px' : null,
			),
			
			'.both-left.separate-containers .inside-left-sidebar' => array(
				'margin-right' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] / 2 . 'px' : null,
				'padding-right' => '0px'
			),
			
			'.both-left.separate-containers .inside-right-sidebar' => array(
				'margin-left' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] / 2 . 'px' : null,
				'padding-left' => '0px'
			),
			
			'.both-right.separate-containers .inside-left-sidebar' => array(
				'margin-right' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] / 2 . 'px' : null,
				'padding-right' => '0px'
			),

			'.both-right.separate-containers .inside-right-sidebar' => array(
				'margin-left' => ( isset( $spacing_settings['separator'] ) ) ? $spacing_settings['separator'] / 2 . 'px' : null,
				'padding-left' => '0px'
			)
			
		);
		
		// Output the above CSS
		$output = '';
		foreach($spacing_css as $k => $properties) {
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

		$output = str_replace(array("\r", "\n"), '', $output);
		return $output; 
	}
	
	/**
	 * Enqueue scripts and styles
	 */
	add_action( 'wp_enqueue_scripts', 'generate_spacing_scripts', 50 );
	function generate_spacing_scripts() {

		wp_add_inline_style( 'generate-style', generate_spacing_css() );
	
	}
endif;