<?php
/**
 * Check if the site has static front page
 *
 * @package Graphene
 * @since 2.0
 */
function graphene_has_static_front_page(){
	if ( 'page' == get_option( 'show_on_front' ) ) return true;
	return false;
}


/**
 * Check if the site has static posts page
 * 
 * @package Graphene
 * @since 2.0
 */
function graphene_has_static_posts_page(){
	if ( get_option( 'page_for_posts' ) ) return true;
	return false;
}