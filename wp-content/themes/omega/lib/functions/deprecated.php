<?php
/**
 * Deprecated functions that should be avoided in favor of newer functions. Also handles removed 
 * functions to avoid errors. Developers should not use these functions in their parent themes and users 
 * should not use these functions in their child themes.  The functions below will all be removed at some 
 * point in a future release.  If your theme is using one of these, you should use the listed alternative or 
 * remove it from your theme if necessary.
 */

/**
 * @since 0.9.0
 * @deprecated 0.9.0
 */
function post_format_tools_post_has_content( $id = 0 ) {
	_deprecated_function( __FUNCTION__, '0.9.0', 'omega_post_has_content()' );
	omega_post_has_content( $id );
}

/**
 * @since 0.9.0
 * @deprecated 0.9.0
 */
function post_format_tools_url_grabber() {
	_deprecated_function( __FUNCTION__, '0.9.0', 'omega_get_the_post_format_url()' );
	omega_get_the_post_format_url();
}

/**
 * @since 0.9.0
 * @deprecated 0.9.0
 */
function post_format_tools_get_image_attachment_count() {
	_deprecated_function( __FUNCTION__, '0.9.0', 'omega_get_gallery_image_count()' );
	omega_get_gallery_image_count();
}

/**
 * @since 0.9.0
 * @deprecated 0.9.0
 */
function post_format_tools_get_video( $deprecated = '' ) {
	_deprecated_function( __FUNCTION__, '0.9.0', 'omega_media_grabber()' );
	omega_media_grabber();
}

/**
 * @since      0.7.0
 * @deprecated 0.9.0
 */
function do_atomic( $tag = '', $arg = '' ) {
	//_deprecated_function( __FUNCTION__, '0.9.0', 'omega_do_atomic' );
	omega_do_atomic( $tag, $arg );
}

/**
 * @since      0.7.0
 * @deprecated 0.9.0
 */
function apply_atomic( $tag = '', $value = '' ) {
	_deprecated_function( __FUNCTION__, '0.9.0', 'omega_apply_atomic' );
	return omega_apply_atomic( $tag, $value );
}

/**
 * @since      0.7.0
 * @deprecated 0.9.0
 */
function apply_atomic_shortcode( $tag = '', $value = '' ) {
	_deprecated_function( __FUNCTION__, '0.9.0', 'omega_apply_atomic_shortcode' );
	return omega_apply_atomic_shortcode( $tag, $value );
}


?>