<?php
/**
 * Muhlenberg Center functions and definitions
 *
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if (!isset($content_width)) {
	$content_width = 660;
}

/**
 * Muhlenberg Center only works in WordPress 4.1 or later.
 */
if (version_compare($GLOBALS['wp_version'], '4.1-alpha', '<')) {
	require get_template_directory() . '/inc/back-compat.php';
}

if (!function_exists('muhlenbergcenter_setup')) :
function muhlenbergcenter_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain('muhlenbergcenter', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	// Let WordPress manage the document title.
	add_theme_support('title-tag');

	// Enable support for post thumbnails on posts and pages.
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(825, 510, true);

	// Register a primary menu using wp_nav_menu().
	register_nav_menus([
		'primary' => __('Primary Menu', 'muhlenbergcenter'),
	]);

	// Switch default core markup to output valid HTML5.
	add_theme_support('html5', [
		'search-form', 'gallery', 'caption'
	]);

	// Enable support for post formats.
	add_theme_support('post-formats', [
		'video', 'gallery'
	]);

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width.
	 */
	add_editor_style(['css/editor-style.css']);

    // Clean up the wp_head action.
    // remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'rsd_link'); 
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
}
endif;
add_action('after_setup_theme', 'muhlenbergcenter_setup');

/**
 * Register widget areas.
 */
function muhlenbergcenter_widgets_init() {
	register_sidebar([
		'name'          => __('First footer column', 'muhlenbergcenter'),
		'id'            => 'sidebar-footer-1',
		'description'   => __('Contains search field that appears in the first footer column.', 'muhlenbergcenter'),
		'before_widget' => '<div id="%1$s" class="medium-3 small-12 columns">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="visuallyhidden">',
		'after_title'   => '</h3>',
	]);
	register_sidebar([
		'name'          => __('Second footer column', 'muhlenbergcenter'),
		'id'            => 'sidebar-footer-2',
		'description'   => __('Contains the first part of navigation that appears in the second footer column.', 'muhlenbergcenter'),
		'before_widget' => '<div id="%1$s" class="medium-2 small-6 columns">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="visuallyhidden">',
		'after_title'   => '</h3>',
	]);
	register_sidebar([
		'name'          => __('Third footer column', 'muhlenbergcenter'),
		'id'            => 'sidebar-footer-3',
		'description'   => __('Contains the second part of navigation that appears in the third footer column.', 'muhlenbergcenter'),
		'before_widget' => '<div id="%1$s" class="medium-3 small-6 columns">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="visuallyhidden">',
		'after_title'   => '</h3>',
	]);
}
add_action('widgets_init', 'muhlenbergcenter_widgets_init');

/**
 * Change default theme stylesheet uri
 */
function muhlenbergcenter_stylesheet_uri($stylesheet_uri, $stylesheet_dir_uri){
    return $stylesheet_dir_uri.'/css/main.min.css';
}
add_filter('stylesheet_uri', 'muhlenbergcenter_stylesheet_uri', 10, 2);

/**
 * Add a parent class for menu items.
 */
add_filter('wp_nav_menu_objects', 'add_menu_parent_class');
function add_menu_parent_class($items) {
    
    $parents = array();
    foreach ($items as $item) {
        if ($item->menu_item_parent && $item->menu_item_parent > 0) {
            $parents[] = $item->menu_item_parent;
        }
    }
    
    foreach ($items as $item) {
        if (in_array($item->ID, $parents)) {
            $item->classes[] = 'has-dropdown'; 
        }
    }
    
    return $items;
}

/**
 * Change class name for sub menus.
 */
class muhlenbergcenter_Walker_Nav_Menu extends Walker_Nav_Menu {
  function start_lvl(&$output, $depth) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"dropdown\">\n";
  }
}

/**
 * Create custom markup for image galleries.
 */
function blockgrid_gallery($string, $attr){

    $output = '<ul class="medium-block-grid-4  small-block-grid-2  clearing-thumbs  thumbnail-gallery" data-clearing>';
    $posts = get_posts([
        'include'   => $attr['ids'],
        'post_type' => 'attachment',
        'orderby'   => 'post__in'
    ]);

    foreach($posts as $imagePost){
        $image_attributes = wp_get_attachment_image_src($imagePost->ID, array(285,175));
        $output .= '<li>
            <a href="' . wp_get_attachment_url($imagePost->ID) . '">
                <img src="' . $image_attributes[0] . '" data-caption="' . $imagePost->post_title . '" alt="' . $imagePost->post_title . '">
            </a>
        </li>';
    }

    $output .= '</ul>';

    return $output;
}
add_filter('post_gallery', 'blockgrid_gallery', 10, 2);

/**
 * Enqueue scripts and styles.
 */
function muhlenbergcenter_scripts() {

    // Remove jQuery crab in front-end
    if (!is_admin()) {
        wp_dequeue_script('jquery');
        wp_deregister_script('jquery');
    }

    // Remove plugin crab
    remove_action('wp_enqueue_scripts', ['EM_Scripts_and_Styles', 'public_enqueue']);
    remove_action('wp_enqueue_scripts', ['EM_Scripts_and_Styles', 'localize_script']);

    // Load stylesheets to the closing head tag.
    wp_enqueue_style('main', get_stylesheet_uri(), array(), 'v1.2.0');

    // Load js-files to the closing body tag.
    wp_enqueue_script('main', get_template_directory_uri() . '/js/main.min.js', array(), 'v1.0.1', true);
}
add_action('wp_enqueue_scripts', 'muhlenbergcenter_scripts');
