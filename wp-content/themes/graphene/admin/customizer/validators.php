<?php
/**
 * Validate multiple select field
 *
 * @package Graphene
 * @since 2.0
 */
function graphene_validate_multiple_select( $input ){
	if ( ! is_array( $input ) ) $input = array();
	foreach ( $input as $key => $value ) {
		$input[$key] = sanitize_text_field( $value );
	}
	
	return $input;
}


/**
 * Validate multiple checkboxes
 * 
 * @package Graphene
 * @since 2.0
 */
function graphene_validate_multiple_checkboxes( $input ){
	$multi_values = ! is_array( $input ) ? explode( ',', $input ) : $input;
    return ! empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
}


/**
 * Validate JSON values and convert to array
 * 
 * @package Graphene
 * @since 2.0
 */
function graphene_validate_json( $input ){
	$input = ( ! is_array( $input ) ) ? json_decode( $input, true ) : $input;
    return $input;
}


/**
 * Validate custom css
 * 
 * @package Graphene
 * @since 2.0
 */
function graphene_validate_css( $input ){
	$input = wp_filter_nohtml_kses( wp_strip_all_tags( $input ) );
	return $input;
}


/**
 * Validate floating numbers
 * 
 * @package Graphene
 * @since 2.0
 */
function graphene_validate_numeric( $input ){
	if ( ! is_numeric( $input ) ) $input = '';
	return $input;
}