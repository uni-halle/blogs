<?php
/*
 * Theme setup functions. Theme initialization, theme support , widgets , navigation
 *
 * @package tempera
 * @subpackage Functions
 */

// Bringing up Tempera Settings page after install
/*
if ( is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
	wp_redirect( 'themes.php?page=tempera-page' );
}*/

 $temperas['tempera_totalSize'] = $temperas['tempera_sidebar'] + $temperas['tempera_sidewidth'];

 /**

 *
 * @package Cryout Creations
 * @subpackage tempera
 * @since tempera 0.5
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = $temperas['tempera_sidewidth'];

/** Tell WordPress to run tempera_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'tempera_setup' );

if ( ! function_exists( 'tempera_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override tempera_setup() in a child theme, add your own tempera_setup to your child theme's
 * functions.php file.
 */
function tempera_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style( "styles/editor-style.css" );
	
	// Support title tag since WP 4.1
	add_theme_support( 'title-tag' );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 150, 150 ); // default Post Thumbnail dimensions (cropped)

	// WooCommerce compatibility tag
	add_theme_support( 'woocommerce' );
	// WooCommerce 3.0 gallery support 
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );
	add_theme_support('post-formats', array( 'aside', 'chat', 'gallery', 'image', 'link', 'quote', 'status','audio', 'video'));

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'tempera', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );



	// This theme uses wp_nav_menu() in 3 locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'tempera' ),
		'top' => __( 'Top Navigation', 'tempera' ),
		'footer' => __( 'Footer Navigation', 'tempera' ),
	) );

	// This theme allows users to set a custom background
	add_theme_support( 'custom-background' );

	global $temperas;
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'tempera_header_image_width', $temperas['tempera_totalSize'] ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'tempera_header_image_height', (int)$temperas['tempera_hheight']) );
	
	add_image_size( 'header', HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );	

	add_image_size( 'slider', $temperas['tempera_fpsliderwidth'], $temperas['tempera_fpsliderheight'], true );
	add_image_size( 'columns', $temperas['tempera_colimagewidth'], $temperas['tempera_colimageheight'], true );
	
	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See tempera_admin_header_style(), below.
	define( 'NO_HEADER_TEXT', true );
	add_theme_support( 'custom-header' );

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'tempera' => array(
			'url' => '%s/images/headers/tempera.png',
			'thumbnail_url' => '%s/images/headers/tempera_thumbnail.png',
			'description' => __( 'Tempera Default Header Image', 'tempera' )
		),

	) );
}
endif;

// Backwards compatibility for the title-tag
if ( ! function_exists( '_wp_render_title_tag' ) ) :
	add_action( 'wp_head', 'tempera_render_title' );
	add_filter( 'wp_title', 'tempera_filter_wp_title' );
	add_filter('wp_title_rss','tempera_filter_wp_title_rss');
endif;

function tempera_render_title() { ?>
		<title><?php wp_title( '', true, 'right' ); ?></title>
<?php }

function tempera_filter_wp_title( $title ) {
    // Get the Site Name
    $site_name = get_bloginfo( 'name' );
    // Prepend name
    $filtered_title = (((strlen($site_name)>0)&&(strlen($title)>0))?$title.' - '.$site_name:$title.$site_name);
	// Get the Site Description
 	$site_description = get_bloginfo( 'description' );
    // If site front page, append description
    if ( (is_home() || is_front_page()) && $site_description ) {
        // Append Site Description to title
        $filtered_title = ((strlen($site_name)>0)&&(strlen($site_description)>0))?$site_name. " | ".$site_description:$site_name.$site_description;
    }
	// Add pagination if that's the case
	global $page, $paged;
	if ( $paged >= 2 || $page >= 2 )
	$filtered_title .=	 ' | ' . sprintf( __( 'Page %s', 'tempera' ), max( $paged, $page ) );

    // Return the modified title
    return $filtered_title;
}

function tempera_filter_wp_title_rss($title) {
	return ' ';
}

if ( ! function_exists( 'tempera_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in tempera_setup().
 *
 * @since tempera 0.5
 */
function tempera_admin_header_style() {
?>
<style type="text/css">
/* Shows the same border as on front end */
#headimg {
	border-bottom: 1px solid #000;
	border-top: 4px solid #000;
}
/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
	#headimg #name { }
	#headimg #desc { }
*/
</style>
<?php
}
endif;

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since tempera 0.5
 */
function tempera_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'tempera_page_menu_args' );

/**
 * Create menus
 */

// TOP MENU
function tempera_top_menu() {
	if ( has_nav_menu( 'top' ) ) 
	wp_nav_menu( array( 'container' => 'nav', 'container_class' => 'topmenu', 'theme_location' => 'top', 'depth' =>1 ) );
}
add_action ('cryout_topbar_hook','tempera_top_menu');

