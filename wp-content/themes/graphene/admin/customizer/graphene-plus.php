<?php
/**
 * Add the optional settings
 */
function graphene_plus_options_defaults( $defaults ){

	/* Posts layout */
	$defaults['plus_posts_layout'] = '';
	$defaults['plus_typography'] = '';
	$defaults['plus_mce_buttons'] = '';
	$defaults['plus_amp_appearance'] = '';
	$defaults['plus_amp_social'] = '';
	$defaults['plus_amp_ads'] = '';
	$defaults['plus_credit'] = '';
	$defaults['plus_stacks_appearance'] = '';
	$defaults['plus_stacks_widget_areas'] = '';

	return $defaults;
}
add_filter( 'graphene_defaults', 'graphene_plus_options_defaults' );


/**
 * Add customizer panels
 */
function graphene_plus_customizer_panels( $panels ){
	$panels[45] = array(
		'id'	=> 'graphene-amp',
		'title'	=> __( 'Graphene: Accelerated Mobile Pages (AMP)', 'graphene' )
	);
	$panels[46] = array(
		'id'	=> 'graphene-stacks',
		'title'	=> __( 'Graphene: Page Builder', 'graphene' )
	);

	return $panels;
}
add_filter( 'graphene_customizer_panels', 'graphene_plus_customizer_panels' );


/**
 * Add custom options to Graphene Customizer panels
 */
function graphene_plus_customizer_options( $wp_customize ){

	/* =Slider 
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_control( new Graphene_Plus_Feature_Control( $wp_customize, 'graphene_settings[slider_as_header]', array(
		'section' 	=> 'graphene-general-slider',
		'priority'	=> 9,
		'link'		=> 'https://www.graphene-theme.com/graphene-plus/stand-out-from-the-crowd/',
		'imgurl'	=> GRAPHENE_ROOTURI . '/admin/images/plus-slider-as-header.png'
	) ) );

	/* =Posts layout
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-display-layout', array(
		'title'	=> __( 'Posts layout', 'graphene' ),
		'panel'	=> 'graphene-display',
	) );

	$wp_customize->add_control( new Graphene_Plus_Feature_Control( $wp_customize, 'graphene_settings[plus_posts_layout]', array(
		'section' 	=> 'graphene-display-layout',
		'link'		=> 'https://www.graphene-theme.com/graphene-plus/stand-out-from-the-crowd/',
		'imgurl'	=> GRAPHENE_ROOTURI . '/admin/images/plus-posts-layout.png'
	) ) );

	/* =Typography
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-display-typography', array(
		'title'	=> __( 'Typography', 'graphene' ),
		'panel'	=> 'graphene-display',
	) );

	$wp_customize->add_control( new Graphene_Plus_Feature_Control( $wp_customize, 'graphene_settings[plus_typography]', array(
		'section' 	=> 'graphene-display-typography',
		'link'		=> 'https://www.graphene-theme.com/graphene-plus/stand-out-from-the-crowd/',
		'imgurl'	=> GRAPHENE_ROOTURI . '/admin/images/plus-typography.png'
	) ) );

	/* =WP Editor
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_control( new Graphene_Plus_Feature_Control( $wp_customize, 'graphene_settings[plus_mce_buttons]', array(
		'section' 	=> 'graphene-display-editor',
		'link'		=> 'https://www.graphene-theme.com/graphene-plus/stand-out-from-the-crowd/',
		'imgurl'	=> GRAPHENE_ROOTURI . '/admin/images/plus-mce-buttons.png'
	) ) );

	/* =AMP - Appearance
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-amp-display', array(
		'title'	=> __( 'Appearance', 'graphene' ),
		'panel'	=> 'graphene-amp',
	) );
	$wp_customize->add_control( new Graphene_Plus_Feature_Control( $wp_customize, 'graphene_settings[plus_amp_appearance]', array(
		'section' 	=> 'graphene-amp-display',
		'link'		=> 'https://www.graphene-theme.com/graphene-plus/accelerated-mobile-pages/',
		'imgurl'	=> GRAPHENE_ROOTURI . '/admin/images/plus-amp-appearance.png'
	) ) );

	/* =AMP - Social sharing buttons
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-amp-social', array(
		'title'	=> __( 'Social sharing', 'graphene' ),
		'panel'	=> 'graphene-amp',
	) );
	$wp_customize->add_control( new Graphene_Plus_Feature_Control( $wp_customize, 'graphene_settings[plus_amp_social]', array(
		'section' 	=> 'graphene-amp-social',
		'link'		=> 'https://www.graphene-theme.com/graphene-plus/accelerated-mobile-pages/',
		'imgurl'	=> GRAPHENE_ROOTURI . '/admin/images/plus-amp-social.png'
	) ) );

	/* =AMP - Ads
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-amp-ads', array(
		'title'	=> __( 'Advertisements', 'graphene' ),
		'panel'	=> 'graphene-amp',
	) );
	$wp_customize->add_control( new Graphene_Plus_Feature_Control( $wp_customize, 'graphene_settings[plus_amp_ads]', array(
		'section' 	=> 'graphene-amp-ads',
		'link'		=> 'https://www.graphene-theme.com/graphene-plus/accelerated-mobile-pages/',
		'imgurl'	=> GRAPHENE_ROOTURI . '/admin/images/plus-amp-ads.png'
	) ) );

	/* =Stacks - Appearance
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-stacks-appearance', array(
		'title'	=> __( 'Appearance', 'graphene' ),
		'panel'	=> 'graphene-stacks',
	) );
	$wp_customize->add_control( new Graphene_Plus_Feature_Control( $wp_customize, 'graphene_settings[plus_stacks_appearance]', array(
		'section' 	=> 'graphene-stacks-appearance',
		'link'		=> 'https://www.graphene-theme.com/graphene-plus/stacks-page-builder/',
		'imgurl'	=> GRAPHENE_ROOTURI . '/admin/images/plus-stacks-appearance.png'
	) ) );

	/* =Stacks - Widget Areas
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-stacks-widget-areas', array(
		'title'	=> __( 'Widget Areas', 'graphene' ),
		'panel'	=> 'graphene-stacks',
	) );
	$wp_customize->add_control( new Graphene_Plus_Feature_Control( $wp_customize, 'graphene_settings[plus_stacks_widget_areas]', array(
		'section' 	=> 'graphene-stacks-widget-areas',
		'link'		=> 'https://www.graphene-theme.com/graphene-plus/stacks-page-builder/',
		'imgurl'	=> GRAPHENE_ROOTURI . '/admin/images/plus-stacks-widget-areas.png'
	) ) );

	/* =Disable credit
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_control( new Graphene_Plus_Feature_Control( $wp_customize, 'graphene_settings[plus_credit]', array(
		'section' 	=> 'graphene-general-footer',
		'link'		=> 'https://www.graphene-theme.com/graphene-plus/',
		'imgurl'	=> GRAPHENE_ROOTURI . '/admin/images/plus-credit.png'
	) ) );

}
add_action( 'customize_register', 'graphene_plus_customizer_options' );