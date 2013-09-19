<?php

/** 
*
* Liquorice Theme functions
*
**/
/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width  = '670';


/**
* Add Menu Support
**/

add_theme_support('menus');
add_theme_support('automatic-feed-links');
register_nav_menu('main', 'Main Nav');

/**
* Add custom background	with custom background colour fix 
* as shown here: http://devpress.com/blog/custom-background-fix-for-theme-developers/
**/


function liquorice_setup_theme() {


	// Custom backgrounds support
	$args = array(
		'default' => 'F3ECE2',
		'default-image' => get_template_directory_uri() . '/images/bg-paper.jpg',
	);

	$args = apply_filters( 'liquorice_custom_background_args', $args );

	if ( function_exists( 'wp_get_theme' ) ) {
		add_theme_support( 'custom-background', $args );
	} else {
		define( 'BACKGROUND_COLOR', $args['default-color'] );
		define( 'BACKGROUND_IMAGE', $args['default-image'] );
		add_custom_background();
	}


}

add_action( 'after_setup_theme', 'liquorice_setup_theme' );

/**
* Add editor style
**/
add_editor_style();


/**
* Thumbnail support
**/
add_theme_support( 'post-thumbnails' );  
set_post_thumbnail_size( 670, 370, true ); // 670 pixels wide by ??? pixels tall, hard crop mode
// Permalink thumbnail size

if ( is_singular() ) wp_enqueue_script( "comment-reply" ); 


/**
* register_sidebar()
*
*@desc Registers the markup to display in and around a widget
*/
if ( function_exists('register_sidebar') )
{
  register_sidebar(array(
    'before_widget' => '<li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li>',
    'before_title' => '',
    'after_title' => '',
  ));
}

/**
* Check to see if this page will paginate
* 
* @return boolean
*/
function will_paginate() 
{
  global $wp_query;
  
  if ( !is_singular() ) 
  {
    $max_num_pages = $wp_query->max_num_pages;
    
    if ( $max_num_pages > 1 ) 
    {
      return true;
    }
  }
  return false;
}

/**
* Load the Theme Options Page that lets users control the social media icons at the top
*/
require_once ( get_template_directory() . '/inc/theme-options.php' );

?>