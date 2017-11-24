<?php
/**
 * Graphene Display options
 *
 * @package Graphene
 * @since 2.0
 */
function graphene_customizer_display_options( $wp_customize ){

	/* =Columns Layout
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-display-columns-layout', array(
		'title'	=> __( 'Columns Layout', 'graphene' ),
		'panel'	=> 'graphene-display',
	) );

	$wp_customize->add_control( 'graphene_settings[container_style]', array(
		'type' 	=> 'radio',
		'section' => 'graphene-display-columns-layout',
		'label' 	=> __( 'Container style', 'graphene' ),
		'choices'	=> array(
			'boxed'			=> __( 'Boxed', 'graphene' ),
			'full-width'	=> __( 'Full width', 'graphene' ),
		),
	) );

	$wp_customize->add_control( new Graphene_Radio_HTML_Control( $wp_customize, 'graphene_settings[column_mode]', array(
		'type' 		=> 'radio',
		'section' 	=> 'graphene-display-columns-layout',
		'label' 	=> __( 'Layout', 'graphene' ),
		'choices'	=> array(
			'one_column'		=> sprintf( '<img src="%1$s/admin/images/template-onecolumn.png" alt="%2$s" title="%2$s" />', GRAPHENE_ROOTURI, __( 'One column', 'graphene' ) ),
			'two_col_left'		=> sprintf( '<img src="%1$s/admin/images/template-twocolumnsleft.png" alt="%2$s" title="%2$s" />', GRAPHENE_ROOTURI, __( 'Two columns, right sidebar', 'graphene' ) ),
			'two_col_right'		=> sprintf( '<img src="%1$s/admin/images/template-twocolumnsright.png" alt="%2$s" title="%2$s" />', GRAPHENE_ROOTURI, __( 'Two columns, left sidebar', 'graphene' ) ),
			'three_col_left'	=> sprintf( '<img src="%1$s/admin/images/template-threecolumnsleft.png" alt="%2$s" title="%2$s" />', GRAPHENE_ROOTURI, __( 'Three columns, right sidebars', 'graphene' ) ),
			'three_col_right'	=> sprintf( '<img src="%1$s/admin/images/template-threecolumnsright.png" alt="%2$s" title="%2$s" />', GRAPHENE_ROOTURI, __( 'Three columns, left sidebars', 'graphene' ) ),
			'three_col_center'	=> sprintf( '<img src="%1$s/admin/images/template-threecolumnscenter.png" alt="%2$s" title="%2$s" />', GRAPHENE_ROOTURI, __( 'Three columns, left & right sidebars', 'graphene' ) ),
		),
	) ) );


	/* =Columns Widths
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-display-columns-width', array(
		'title'	=> __( 'Columns Width', 'graphene' ),
		'panel'	=> 'graphene-display',
		'description'	=> __( 'Leave values empty to reset to the default values.', 'graphene' ),
	) );

	$wp_customize->add_control( new Graphene_Columns_Width_Control( $wp_customize, 'graphene_settings[container_width]', array(
		'section' 	=> 'graphene-display-columns-width',
	) ) );

	$wp_customize->add_control( new Graphene_Columns_Width_Control( $wp_customize, 'graphene_settings[column_width]', array(
		'section' 	=> 'graphene-display-columns-width',
	) ) );


	/* =Post Elements
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-display-posts', array(
		'title'	=> __( 'Posts Elements', 'graphene' ),
		'panel'	=> 'graphene-display',
	) );

	$wp_customize->add_control( 'graphene_settings[hide_post_author]', array(
		'type' 		=> 'checkbox',
		'section' 	=> 'graphene-display-posts',
		'label' 	=> __( 'Hide post author', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[hide_post_cat]', array(
		'type' 		=> 'checkbox',
		'section' 	=> 'graphene-display-posts',
		'label' 	=> __( 'Hide post categories', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[hide_post_tags]', array(
		'type' 		=> 'checkbox',
		'section' 	=> 'graphene-display-posts',
		'label' 	=> __( 'Hide post tags', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[hide_post_commentcount]', array(
		'type' 		=> 'checkbox',
		'section' 	=> 'graphene-display-posts',
		'label' 	=> __( 'Hide post comment count', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[hide_author_bio]', array(
		'type' 		=> 'checkbox',
		'section' 	=> 'graphene-display-posts',
		'label' 	=> __( 'Hide author bio', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[post_date_display]', array(
		'type' 		=> 'radio',
		'section' 	=> 'graphene-display-posts',
		'label' 	=> __( 'Post date display', 'graphene' ),
		'choices'	=> array(
			'hidden'		=> __( 'Hidden', 'graphene'),
			'icon_no_year'	=> __( 'Icon', 'graphene'),
			'icon_plus_year'=> __( 'Icon with year', 'graphene'),
			'text'			=> __( 'Inline text', 'graphene'),
		)
	) );


	/* =Excerpts
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-display-excerpts', array(
		'title'	=> __( 'Excerpts', 'graphene' ),
		'panel'	=> 'graphene-display',
	) );

	$wp_customize->add_control( 'graphene_settings[posts_show_excerpt]', array(
		'type' 		=> 'checkbox',
		'section' 	=> 'graphene-display-excerpts',
		'label' 	=> __( 'Show excerpts in Front Page', 'graphene' ),
	) );

	$wp_customize->add_control( 'graphene_settings[archive_full_content]', array(
		'type' 		=> 'checkbox',
		'section' 	=> 'graphene-display-excerpts',
		'label' 	=> __( 'Show full content in archive pages', 'graphene' ),
	) );
	
	$wp_customize->add_control( 'graphene_settings[show_excerpt_more]', array(
		'type' 		=> 'checkbox',
		'section' 	=> 'graphene-display-excerpts',
		'label' 	=> __( 'Show More link for manual excerpts', 'graphene' ),
	) );

	$wp_customize->add_control( new Graphene_Code_Control( $wp_customize, 'graphene_settings[excerpt_html_tags]', array(
		'type' 		=> 'textarea',
		'section' 	=> 'graphene-display-excerpts',
		'label' 	=> __( 'Keep these HTML tags in excerpts', 'graphene' ),
		'description'	=> __( "Enter the HTML tags you'd like to retain in excerpts. For example, enter <code>&lt;p&gt;&lt;ul&gt;&lt;li&gt;</code> to retain <code>&lt;p&gt;</code>, <code>&lt;ul&gt;</code>, and <code>&lt;li&gt;</code> HTML tags.", 'graphene' ),
		'input_attrs'	=> array(
			'rows'	=> 1,
			'cols'	=> 60
		)
	) ) );

	
	/* =Miscellaneous
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-display-misc', array(
		'title'	=> __( 'Miscellaneous', 'graphene' ),
		'panel'	=> 'graphene-display',
	) );

	$options = array(
		'section'	=> 'graphene-display-misc',
		'type'		=> 'checkbox',
		'options'	=> array(
			'disable_editor_style'	=> array( 'label' => __( 'Disable custom editor styles', 'graphene' ) ),
		)
	);
	graphene_add_customizer_options( $options, $wp_customize );

}