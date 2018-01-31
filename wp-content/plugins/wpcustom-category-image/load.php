<?php
/**
 * Plugin Name: WPCustom Category Image
 * Version: 2.1.13
 * Plugin URI: https://wordpress.org/plugins/wpcustom-category-image/
 * Text Domain: wpcustom-category-image
 * Domain Path: /lang
 * Description: "Customization is a good thing." The Category Image plugin allow users to upload their very own custom category (taxonomy) image to obtain a much more personalized look and feel.
 * Author: Eduardo Stuart
 * Author URI: https://s.tuart.me
 * Tested up to: 4.5.2
 * License: GPL v3
 */

define('WPCCI_WP_VERSION',      get_bloginfo('version'));
define('WPCCI_WP_MIN_VERSION',  3.5);
define('WPCCI_MIN_PHP_VERSION', '5.3.0');
define('WPCCI_PATH_BASE',       plugin_dir_path(__FILE__));
define('WPCCI_PATH_TEMPLATES',  WPCCI_PATH_BASE . 'templates/');

function wpcustomcategoryimage_textdomain()
{
    load_plugin_textdomain('wpcustom-category-image', false, plugin_basename(WPCCI_PATH_BASE) . '/lang/');
}

include_once WPCCI_PATH_BASE . 'helpers.php';
include_once WPCCI_PATH_BASE . 'WPCustomCategoryImage.php';

add_action('init', array('WPCustomCategoryImage', 'initialize'));
add_action('plugins_loaded', 'wpcustomcategoryimage_textdomain');

register_activation_hook(__FILE__, array('WPCustomCategoryImage', 'activate'));
register_deactivation_hook(__FILE__, array('WPCustomCategoryImage', 'deactivate'));