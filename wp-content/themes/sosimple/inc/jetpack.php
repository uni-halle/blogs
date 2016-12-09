<?php
/**
 * Jetpack Compatibility File
 * See: https://jetpack.me/
 *
 * @package SoSimple
 */

/**
 * Add theme support for Infinite Scroll.
 * See: https://jetpack.me/support/infinite-scroll/
 */
function sosimple_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'sosimple_infinite_scroll_render',
		'footer'    => 'page',
	) );
} // end function sosimple_jetpack_setup
add_action( 'after_setup_theme', 'sosimple_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function sosimple_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', get_post_format() );
	}
} // end function sosimple_infinite_scroll_render
