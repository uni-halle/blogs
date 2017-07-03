<?php
/**
 * @package Catch Themes
 * @subpackage Catch Box
 * @since Catch Box 3.6.7
 */


if ( ! function_exists( 'catchbox_is_feed_url_present' ) ) :
	/**
	* Return true if feed url is present
	*
	* @since Catch Box 3.6.7
	*/
	function catchbox_is_feed_url_present( $control ) {
		$options = catchbox_get_options();

		return ( isset( $options['feed_url'] ) && '' != $options['feed_url'] );
	}
endif;


if ( ! function_exists( 'catchbox_is_header_code_present' ) ) :
	/**
	* Return true if header code is present
	*
	* @since Catch Box 3.6.7
	*/
	function catchbox_is_header_code_present( $control ) {
		$options = catchbox_get_options();

		return ( isset( $options['tracker_header'] ) && '' != $options['tracker_header'] );
	}
endif;


if ( ! function_exists( 'catchbox_is_footer_code_present' ) ) :
	/**
	* Return true if footer code is present
	*
	* @since Catch Box 3.6.7
	*/
	function catchbox_is_footer_code_present( $control ) {
		$options = catchbox_get_options();

		return ( isset( $options['tracker_footer'] ) && '' != $options['tracker_footer'] );
	}
endif;