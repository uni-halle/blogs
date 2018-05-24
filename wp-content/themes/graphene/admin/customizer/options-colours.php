<?php
/**
 * Graphene Advanced options
 *
 * @package Graphene
 * @since 2.0
 */
function graphene_customizer_colour_options( $wp_customize ){

	/* =Top Bar
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-colours-top-bar', array(
		'title'	=> __( 'Top Bar', 'graphene' ),
		'panel'	=> 'graphene-colours',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'graphene_settings[top_bar_bg]', array(
		'section' 	=> 'graphene-colours-top-bar',
		'label' 	=> __( 'Background', 'graphene' ),
	) ) );


	/* =Primary Menu
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-colours-menu-primary', array(
		'title'	=> __( 'Primary Menu', 'graphene' ),
		'panel'	=> 'graphene-colours',
	) );

	$options = array(
		'type'		=> 'colour',
		'section'	=> 'graphene-colours-menu-primary',
		'options'	=> array(
			'menu_primary_bg'				=> array( 'label' => __( 'Background (default state)', 'graphene' ) ),
			'menu_primary_item'				=> array( 'label' => __( 'Text (default state)', 'graphene' ) ),
			'menu_primary_active_bg'		=> array( 'label' => __( 'Background (active state)', 'graphene' ) ),
			'menu_primary_active_item'		=> array( 'label' => __( 'Text (active state)', 'graphene' ) ),
			'menu_primary_dd_item'			=> array( 'label' => __( 'Dropdown menu text (default state)', 'graphene' ) ),
			'menu_primary_dd_active_item'	=> array( 'label' => __( 'Dropdown menu text (active state)', 'graphene' ) ),
		)
	);
	graphene_add_customizer_options( $options, $wp_customize );


	/* =Secondary Menu
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-colours-menu-secondary', array(
		'title'	=> __( 'Secondary Menu', 'graphene' ),
		'panel'	=> 'graphene-colours',
	) );

	$options = array(
		'type'		=> 'colour',
		'section'	=> 'graphene-colours-menu-secondary',
		'options'	=> array(
			'menu_sec_border'			=> array( 'label' => __( 'Top border', 'graphene' ) ),
			'menu_sec_bg'				=> array( 'label' => __( 'Background (default state)', 'graphene' ) ),
			'menu_sec_item'				=> array( 'label' => __( 'Text (default state)', 'graphene' ) ),
			'menu_sec_active_bg'		=> array( 'label' => __( 'Background (active state)', 'graphene' ) ),
			'menu_sec_active_item'		=> array( 'label' => __( 'Text (active state)', 'graphene' ) ),
			'menu_sec_dd_item'			=> array( 'label' => __( 'Dropdown menu text (default state)', 'graphene' ) ),
			'menu_sec_dd_active_item'	=> array( 'label' => __( 'Dropdown menu text (active state)', 'graphene' ) ),
		)
	);
	graphene_add_customizer_options( $options, $wp_customize );


	/* =Slider
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-colours-slider', array(
		'title'	=> __( 'Slider', 'graphene' ),
		'panel'	=> 'graphene-colours',
	) );

	$wp_customize->add_control(	new Graphene_Customize_Alpha_Color_Control( $wp_customize, 'graphene_settings[slider_caption_bg]', array(
		'label'         => __( 'Slider caption background', 'graphene' ),
		'section'       => 'graphene-colours-slider',
		'show_opacity'  => true,
	) ) );

	$options = array(
		'type'		=> 'colour',
		'section'	=> 'graphene-colours-slider',
		'options'	=> array(
            'slider_caption_text'   => array( 'label' => __( 'Slider caption text', 'graphene' ) ),
            'slider_card_bg'   		=> array( 'label' => __( 'Card display style: background', 'graphene' ) ),
            'slider_card_text'   	=> array( 'label' => __( 'Card display style: text', 'graphene' ) ),
            'slider_card_link'   	=> array( 'label' => __( 'Card display style: links', 'graphene' ) ),
		)
	);
	graphene_add_customizer_options( $options, $wp_customize );


	/* =Content Area
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-colours-content-area', array(
		'title'	=> __( 'Content Area', 'graphene' ),
		'panel'	=> 'graphene-colours',
	) );

	$options = array(
		'type'		=> 'colour',
		'section'	=> 'graphene-colours-content-area',
		'options'	=> array(
			'content_wrapper_bg'    => array( 'label' => __( 'Main content wrapper background', 'graphene' ) ),
            'content_bg'            => array( 'label' => __( 'Post and pages content background', 'graphene' ) ),
            'meta_border'           => array( 'label' => __( 'Post meta and footer border', 'graphene' ) ),
            'content_font_colour'   => array( 'label' => __( 'Content text', 'graphene' ) ),
            'title_font_colour'     => array( 'label' => __( 'Title text', 'graphene' ) ),
            'link_colour_normal'    => array( 'label' => __( 'Links', 'graphene' ) ),
            'link_colour_hover'     => array( 'label' => __( 'Links (hover)', 'graphene' ) ),
   		    'sticky_border'         => array( 'label' => __( 'Sticky posts border', 'graphene' ) ),
            'child_page_content_bg' => array( 'label' => __( 'Child pages content background', 'graphene' ) ),
		)
	);
	graphene_add_customizer_options( $options, $wp_customize );


	/* =Widgets
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-colours-widgets', array(
		'title'	=> __( 'Widgets', 'graphene' ),
		'panel'	=> 'graphene-colours',
	) );

	$options = array(
		'type'		=> 'colour',
		'section'	=> 'graphene-colours-widgets',
		'options'	=> array(
			'widget_item_bg'         => array( 'label' => __( 'Widget item background', 'graphene' ) ),
            'widget_list'            => array( 'label' => __( 'Widget item list border', 'graphene' ) ),
            'widget_header_border'   => array( 'label' => __( 'Widget header border', 'graphene' ) ),
		)
	);
	graphene_add_customizer_options( $options, $wp_customize );


	/* =Buttons and Labels
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-colours-buttons-labels', array(
		'title'	=> __( 'Buttons and Labels', 'graphene' ),
		'panel'	=> 'graphene-colours',
	) );

	$options = array(
		'type'		=> 'colour',
		'section'	=> 'graphene-colours-buttons-labels',
		'options'	=> array(
            'button_bg'     => array( 'label' => __( 'Button background', 'graphene' ) ),
            'button_label'  => array( 'label' => __( 'Button text', 'graphene' ) ),
            'label_bg'      => array( 'label' => __( 'Label background', 'graphene' ) ),
            'label_text'    => array( 'label' => __( 'Label text', 'graphene' ) ),
		)
	);
	graphene_add_customizer_options( $options, $wp_customize );


	/* =Archives
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-colours-archives', array(
		'title'	=> __( 'Archives', 'graphene' ),
		'panel'	=> 'graphene-colours',
	) );

	$options = array(
		'type'		=> 'colour',
		'section'	=> 'graphene-colours-archives',
		'options'	=> array(
            'archive_bg'        => array( 'label' => __( 'Archive label background', 'graphene' ) ),
		    'archive_border'    => array( 'label' => __( 'Archive label border', 'graphene' ) ),
		    'archive_label'     => array( 'label' => __( 'Archive label title colour', 'graphene' ) ),
		    'archive_text'      => array( 'label' => __( 'Archive label text colour', 'graphene' ) ),
		)
	);
	graphene_add_customizer_options( $options, $wp_customize );


	/* =Comments Area
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-colours-comments', array(
		'title'	=> __( 'Comments', 'graphene' ),
		'panel'	=> 'graphene-colours',
	) );

	$options = array(
		'type'		=> 'colour',
		'section'	=> 'graphene-colours-comments',
		'options'	=> array(
            'comments_bg'               => array( 'label' => __( 'Comments background', 'graphene' ) ),
            'comments_border'           => array( 'label' => __( 'Comments border', 'graphene' ) ),
            'comments_box_shadow'       => array( 'label' => __( 'Comments box shadow', 'graphene' ) ),
            'comments_text'             => array( 'label' => __( 'Comments text', 'graphene' ) ),
            'author_comments_border'    => array( 'label' => __( 'Author comments border', 'graphene' ) ),
		)
	);
	graphene_add_customizer_options( $options, $wp_customize );


	/* =Footer
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-colours-footer', array(
		'title'	=> __( 'Footer', 'graphene' ),
		'panel'	=> 'graphene-colours',
	) );

	$options = array(
		'type'		=> 'colour',
		'section'	=> 'graphene-colours-footer',
		'options'	=> array(
            'footer_bg'      		=> array( 'label' => __( 'Background', 'graphene' ) ),
			'footer_text'    		=> array( 'label' => __( 'Normal text', 'graphene' ) ),
			'footer_link'    		=> array( 'label' => __( 'Link text', 'graphene' ) ),
			'footer_widget_bg'      => array( 'label' => __( 'Widget area background', 'graphene' ) ),
			'footer_widget_border'  => array( 'label' => __( 'Widget area border', 'graphene' ) ),
			'footer_widget_text'    => array( 'label' => __( 'Widget area text', 'graphene' ) ),
			'footer_widget_link'    => array( 'label' => __( 'Widget area link text', 'graphene' ) ),
		)
	);
	graphene_add_customizer_options( $options, $wp_customize );


	do_action( 'graphene_customizer_colour_options', $wp_customize );
}