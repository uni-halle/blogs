<?php


// parent-style import; current best practice
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', PHP_INT_MAX);

function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' ) );
    wp_enqueue_script('jquery');
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
add_filter('body_class', 'blacklist_body_class', 20, 2);
function blacklist_body_class($wp_classes, $extra_classes) {
if( is_single() || is_page() ) :
// List of the classes to remove from the WP generated classes
$blacklist = array('singular');
// Filter the body classes
  foreach( $blacklist as $val ) {
    if (!in_array($val, $wp_classes)) : continue;
    else:
      foreach($wp_classes as $key => $value) {
      if ($value == $val) unset($wp_classes[$key]);
      }
    endif;
  }
endif;   // Add the extra classes back untouched
return array_merge($wp_classes, (array) $extra_classes);
}


add_action( 'widgets_init', 'theme_slug_widgets_init' );
function theme_slug_widgets_init() {
	register_sidebar( array(
			'id' => 'ulb_green',
			'name' => __( 'ULB gruen' ),
			'description' => __( 'The right sidebar' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
}


/*
	2018_05_08 HenryG.
	PageTopWidget for languages
		
*/
add_action( 'widgets_init', 'theme_top_widget_init' );
function theme_top_widget_init() {
	register_sidebar( array(
		'id' => 'top_widget',
		'name' => __( 'PageTopWidget' ),
		'description' => __( 'Widget on top of the page above branding' ),
		'before_widget' => '<div id="%1$s" class="widget widget-area page-top-widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
		)
	);
}

/*
	Remove autoformat in editor
*/

// remove_filter( 'the_content', 'wpautop' );
/*

*/

function add_button_tile( $atts, $content = null) {
return <<<FORMULAR

     <div class="lebouton">$content</div>
FORMULAR;
}
add_shortcode('lebouton', 'add_button_tile');
/*function add_anfahrt() {
return <<<FORMULAR

     <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2484.057875232845!2d11.961391515841033!3d51.49380541951797!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47a67caa8db31243%3A0xa3c48bf1957328a2!2sStadtbezirk+Nord%2C+M%C3%BChlweg+15%2C+06114+Halle+(Saale)!5e0!3m2!1sde!2sde!4v1467013128528" width="400" height="400" frameborder="0" style="border:0"></iframe>
FORMULAR;	
}
add_shortcode('anfahrt', 'add_anfahrt');*/

/*
	2017_12_18 HenryG.
	Enable categories and tags for pages

	http://spicemailer.com/wordpress/add-categories-tags-pages-wordpress/ 
*/
function add_taxonomies_to_pages() {
 register_taxonomy_for_object_type( 'post_tag', 'page' );
 register_taxonomy_for_object_type( 'category', 'page' );
 }
add_action( 'init', 'add_taxonomies_to_pages' );

/*
	***
*/


/*
	2018_01_03 HenryG.
	SVG Support

	https://www.sitepoint.com/wordpress-svg/ 
*/

function add_file_types_to_uploads($file_types){

    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $file_types = array_merge($file_types, $new_filetypes );

    return $file_types;
}
add_action('upload_mimes', 'add_file_types_to_uploads');
/*
	***
*/


/*
	2018_06_18 HenryG.
	Excerpt for pages

	https://blog.kulturbanause.de/2013/07/wordpress-auszug-the_excerpt-fur-statische-seiten-aktivieren/
*/

add_action( 'init', 'enable_page_excerpts' );

function enable_page_excerpts() 
{
  add_post_type_support( 'page', 'excerpt' );
}
/*
	***
*/



/*
	2018_01_03 HenryG.
	SVG Support in costum header
	(This enables to skip the crop image function)

	https://wordpress.stackexchange.com/questions/207442/how-to-use-a-svg-as-custom-header
	function hikeitbaby_custom_header_setup()

*/

function menalib_custom_header_setup() {
    add_theme_support( 'custom-header', apply_filters( 'menalib_custom_header_args', array(
        'default-image'          => '',
        'default-text-color'     => 'FFFFFF',
        'width'                  => 886,
        'height'                 => 120,
        'flex-width'             => true,
        'flex-height'            => true,
        'wp-head-callback'       => 'menalib_header_style',
        'admin-head-callback'    => 'menalib_admin_header_style',
        'admin-preview-callback' => 'menalib_admin_header_image',
    ) ) );
}
add_action( 'after_setup_theme', 'menalib_custom_header_setup' );









/*
	2018_01_24 HenryG.
	display pages as posts



add_filter( 'pre_get_posts', 'my_get_posts' );
 function my_get_posts( $query ) {
 if ( is_home() && false == $query->query_vars['suppress_filters'] )
 $query->set( 'post_type', array( 'post', 'page') );
 return $query;
 }

 */

  /**
 * Include Shortcode für Hal:Lit Discovery Suche
 */


require_once( get_stylesheet_directory() . '/inc/discovery-search.php');

require_once( get_stylesheet_directory() . '/inc/opac-search.php');
require_once( get_stylesheet_directory() . '/inc/almanhal-search.php');
require_once( get_stylesheet_directory() . '/inc/menadoc-search.php');

require_once( get_stylesheet_directory() . '/inc/calendar-calculator.php');

require_once( get_stylesheet_directory() . '/inc/get-excerpt-by-id.php');
require_once( get_stylesheet_directory() . '/inc/get-short-post-preview-by-id.php');
require_once( get_stylesheet_directory() . '/inc/list-subpages.php');

require_once( get_stylesheet_directory() . '/inc/password-protected-posts.php');

require_once( get_stylesheet_directory() . '/inc/iframe-shortcode.php');



