<?php
/*
Plugin Name: Widget Wrangler
Plugin URI: https://wordpress.org/plugins/widget-wrangler/
Description: Widget Wrangler gives the WordPress admin a clean interface for managing widgets on a page by page basis. It also provides widgets as a post type, the ability to clone existing WordPress widgets, and granular control over widget templates.
Author: Jonathan Daggerhart
Version: 2.3.6
Requires PHP: 5.3
Author URI: http://daggerhart.com
Text Domain: widgetwrangler
Domain Path: /languages
License: GPL2
*/
/*  Copyright 2010  Jonathan Daggerhart  (email : jonathan@daggerhart.com)
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('WW_VERSION', '2.3.6');
define('WW_DB_VERSION', '2002');
define('WW_SCRIPT_VERSION', '2.3.0');

define('WW_PLUGIN_FILE', __FILE__);
define('WW_PLUGIN_DIR', dirname(WW_PLUGIN_FILE));
define('WW_PLUGIN_URL', plugin_dir_url(WW_PLUGIN_FILE));

// leave this in the global space so anything can use it
$widget_wrangler = Widget_Wrangler::register();

use WidgetWrangler\Admin;
use WidgetWrangler\Display;
use WidgetWrangler\Presets;
use WidgetWrangler\Settings;
use WidgetWrangler\Updates;
use WidgetWrangler\Utils;
use WidgetWrangler\Widgets;

/**
 * Class Widget_Wrangler.
 *
 * New WordPress filters included
 *  - widget_wrangler_find_all_page_widgets
 *  - widget-wrangler-set-page-context
 *  - widget-wrangler-display-widget-output-alter
 *  - widget-wrangler-display-corral-output-alter
 *
actions
- widget_wrangler_form_meta  (doesn't get replaced on ajax)
- widget_wrangler_form_top
 */
class Widget_Wrangler {

	/**
	 * @var \WidgetWrangler\Settings
	 */
    public $settings;

	/**
	 * Store an instance of the Display object for global functions.
	 *
	 * @var \WidgetWrangler\Display
	 */
    public $display;

	/**
	 * Store a copy of the contextual page widgets here for other plugins
	 * and themes to use.
	 * Backwards compatibility for WW 2.2-
	 *
	 * @var array
	 */
    public $page_widgets = array();

	/**
	 * Construct the widget wrangler object.
	 *  - add dependencies
	 */
	function __construct() {
		include_once WW_PLUGIN_DIR.'/common/Context.php';
		include_once WW_PLUGIN_DIR.'/common/Corrals.php';
		include_once WW_PLUGIN_DIR.'/common/display.php';
		include_once WW_PLUGIN_DIR.'/common/Extras.php';
		include_once WW_PLUGIN_DIR.'/common/presets.php';
		include_once WW_PLUGIN_DIR.'/common/Settings.php';
		include_once WW_PLUGIN_DIR.'/common/Updates.php';
		include_once WW_PLUGIN_DIR.'/common/Utils.php';
		include_once WW_PLUGIN_DIR.'/common/Widgets.php';

		include_once WW_PLUGIN_DIR.'/includes/template-wrangler.inc';
		include_once WW_PLUGIN_DIR.'/includes/backwards-compat-functions.inc';
		include_once WW_PLUGIN_DIR.'/includes/wp-widget-ww-corral.php';
		include_once WW_PLUGIN_DIR.'/includes/wp-widget-ww-widget.php';
	}

	/**
	 * Instantiate and register WP hooks
	 *
	 * @return \Widget_Wrangler
	 */
	public static function register() {
		$plugin = new self();

		add_action('wp_loaded', array($plugin, 'wp_loaded'), 999);

		// early wp hooks
		register_activation_hook(WW_PLUGIN_FILE, '\WidgetWrangler\Updates::install');
		add_action( 'widgets_init', array( $plugin, 'widgets_init' ) );
		add_action( 'init', array( $plugin, 'register_post_types' ) );
		add_action( 'wp', array( $plugin, 'load_page_widgets' ) );

		// let all plugins load before gathering addons
		add_action( 'plugins_loaded' , array( $plugin, 'plugins_loaded' ) );

		return $plugin;
	}

