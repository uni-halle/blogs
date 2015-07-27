<?php
/**
 * Author: Ole Fredrik Lie
 * URL: http://olefredrik.com
 *
 * FoundationPress functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @package WordPress
 * @subpackage FoundationPress
 * @since FoundationPress 1.0
 */

/** Various clean up functions */
require_once( 'library/cleanup.php' );

/** Required for Foundation to work properly */
require_once( 'library/foundation.php' );

/** Register all navigation menus */
require_once( 'library/navigation.php' );

/** Add desktop menu walker */
require_once( 'library/menu-walker.php' );

/** Add off-canvas menu walker */
require_once( 'library/offcanvas-walker.php' );

/** Create widget areas in sidebar and footer */
require_once( 'library/widget-areas.php' );

/** Return entry meta information for posts */
require_once( 'library/entry-meta.php' );

/** Enqueue scripts */
require_once( 'library/enqueue-scripts.php' );

/** Add theme support */
require_once( 'library/theme-support.php' );

/** Add Header image */
require_once( 'library/custom-header.php' );


/**
 * Custom functionality for the orma-responsive theme.
 */

require_once( 'includes/custom-post-type-person.php' );
require_once( 'includes/custom-taxonomy-person-type.php' );
//require_once( 'includes/custom-pagebuilder-layouts.php' );
require_once( 'includes/CustomFields.php' );

require_once( 'includes/PersonBoxWidget.class.php' );
require_once( 'includes/PersonListWidget.class.php' );
require_once( 'includes/TestimonialSlideshow.class.php' );
/**
 * Dequeue head styles from Siteorigin page builder.
 */
//dequeue css from plugins
/*add_action('wp_print_styles', 'orma_responsive_dequeue_css_from_plugins', 100);
function orma_responsive_dequeue_css_from_plugins()  {
	wp_dequeue_style( 'siteorigin-panels-front' );
}*/

/*remove_action('wp_head', 'siteorigin_panels_print_inline_css', 12);
remove_action('wp_footer', 'siteorigin_panels_print_inline_css');*/

/*add_filter( 'siteorigin_panels_row_attributes', 'orma_responsive_filter_grid', 2 );

function orma_responsive_filter_grid( $attributes, $panels_data ) {
	$attributes = array();
	return $attributes;
}
*/

/**
 * Add a custom filter group to the PageBuilder widget dialogue.
 * @param $tabs
 *
 * @return array
 */
function orma_responsive_add_widget_tabs($tabs) {
	$tabs[] = array(
		'title' => __('ORMA', 'orma'),
		'filter' => array(
			'groups' => array('orma')
		)
	);

	return $tabs;
}
add_filter('siteorigin_panels_widget_dialog_tabs', 'orma_responsive_add_widget_tabs', 20);



add_filter('wp_nav_menu_items','top_bar_search_box', 10, 2);
function top_bar_search_box( $nav, $args ) {
	if( $args->theme_location == 'top-bar-r' )
		return $nav.'<li class="has-form">
                <div class="row collapse">
                  <div class="large-8 small-9 columns">
                    <input type="text" placeholder="'. _e('Search term') .'">
                  </div>
                  <div class="large-4 small-3 columns"><a href="#" class="alert button expand">'. _e('Search') .'</a></div>
	                                                 </div>
              </li>';

	return $nav;
}
