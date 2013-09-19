<?php
/**
 * @package Esquire
 */

if ( function_exists( 'get_the_post_format_url' ) && ! function_exists( 'esquire_url_grabber' ) ) {
/**
 * Return the URL for the first link found in this post.
 *
 * @param string $the_content Post content, falls back to current post content if empty.
 * @return string URL or false when no link is present.
 */
function esquire_url_grabber( $the_content = '' ) {
	if ( empty( $the_content ) )
		$the_content = get_the_content();

	$url = get_the_post_format_url();
	if ( empty( $url ) )
		$url = get_content_url( $the_content );

	return esc_url_raw( $url );
}
} // if ( function_exists( 'get_the_post_format_url' ) )

if ( function_exists( 'get_the_post_format_image' ) && ! function_exists( 'esquire_image_grabber' ) ) {
/**
 * Return the HTML output for first image found for a post.
 *
 * @param int $post_id ID for parent post
 * @param string $the_content
 * @param string $before Optional before string
 * @param string $after Optional after string
 * @return string HTML output or false if no match
 */
function esquire_image_grabber( $post_id, $the_content = '', $before = '', $after = '' ) {
	preg_match( WPCOM_THEMES_IMAGE_REGEX, get_the_post_format_image(), $matches );
	$image = isset( $matches[0] ) ? $matches[0] : '';

	// Add wrapper markup, if specified
	if ( ! empty( $before ) )
		$image = $before . $image;
	if ( ! empty( $after ) )
		$image = $image . $after;

	return $image;
}
} // if ( function_exists( 'get_the_post_format_image' ) )

if ( function_exists( 'get_the_post_format_media' ) && ! function_exists( 'esquire_audio_grabber' ) ) {
/**
 * Return the first audio file found for a post.
 *
 * @param int $post_id ID for parent post.
 * @return string Path to audio file.
 */
function esquire_audio_grabber( $post_id ) {
	$null = null;
	preg_match( '/src=[\'"](.+?)[\'"]/is', get_the_post_format_media( 'audio', $null, 1 ), $matches );

	return isset( $matches[1] ) ? esc_url( $matches[1] ) : '';
}
} // if ( function_exists( 'get_the_post_format_media' ) )

// Define common regex lookup patterns
if ( ! defined( 'WPCOM_THEMES_IMAGE_REGEX' ) )
	define( 'WPCOM_THEMES_IMAGE_REGEX', '/(<img.+src=[\'"]([^\'"]+)[\'"].*?>)/i' );
if ( ! defined( 'WPCOM_THEMES_IMAGE_REPLACE_REGEX' ) )
	define( 'WPCOM_THEMES_IMAGE_REPLACE_REGEX', '/\[caption.*\[\/caption\]|<img[^>]+./' );

if ( ! function_exists( 'esquire_url_grabber' ) ) {
/**
 * Return the URL for the first link found in this post.
 *
 * @param string $the_content Post content, falls back to current post content if empty.
 * @return boolean|string URL or false when no link is present.
 */
function esquire_url_grabber( $the_content = '' ) {
	if ( empty( $the_content ) )
		$the_content = get_the_content();
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', $the_content, $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}
} // if ( ! function_exists( 'esquire_url_grabber' ) )

if ( ! function_exists( 'esquire_image_grabber' ) ) {
/**
 * Return the HTML output for first image found for a post.
 *
 * @param int $post_id ID for parent post.
 * @param string $the_content
 * @param string $before Optional before string.
 * @param string $after Optional after string.
 * @return boolean|string HTML output or false if no match.
 */
function esquire_image_grabber( $post_id, $the_content = '', $before = '', $after = '' ) {
	global $wpdb;
	$image_src = '';
	if ( empty( $the_content ) )
		$the_content = get_the_content();

	$first_image = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_parent = %d AND post_type = 'attachment' AND INSTR(post_mime_type, 'image') ORDER BY menu_order ASC LIMIT 0,1", (int) $post_id ) );

	if ( ! empty( $first_image ) ) {
		// We have an attachment, so just use its data.
		$image_src = wp_get_attachment_image( $first_image, 'image' );
	} else {
		// Try to get the image for the linked image (not attached)
		$output = preg_match( WPCOM_THEMES_IMAGE_REGEX, $the_content, $matches );
		if ( isset( $matches[0] ) )
			$image_src = $matches[0];
	}

	if ( ! empty( $image_src ) ) {
		// Add wrapper markup, if specified
		if ( ! empty( $before ) )
			$image_src = $before . $image_src;
		if ( ! empty( $after ) )
			$image_src = $image_src . $after;

		return $image_src;
	}

	return false;
}
} // if ( ! function_exists( 'esquire_image_grabber' ) )

if ( ! function_exists( 'esquire_audio_grabber' ) ) {
/**
 * Return the first audio file found for a post.
 *
 * @param int $post_id ID for parent post.
 * @return boolean|string Path to audio file.
 */
function esquire_audio_grabber( $post_id ) {
	global $wpdb;

	$first_audio = $wpdb->get_var( $wpdb->prepare( "SELECT guid FROM $wpdb->posts WHERE post_parent = %d AND post_type = 'attachment' AND INSTR(post_mime_type, 'audio') ORDER BY menu_order ASC LIMIT 0,1", (int) $post_id ) );

	if ( ! empty( $first_audio ) )
		return $first_audio;

	return false;
}
} // if ( ! function_exists( 'esquire_audio_grabber' ) )
