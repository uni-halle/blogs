<?php
/*
Plugin Name: Poll, Survey, Quiz, Slideshow & Form Builder
Plugin URI: http://www.opinionstage.com
Description: Add a highly engaging poll, survey, quiz or contact form builder to your site. You can add the poll, survey, quiz or form to any post/page or to the sidebar.
Version: 19.2.5
Author: OpinionStage.com
Author URI: http://www.opinionstage.com
Text Domain: social-polls-by-opinionstage
*/

// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

$opinionstage_settings = array();

// don't even try to load any configuration settings,
// if wordpress is not in debug mode,
// as configuration settings are only for plugin development.
if ( defined('WP_DEBUG') && true === WP_DEBUG ) {
	if ( file_exists( $opinionstage_dev_cfg_path = plugin_dir_path( __FILE__ ).'dev.ini' ) ) {
		error_log( "[opinionstage plugin] loading configuration from file $opinionstage_dev_cfg_path" );
		$opinionstage_settings = parse_ini_file( $opinionstage_dev_cfg_path );
	}
}

define('OPINIONSTAGE_WIDGET_VERSION', '19.2.5');

define('OPINIONSTAGE_TEXT_DOMAIN', 'social-polls-by-opinionstage');

define('OPINIONSTAGE_SERVER_BASE', isset($opinionstage_settings['server_base']) ? $opinionstage_settings['server_base'] : 'https://www.opinionstage.com');
define('OPINIONSTAGE_LOGIN_PATH', OPINIONSTAGE_SERVER_BASE.'/integrations/wordpress/new');
define('OPINIONSTAGE_API_PATH', OPINIONSTAGE_SERVER_BASE.'/api/v1');
define('OPINIONSTAGE_CONTENT_POPUP_CLIENT_WIDGETS_API', OPINIONSTAGE_SERVER_BASE.'/api/wp/v1/my/widgets');
define('OPINIONSTAGE_CONTENT_POPUP_SHARED_WIDGETS_API', OPINIONSTAGE_SERVER_BASE.'/api/wp/v1/shared_widgets');
define('OPINIONSTAGE_CONTENT_POPUP_CLIENT_WIDGETS_API_RECENT_UPDATE', OPINIONSTAGE_SERVER_BASE.'/api/wp/v1/my/widgets/recent-update');

define('OPINIONSTAGE_WIDGET_API_KEY', 'wp35e8');
define('OPINIONSTAGE_OPTIONS_KEY', 'opinionstage_widget');

define('OPINIONSTAGE_POLL_SHORTCODE', 'socialpoll');
define('OPINIONSTAGE_WIDGET_SHORTCODE', 'os-widget');
define('OPINIONSTAGE_PLACEMENT_SHORTCODE', 'osplacement');

define('OPINIONSTAGE_MENU_SLUG', 'opinionstage-settings');
define('OPINIONSTAGE_LOGIN_CALLBACK_SLUG', 'opinionstage-login-callback');

require_once( plugin_dir_path( __FILE__ ).'includes/opinionstage-functions.php' );

// Check if another OpinionStage plugin already installed and display warning message.
if (opinionstage_check_plugin_available('opinionstage_popup')) {
	add_action('admin_notices', 'opinionstage_other_plugin_installed_warning');
} else {
	require_once( plugin_dir_path( __FILE__ ).'includes/opinionstage-utility-functions.php' );
	require_once( plugin_dir_path( __FILE__ ).'includes/opinionstage-article-placement-functions.php' );
	require_once( plugin_dir_path( __FILE__ ).'includes/opinionstage-sidebar-widget.php' );

	if ( is_admin() ) {
		require( plugin_dir_path( __FILE__ ).'admin/init.php' );
	} else {
		require( plugin_dir_path( __FILE__ ).'public/init.php' );
	}

	add_action('widgets_init', 'opinionstage_init_widget');
	add_action('plugins_loaded', 'opinionstage_init');
}
?>
