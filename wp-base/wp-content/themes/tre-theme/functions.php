<?php
/**
 * Beta functions and definitions
 *
 * @package Beta
 */

if ( ! function_exists( 'beta_theme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */



function beta_theme_setup() {
	
	/* Load the primary menu. */
	remove_action( 'omega_before_header', 'omega_get_primary_menu' );	
	add_action( 'omega_before_main', 'omega_get_primary_menu' );
	add_filter( 'omega_site_description', 'beta_site_description' );
	add_theme_support( 'omega-footer-widgets', 4 );

}
endif; // beta_theme_setup

add_action( 'after_setup_theme', 'beta_theme_setup', 11 );


function beta_site_description($desc) {
	$desc = "";
	return $desc;
}

/**
 * Enqueue scripts and styles
 */
function beta_scripts() {
	wp_enqueue_style('lato-font', 'http://fonts.googleapis.com/css?family=Ubuntu:400,700');
}

add_action( 'wp_enqueue_scripts', 'beta_scripts' );

function add_favicon (){
	echo '<link rel="shortcut icon" href="wp-content/themes/tre-theme/favicon.ico" />';
}

add_action ( 'wp_head ', 'add_favicon' );


/* add_action ( 'omega_before_main', 'omega_get_primary_menu' ); */


/*
function tre_recent_news() {

if(is_front_page ()) {

echo '<footer class="site-footer"><div class="widget-wrap"><ul><h2>Recent News</h2>';

	$args = array( 'numberposts' => '5' );
	$recent_posts = wp_get_recent_posts( $args );
	foreach( $recent_posts as $recent ){
		echo '<li><a href="' . get_permalink($recent["ID"]) . '" title="Look '.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].'</a> </li> ';
	}

echo '</ul></div></footer>';


}}


add_action ('omega_footer_widget_areas', 'tre_recent_news');
*/




?>