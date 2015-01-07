<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Choco
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
add_action( 'infinite_scroll_render', 'choco_infinite_scroll_render' );
add_action( 'init',                   'choco_infinite_scroll_init' );

/**
 * Add theme support for infinity scroll
 */
function choco_infinite_scroll_init() {
	// Theme support takes one argument: the ID of the element to append new results to.
	add_theme_support( 'infinite-scroll', 'content' );
}

/**
 * Set the code to be rendered on for calling posts,
 * hooked to template parts when possible.
 *
 * Note: must define a loop.
 */
function choco_infinite_scroll_render() {
	while ( have_posts() ) : the_post();
		get_template_part( 'content', get_post_format() );
	endwhile;
}

/**
 * Do we have footer widgets?
 */
function infinite_scroll_has_footer_widgets() {
	return false;
}