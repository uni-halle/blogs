<?php
/*
Plugin Name: WP Category Tag Cloud
Plugin URI:  https://wordpress.org/plugins/wp-category-tag-could/
Description: Display a configurable cloud of tags, categories or any other taxonomy filtered by tags or categories.
Version:     1.7.1
Author:      Henri Benoit
Author URI:  http://benohead.com
*/

/*
 * This plugin was built on top of WordPress-Plugin-Skeleton by Ian Dunn.
 * See https://github.com/iandunn/WordPress-Plugin-Skeleton for details.
 */

if (!defined('ABSPATH')) {
    die('Access denied.');
}

define('WPCTC_NAME', 'WP Category Tag Cloud');
define('WPCTC_REQUIRED_PHP_VERSION', '5.3'); // because of get_called_class()
define('WPCTC_REQUIRED_WP_VERSION', '3.1'); // because of esc_textarea()

/**
 * Checks if the system requirements are met
 *
 * @return bool True if system requirements are met, false if not
 */
function wpctc_requirements_met()
{
    global $wp_version;

    if (version_compare(PHP_VERSION, WPCTC_REQUIRED_PHP_VERSION, '<')) {
        return false;
    }

    if (version_compare($wp_version, WPCTC_REQUIRED_WP_VERSION, '<')) {
        return false;
    }

    return true;
}

/**
 * Prints an error that the system requirements weren't met.
 */
function wpctc_requirements_error()
{
    global $wp_version;

    require_once(dirname(__FILE__) . '/views/requirements-error.php');
}

/*
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met. Otherwise older PHP installations could crash when trying to parse it.
 */
if (wpctc_requirements_met()) {
    require_once(__DIR__ . '/classes/wpctc-module.php');
    require_once(__DIR__ . '/classes/wp-category-tag-cloud.php');
    require_once(__DIR__ . '/classes/wpctc-settings.php');
    require_once(__DIR__ . '/classes/wpctc-widget.php');

    if (class_exists('WordPress_Category_Tag_Cloud')) {
        $GLOBALS['wpctc'] = WordPress_Category_Tag_Cloud::get_instance();
        register_activation_hook(__FILE__, array($GLOBALS['wpctc'], 'activate'));
        register_deactivation_hook(__FILE__, array($GLOBALS['wpctc'], 'deactivate'));
    }
} else {
    add_action('admin_notices', 'wpctc_requirements_error');
}

if (class_exists('WordPress_Category_Tag_Cloud')) {
    function show_tag_cloud($options)
    {
        echo $GLOBALS['wpctc']->get_tag_cloud($options);
    }
}