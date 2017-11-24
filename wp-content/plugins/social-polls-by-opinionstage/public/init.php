<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

require( plugin_dir_path( __FILE__ ).'shortcodes.php' );

add_action( 'wp_enqueue_scripts', 'opinionstage_enqueue_shortcodes_assets' );

add_shortcode(OPINIONSTAGE_POLL_SHORTCODE, 'opinionstage_poll_or_set_shortcode');
add_shortcode(OPINIONSTAGE_WIDGET_SHORTCODE, 'opinionstage_widget_shortcode');
add_shortcode(OPINIONSTAGE_PLACEMENT_SHORTCODE, 'opinionstage_placement_shortcode');

add_action('wp_head', 'opinionstage_add_flyout');
?>