// MAIN MENU
function tempera_main_menu() { ?>
	<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'tempera' ); ?>">
		<?php _e( 'Skip to content', 'tempera' ); ?>
	</a></div>
	<?php 
	/* Main navigation menu. If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  
	   The menu assiged to the primary position is the one used. 
	   If none is assigned, the menu with the lowest ID is used. */
	wp_nav_menu( array( 'container_class' => 'menu', 'menu_id' =>'prime_nav', 'theme_location' => 'primary', 'link_before' => '<span>', 'link_after' => '</span>' ) );
}
add_action ('cryout_access_hook','tempera_main_menu');

// FOOTER MENU
function tempera_footer_menu() {
	if ( has_nav_menu( 'footer' ) ) 
		wp_nav_menu( array( 'container' => 'nav', 'container_class' => 'footermenu', 'theme_location' => 'footer', 'depth' =>1 ) );
}
add_action ('cryout_footer_hook','tempera_footer_menu',98);


/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override tempera_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since tempera 0.5
 * @uses register_sidebar
 */
function tempera_widgets_init() {	
	global $temperas;
	
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Left Sidebar', 'tempera' ),
		'id' => 'left-widget-area',
		'description' => __( 'Left sidebar', 'tempera' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Right Sidebar', 'tempera' ),
		'id' => 'right-widget-area',
		'description' => __( 'Right sidebar', 'tempera' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 5, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Area', 'tempera' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'First footer area', 'tempera' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 6, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Area', 'tempera' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'Second footer area', 'tempera' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 7, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Area', 'tempera' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer area', 'tempera' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 8, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Fourth Footer Area', 'tempera' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer area', 'tempera' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

		// Area 9, located above the content area. Empty by default.
	register_sidebar( array(
		'name' => __( 'Above Content Area', 'tempera' ),
		'id' => 'above-content-widget-area',
		'description' => __( 'Above Content Area', 'tempera' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

		// Area 10, located below the content area. Empty by default.
	register_sidebar( array(
		'name' => __( 'Below Content Area', 'tempera' ),
		'id' => 'below-content-widget-area',
		'description' => __( 'Below Content Area', 'tempera' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
		// Area 0, located inside the header
	register_sidebar( array(
		'name' => __( 'Header Widgets', 'tempera' ),
		'id' => 'header-widget-area',
		'description' => __( 'Header Widgets', 'tempera' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	// Area 11, the presentation page columns
	register_sidebar( array(
		'name' => __( 'Presentation Page Columns', 'tempera' ),
		'id' => 'presentation-page-columns-area',
		'description' => sprintf(__('Only drag [Cryout Column] widgets here. Recommended size for uploaded images: %1$dpx (width) x %2$dpx (height). Go to the Tempera Settings page >> Presentation Page Settings >> Columns to edit sizes and more.','tempera' ), $temperas['tempera_colimagewidth'],$temperas['tempera_colimageheight']),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
/** Register sidebars by running tempera_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'tempera_widgets_init' );


/**
 * Creates different class names for footer widgets depending on their number.
 * This way they can fit the footer area.
 */

function tempera_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'first-footer-widget-area' ) )
		$count++;

	if ( is_active_sidebar( 'second-footer-widget-area' ) )
		$count++;

	if ( is_active_sidebar( 'third-footer-widget-area' ) )
		$count++;

	if ( is_active_sidebar( 'fourth-footer-widget-area' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
		case '4':
			$class = 'four';
			break;
	}

	if ( $class )
		echo 'class="footer' . $class . '"';
}


function tempera_header_widget() {
	if ( is_active_sidebar( 'header-widget-area' )) { ?>
		<div id="header-widget-area">
			<ul class="yoyo">
				<?php dynamic_sidebar( 'header-widget-area' ); ?>
			</ul>
		</div>
	<?php } 
}
add_action ('cryout_header_widgets_hook','tempera_header_widget');

function tempera_above_widget() {
	if ( is_active_sidebar( 'above-content-widget-area' )) { ?>
		<ul class="yoyo">
			<?php dynamic_sidebar( 'above-content-widget-area' ); ?>
		</ul>
	<?php } 
}
add_action( 'cryout_before_content_hook', 'tempera_above_widget' );

function tempera_below_widget() {
	if ( is_active_sidebar( 'below-content-widget-area' )) { ?>
		<ul class="yoyo">
			<?php dynamic_sidebar( 'below-content-widget-area' ); ?>
		</ul>
	<?php } 
}
add_action( 'cryout_after_content_hook', 'tempera_below_widget' );

// FIN