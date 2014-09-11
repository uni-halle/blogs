<?php
/**
 *  Maja theme functions and definitions
 */
 
// ==============================================
// 			CONTENT WIDTH
// ==============================================  

if ( ! isset( $content_width ) ) $content_width = 435;
	
// ==============================================
// 			DEFINE THEME DIRECTORY
// ============================================== 

define('MAJA_THEME_DIR', get_template_directory_uri());

// ==============================================
//		THEME SETTINGS 
// ==============================================
			
function maja_get_global_options(){
	
	$maja_option = array();
	$maja_option = get_option('maja_options');
	
return $maja_option;
}

$maja_option = maja_get_global_options();

// ==============================================
// 			ENQUEUE SCRIPTS & STYLES
// ============================================== 
  
function maja_scripts() {
	if ( !is_admin() ) {
		// enqueue jQuery first
		wp_enqueue_script( 'jquery' );
		
		// enqueue other scripts and styles
		wp_enqueue_script('maja_modernizr', MAJA_THEME_DIR . '/lib/js/modernizr.custom.js');
		wp_enqueue_script('maja_easing', MAJA_THEME_DIR . '/lib/js/jquery.easing.1.3.js', array('jquery'));
		wp_enqueue_script('maja_tools', MAJA_THEME_DIR . '/lib/js/jquery.tools.min.js', array('jquery'));
		wp_enqueue_script('maja_cookie', MAJA_THEME_DIR . '/lib/js/jquery.cookie.js', array('jquery'));
		wp_enqueue_script('maja_hover', MAJA_THEME_DIR . '/lib/js/hoverIntent.js', array('jquery'));
		wp_enqueue_script('maja_accordion', MAJA_THEME_DIR . '/lib/js/dcjqaccordion.js', array('jquery'));
		wp_enqueue_script('maja_mixed', MAJA_THEME_DIR . '/lib/js/mixed.js', array('jquery'));
		//wp_enqueue_script('maja_styleswitch', MAJA_THEME_DIR . '/lib/js/styleswitch.js', array('jquery'));
		wp_enqueue_script('maja_lightbox', MAJA_THEME_DIR . '/lib/js/jquery.prettyPhoto.js', array('jquery'));
		wp_enqueue_style('maja_lightbox_style', MAJA_THEME_DIR . '/lib/css/prettyPhoto.css');
		
		$maja_option = maja_get_global_options();
		
		if($maja_option['maja_slider'] !='') {
			wp_enqueue_script('maja_flex_slider', MAJA_THEME_DIR . '/lib/js/jquery.flexslider-min.js', array('jquery'));	
			wp_enqueue_style('maja_flex_slider_style', MAJA_THEME_DIR . '/lib/css/flexslider.css');		
		}		
		
		if($maja_option['maja_quicksand'] !='') {
			wp_enqueue_script('maja_quicksand', MAJA_THEME_DIR . '/lib/js/jquery.quicksand.js', array('jquery'));
		}
		
		if ($maja_option['maja_map-check'] !='') {
			wp_enqueue_script('maja_map', 'http://maps.google.com/maps/api/js?sensor=false');	
		}
				
		// enqueue when on single blog post
		if ( is_single() ) wp_enqueue_script( "comment-reply" ); }}
		
add_action('template_redirect', 'maja_scripts');

// ==============================================
// 			 ADDING THEME SUPORT
// ==============================================

// wp3 menu
add_theme_support( 'menus' );

// RSS feed
add_theme_support( 'automatic-feed-links' );
		
// thumbnails
if(function_exists('register_nav_menu')):
	add_theme_support( 'post-thumbnails' );
	
	// regular thumbnail size
	set_post_thumbnail_size( 140, 140, true );
	
	//nivo slider images
	add_image_size('slider-thumbnail', 680, 260, true);
	
	// thumbnail size inside the 2col portfolio	
	add_image_size('portfolio-thumbnail-2col', 343, 200, true);	
		
	// thumbnail size inside the 3col portfolio
	add_image_size('portfolio-thumbnail-3col', 213, 135, true);
	
	// thumbnail size inside the 4col portfolio	
	add_image_size('portfolio-thumbnail-4col', 148, 135, true);	
		
	// featured blog image
	add_image_size('blog-thumbnail', 215, 135, true);
		
endif;

// ==============================================
// 			INCLUDING PHP FILES
// ==============================================

// theme settings 
if(is_admin()) {	
	require_once('lib/maja-theme-settings.php');
}	

//fonts
include 'lib/fonts.php';

// shortcodes
include 'lib/shortcodes.php';

// reusable meta boxes
include 'lib/metaboxes-slider.php';
include 'lib/metaboxes-portfolio.php';

// blog comments
include 'lib/blog-comments.php';

// shortcodes cleanup
include 'lib/cleanup.php';

// custom post type for portfolio
include 'lib/post-types-portfolio.php';

// custom post type for nivo slider
include 'lib/post-types-slider.php';

// search filter
include 'lib/search-filter.php';

// TinyMCE buttons
include 'lib/TinyMCE.php';

// theme localisation
include 'lib/localisation.php';

// ==============================================
// 			 REGISTERING
// ==============================================

// sidebar
if(function_exists('register_sidebar')) {
	register_sidebar(array('name' => 'Maja'));
}

// navigation menu
if(function_exists('register_nav_menu')):
	register_nav_menu( 'primary_nav', 'Primary Navigation');
endif;

add_editor_style('custom-editor-style.css');
// Add the Style selectbox to the second row of MCE buttons
function my_mce_buttons_2($buttons)
{
    array_unshift($buttons, 'styleselect');
    return $buttons;
}
add_filter('mce_buttons_2', 'my_mce_buttons_2');

//Here's how I add the styles (it works when I uncomment the ):

//Define the actual styles that will be in the box
function my_mce_before_init($init_array)
{
    // add classes using a ; separated values
    //$init_array['theme_advanced_styles'] = "Section Head=section-head;Sub Section Head=sub-section-head";

    $temp_array['style_formats'] = array(
        array(
            'title' => 'Box(grau)',
            'block' => 'div',
            'classes' => 'news_container',
			'wrapper' => true
        ),
 array(
            'title' => 'Überschrift 1',
            'block' => 'h1'
        )
,
 array(
            'title' => 'Überschrift 2',
            'block' => 'h2'
        )  
,
 array(
            'title' => 'Überschrift 3',
            'block' => 'h3'
        )  		
    );

    $styles_array = json_encode( $temp_array['style_formats'] );

            //  THIS IS THE PROBLEM !!!! READ BELOW
    $init_array['style_formats'] = $styles_array;

    return $init_array;
}
add_filter('tiny_mce_before_init', 'my_mce_before_init');
?>