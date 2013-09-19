<?php
load_theme_textdomain( 'magazinetheme', TEMPLATEPATH.'/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH."/languages/$locale.php";
if ( is_readable($locale_file) )
	require_once($locale_file);
	if (function_exists('register_sidebar')) register_sidebar();
/** Tell WordPress to run magazinetheme_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'magazinetheme_setup' );

if ( ! function_exists('magazinetheme_setup') ):
/**
* @uses add_custom_image_header() To add support for a custom header.
* @uses register_default_headers() To register the default custom header images provided with the theme.
*
* @since 3.0.0
*/
function magazinetheme_setup() {


// Your changeable header business starts here
define('HEADER_TEXTCOLOR', '#ededed'); // Default text color
// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
define( 'HEADER_IMAGE', '%s/images/headers/fence.jpg' );

// The height and width of your custom header. You can hook into the theme's own filters to change these values.
// Add a filter to magazinetheme_header_image_width and magazinetheme_header_image_height to change these values.
define( 'HEADER_IMAGE_WIDTH', apply_filters( 'magazinetheme_header_image_width', 832 ) );
define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'magazinetheme_header_image_height', 260 ) );

// We'll be using post thumbnails for custom header images on posts and pages.
// We want them to be 832 pixels wide by 260 pixels tall (larger images will be auto-cropped to fit).
set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

// Don't support text inside the header image.
	define( 'NO_HEADER_TEXT', true );

// Add a way for the custom header to be styled in the admin panel that controls
// custom headers. See magazinetheme_admin_header_style(), below.
add_custom_image_header( '', 'magazinetheme_admin_header_style' );

// … and thus ends the changeable header business.

// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
register_default_headers( array (
'fence' => array (
'url' => '%s/images/headers/fence.jpg',
'thumbnail_url' => '%s/images/headers/fence-thumbnail.jpg',
'description' => __( 'Fence', 'magazinetheme' )
),
'baseball' => array (
'url' => '%s/images/headers/baseball.jpg',
'thumbnail_url' => '%s/images/headers/baseball-thumbnail.jpg',
'description' => __( 'Baseball', 'magazinetheme' )
),
'farm' => array (
'url' => '%s/images/headers/farm.jpg',
'thumbnail_url' => '%s/images/headers/farm-thumbnail.jpg',
'description' => __( 'Farm', 'magazinetheme' )
),
'school' => array (
'url' => '%s/images/headers/school.jpg',
'thumbnail_url' => '%s/images/headers/school-thumbnail.jpg',
'description' => __( 'School', 'magazinetheme' )
),
'school' => array (
'url' => '%s/images/headers/school.jpg',
'thumbnail_url' => '%s/images/headers/school-thumbnail.jpg',
'description' => __( 'School', 'magazinetheme' )
),
'city' => array (
'url' => '%s/images/headers/city.jpg',
'thumbnail_url' => '%s/images/headers/city-thumbnail.jpg',
'description' => __( 'City', 'magazinetheme' )
),
'coffee' => array (
'url' => '%s/images/headers/coffee.jpg',
'thumbnail_url' => '%s/images/headers/coffee-thumbnail.jpg',
'description' => __( 'Coffee', 'magazinetheme' )
),
'waterfall' => array (
'url' => '%s/images/headers/waterfall.jpg',
'thumbnail_url' => '%s/images/headers/waterfall-thumbnail.jpg',
'description' => __( 'Waterfall', 'magazinetheme' )
),
'beach' => array (
'url' => '%s/images/headers/beach.jpg',
'thumbnail_url' => '%s/images/headers/beach-thumbnail.jpg',
'description' => __( 'Beach', 'magazinetheme' )
),
'snow' => array (
'url' => '%s/images/headers/snow.jpg',
'thumbnail_url' => '%s/images/headers/snow-thumbnail.jpg',
'description' => __( 'Snow', 'magazinetheme' )
)
) );
}
endif;

if ( ! function_exists( 'magazinetheme_admin_header_style' ) ) :
/**
* Styles the header image displayed on the Appearance > Header admin panel.
*


* Referenced via add_custom_image_header() in magazinetheme_setup().
*
* @since 3.0.0
*/
function magazinetheme_admin_header_style() {
?>
<style type="text/css">

</style>
<?php
}
endif;

// add menu support and fallback menu if menu doesn't exist
add_action('init', 'sjc_register_menu');
function sjc_register_menu() {
	if (function_exists('register_nav_menu')) {
	
	register_nav_menu('sjc-main-menu', __('Main Menu'));
	}	

}
function sjc_freshink_menu() {
	echo '<div id="greendrop" class="backgreen"><ul>';
	if ('page' != get_option('show_on_front')) {
		echo '<li><a href="'. home_url() . '/">Home</a></li>';
	}
	wp_list_pages('title_li=');
	echo '</ul></div><div class="below"></div>';
}
add_theme_support( 'automatic-feed-links' );

if ( ! isset( $content_width ) )
	$content_width = 500; // Should be equal to the width the theme is designed for

add_custom_background();


/** 
* A pagination function 
* @param integer $range: The range of the slider, works best with even numbers 
* Used WP functions: 
* get_pagenum_link($i) - creates the link, e.g. http://site.com/page/4 
* previous_posts_link(' Previous '); - returns the Previous page link 
* next_posts_link(' Next '); - returns the Next page link 
*/  
function get_pagination($range = 4){  
  // $paged - number of the current page  
  global $paged, $wp_query;  
  // How much pages do we have?  
     $max_page = $wp_query->max_num_pages;  
	 if ( !$max_page ) {  
    $max_page = $wp_query->max_num_pages;  
  }  
  // We need the pagination only if there are more than 1 page  
  if($max_page > 1){  
    if(!$paged){  
      $paged = 1;  
    }  
    // On the first page, don't put the First page link  
   
    // To the previous page  
    previous_posts_link('<<');  
    // We need the sliding effect only if there are more pages than is the sliding range  
    if($max_page > $range){  
      // When closer to the beginning  
      if($paged < $range){  
        for($i = 1; $i <= ($range + 1); $i++){  
          echo "<a href='" . get_pagenum_link($i) ."'";  
          if($i==$paged) echo "class='current'";  
          echo ">$i</a>";  
        }  
      }  
      // When closer to the end  
      elseif($paged >= ($max_page - ceil(($range/2)))){  
        for($i = $max_page - $range; $i <= $max_page; $i++){  
          echo "<a href='" . get_pagenum_link($i) ."'";  
          if($i==$paged) echo "class='current'";  
          echo ">$i</a>";  
        }  
      }  
      // Somewhere in the middle  
      elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){  
        for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){  
          echo "<a href='" . get_pagenum_link($i) ."'";  
          if($i==$paged) echo "class='current'";  
          echo ">$i</a>";  
        }  
      }  
    }  
    // Less pages than the range, no sliding effect needed  
    else{  
      for($i = 1; $i <= $max_page; $i++){  
        echo "<a href='" . get_pagenum_link($i) ."'";  
        if($i==$paged) echo "class='current'";  
        echo ">$i</a>";  
      }  
    }  
    // Next page  
    next_posts_link('>>');  
    // On the last page, don't put the Last page link  
   
  }  
} 

add_editor_style();
?>
