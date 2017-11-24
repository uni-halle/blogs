<?php
/**
 * Graphene Utilities
 *
 * @package Graphene
 * @since 2.0
 */
function graphene_customizer_utilities( $wp_customize ){ 

	/* =Reset settings
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-utilities-reset', array(
	  'title' 		=> __( 'Reset settings', 'graphene' ),
	  'panel'		=> 'graphene-utilities',
	) );

	$wp_customize->add_control( new Graphene_Reset_Settings( $wp_customize, 'graphene_settings[reset_settings]', array(
		'section'	=> 'graphene-utilities-reset',
		'label' 	=> __( 'Reset theme settings', 'graphene' ),
	) ) );


	/* =Import and export settings
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-utilities-import-export', array(
	  'title' 		=> __( 'Import & export settings', 'graphene' ),
	  'panel'		=> 'graphene-utilities',
	) );

	$wp_customize->add_control( new Graphene_Import_Settings( $wp_customize, 'graphene_settings[import_settings]', array(
		'section'   	=> 'graphene-utilities-import-export',
		'label'     	=> __( 'Import settings', 'graphene' ),
		'description'	=> __( 'Import Graphene settings to replace all current Graphene settings on this website.', 'graphene' ),
	) ) );

	$wp_customize->add_control( new Graphene_Export_Settings( $wp_customize, 'graphene_settings[export_settings]', array(
		'section'		=> 'graphene-utilities-import-export',
		'label' 		=> __( 'Export settings', 'graphene' ),
		'description'	=> __( 'Export Graphene settings so that you can import them later on as a backup, or replicate settings on other websites.', 'graphene' ),
	) ) );


	/* =Export Graphene 1.9 settings
	--------------------------------------------------------------------------------------*/
	$wp_customize->add_section( 'graphene-utilities-legacy', array(
	  'title' 		=> __( 'Legacy settings', 'graphene' ),
	  'panel'		=> 'graphene-utilities',
	) );

	$wp_customize->add_control( new Graphene_Export_Settings( $wp_customize, 'graphene_settings[export_legacy_settings]', array(
		'section'   	=> 'graphene-utilities-legacy',
		'type'			=> 'legacy',
		'label'     	=> __( 'Export legacy Graphene settings', 'graphene' ),
		'description'	=> __( 'Export legacy Graphene settings in case you need to roll back from Graphene 2.0.', 'graphene' ),
	) ) );

}