<?php
/**
 * Graphene Advanced options
 *
 * @package Graphene
 * @since 2.0
 */
function graphene_customizer_advanced_options( $wp_customize ){

	/* =Head Tags
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-advanced-head-tags', array(
		'title'	=> __( 'Custom <head> Tags', 'graphene' ),
		'panel'	=> 'graphene-advanced',
	) );

	$wp_customize->add_control( new Graphene_Code_Control( $wp_customize, 'graphene_settings[head_tags]', array(
		'type' 		=> 'textarea',
		'section' 	=> 'graphene-advanced-head-tags',
		'label' 	=> __( 'Code to insert into the <head> element', 'graphene' ),
		'input_attrs'	=> array(
			'rows'	=> 3,
			'cols'	=> 60
		)
	) ) );


	/* =Action Hooks Widget Areas
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-advanced-widget-hooks', array(
		'title'	=> __( 'Action Hooks Widget Areas', 'graphene' ),
		'panel'	=> 'graphene-advanced',
	) );

	$wp_customize->add_control( new Graphene_Widget_Hooks_Control( $wp_customize, 'graphene_settings[widget_hooks]', array(
		'type' 		=> 'textarea',
		'section' 	=> 'graphene-advanced-widget-hooks',
	) ) );
}