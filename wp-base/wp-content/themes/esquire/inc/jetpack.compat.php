<?php
/**
 * Compatibility settings and functions for Jetpack from Automattic
 * See http://jetpack.me/support/infinite-scroll/
 *
 * @package Esquire
 */


/**
 * Add support for Infinite Scroll.
 */
add_theme_support( 'infinite-scroll', array(
	'container'      => 'posts',
	'footer_widgets' => array( 'sidebar-1' ),
	'footer'         => false,
) );