	/**
	 * WordPress hook widgets_init
	 *
	 *  - Register the corral and widget WP_Widget(s)
	 */
	function widgets_init() {
		register_widget( 'WidgetWrangler_Corral_Widget' );
		register_widget( 'WidgetWrangler_Widget_Widget' );
	}

	/**
	 * WordPress hook plugins_loaded
	 */
	function plugins_loaded() {
		load_plugin_textdomain( 'widgetwrangler', FALSE, basename( WW_PLUGIN_DIR ) . '/languages/' );

		// initialize core
		$this->settings = Settings::instance();
		$this->display = Display::register( $this->settings->values );
		Presets::register();


		// initialize admin stuff
		if (is_admin()){
			include_once WW_PLUGIN_DIR.'/admin/Admin.php';
			Admin::register($this->settings->values);

			// make sure we're updated
			Updates::update();
		}
	}

	/**
	 * WordPress wp_loaded hook
	 */
	function wp_loaded() {
		if ( !is_admin()) {
			global $wp_registered_sidebars;
			$wp_registered_sidebars = Utils::alteredSidebars();
		}
	}

	/**
	 * Widget Post Type
	 */
	function register_post_types() {
		$settings = $this->settings->values;
		$capability_type = ($settings['capabilities'] == "advanced" && isset($settings['advanced_capability'])) ? $settings['advanced_capability'] : "post";

		register_post_type('widget', array(
			'labels' => array(
				'name' => __('Widget Wrangler', 'widgetwrangler'),
				'all_items' => __('All Widgets', 'widgetwrangler'),
				'singular_name' => __('Widget', 'widgetwrangler'),
				'add_new' => __('Add New Widget', 'widgetwrangler'),
				'add_new_item' => __('Add New Widget', 'widgetwrangler'),
				'edit_item' => __('Edit Widget', 'widgetwrangler'),
				'new_item' => __('New Widget', 'widgetwrangler'),
				'view_item' => __('View Widget', 'widgetwrangler'),
				'search_items' => __('Search Widgets', 'widgetwrangler'),
				'not_found' =>  __('No widgets found', 'widgetwrangler'),
				'not_found_in_trash' => __('No widgets found in Trash', 'widgetwrangler'),
				'parent_item_colon' => '',
			),
			'supports' => array(
				'title' => 'title',
				'excerpt' => 'excerpt',
				'editor' => 'editor',
				'custom-fields' => 'custom-fields',
				'thumbnail' => 'thumbnail'
			),
			'public' => true,
			'exclude_from_search' => (isset($settings['exclude_from_search']) && $settings['exclude_from_search'] == 0) ? false : true,
			'show_in_menu' => true,
			'show_ui' => true,
			'_builtin' => false,
			'_edit_link' => 'post.php?post=%d',
			'capability_type' => $capability_type,
			'hierarchical' => false,
			'rewrite' => array('slug' => 'widget'),
			'query_var' => 'widget',
			'menu_icon' => WW_PLUGIN_URL.'/admin/images/lasso-menu.png'
		));
	}

	/**
	 * Keep a copy of the page widgets here within the global WW object for
	 * other plugins or themes to use.
	 */
	function load_page_widgets() {
		$this->page_widgets = \WidgetWrangler\Context::pageWidgets();
	}

	/**
	 * Returns all published widgets
	 * @deprecated
	 * @see \WidgetWranglerWidgets::all()
	 */
	function get_all_widgets($post_status = array('publish')) {
		return Widgets::all($post_status);
	}

	/**
	 * Retrieve and return a single widget by its ID
	 * @deprecated
	 * @see Widgets::get()
	 */
	function get_single_widget($post_id, $widget_status = false) {
		return Widgets::get($post_id, $widget_status);
	}
}
