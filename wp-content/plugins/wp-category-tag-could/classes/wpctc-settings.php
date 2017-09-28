<?php

if ( ! class_exists( 'WPCTC_Settings' ) ) {

	/**
	 * Handles plugin settings and user profile meta fields
	 */
	class WPCTC_Settings extends WPCTC_Module {
		/**
		 * @var
		 */
		protected $settings;
		/**
		 * @var
		 */
		protected static $default_settings;
		/**
		 * @var array
		 */
		protected static $readable_properties = array( 'settings' );
		/**
		 * @var array
		 */
		protected static $writeable_properties = array( 'settings' );

		/**
		 *
		 */
		const REQUIRED_CAPABILITY = 'manage_options';


		/*
		 * General methods
		 */

		/**
		 * Constructor
		 *
		 * @mvc Controller
		 */
		protected function __construct() {
			$this->register_hook_callbacks();
		}

		/**
		 * Public setter for protected variables
		 *
		 * Updates settings outside of the Settings API or other subsystems
		 *
		 * @mvc Controller
		 *
		 * @param string $variable
		 * @param array $value This will be merged with WPCTC_Settings->settings, so it should mimic the structure of the WPCTC_Settings::$default_settings. It only needs the contain the values that will change, though. See WordPress_Category_Tag_Cloud->upgrade() for an example.
		 */
		public function __set( $variable, $value ) {
			// Note: WPCTC_Module::__set() is automatically called before this

			if ( $variable != 'settings' ) {
				return;
			}

			$this->settings = self::validate_settings( $value );
			update_option( 'wpctc_settings', $this->settings );
		}

		/**
		 * Register callbacks for actions and filters
		 *
		 * @mvc Controller
		 */
		public function register_hook_callbacks() {
			add_action('admin_menu', __CLASS__ . '::register_settings_pages');
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );

			add_filter(
				'plugin_action_links_' . plugin_basename( dirname( __DIR__ ) ) . '/bootstrap.php',
				array( $this, 'add_plugin_action_links' )
			);
		}

		/**
		 * Adds pages to the Admin Panel menu
		 *
		 * @mvc Controller
		 */
		public static function register_settings_pages()
		{
			add_submenu_page(
				'options-general.php',
				WPCTC_NAME . ' Settings',
				WPCTC_NAME,
				self::REQUIRED_CAPABILITY,
				'wpctc_settings',
				__CLASS__ . '::markup_settings_page'
			);
		}

		/**
		 * Creates the markup for the Settings page
		 *
		 * @mvc Controller
		 */
		public static function markup_settings_page()
		{
			if (current_user_can(self::REQUIRED_CAPABILITY)) {
				echo self::render_template('wpctc-settings/page-settings.php');
			} else {
				wp_die('Access denied.');
			}
		}

		/**
		 * Prepares site to use the plugin during activation
		 *
		 * @mvc Controller
		 *
		 * @param bool $network_wide
		 */
		public
		function activate(
			$network_wide
		) {
		}

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 *
		 * @mvc Controller
		 */
		public
		function deactivate() {
		}

		/**
		 * Initializes variables
		 *
		 * @mvc Controller
		 */
		public
		function init() {
			self::$default_settings = self::get_default_settings();
			$this->settings         = self::get_settings();
		}

		/**
		 * Executes the logic of upgrading from specific older versions of the plugin to the current version
		 *
		 * @mvc Model
		 *
		 * @param string $db_version
		 */
		public
		function upgrade(
			$db_version = 0
		) {
			/*
			if( version_compare( $db_version, 'x.y.z', '<' ) )
			{
				// Do stuff
			}
			*/
		}

		/**
		 * Checks that the object is in a correct state
		 *
		 * @mvc Model
		 *
		 * @param string $property An individual property to check, or 'all' to check all of them
		 *
		 * @return bool
		 */
		protected
		function is_valid(
			$property = 'all'
		) {
			// Note: __set() calls validate_settings(), so settings are never invalid

			return true;
		}


		/*
		 * Plugin Settings
		 */

		/**
		 * Establishes initial values for all settings
		 *
		 * @mvc Model
		 *
		 * @return array
		 */
		protected
		static function get_default_settings() {
			$general = array(
				"clear-cache-on-save" => false,
				"do-not-load-scripts" => false,
			);

			return array(
				'db-version' => '0',
				'general' => $general
			);
		}

		/**
		 * Retrieves all of the settings from the database
		 *
		 * @mvc Model
		 *
		 * @return array
		 */
		protected function get_settings() {
			$settings = shortcode_atts(
				self::$default_settings,
				get_option( 'wpctc_settings', array() )
			);

			return $settings;
		}

		/**
		 * Adds links to the plugin's action link section on the Plugins page
		 *
		 * @mvc Model
		 *
		 * @param array $links The links currently mapped to the plugin
		 *
		 * @return array
		 */
		public function add_plugin_action_links( $links ) {
			array_unshift( $links, '<a href="http://wordpress.org/extend/plugins/wp-category-tag-could/faq/">Help</a>' );

			return $links;
		}

		/**
		 * Delivers the markup for settings fields
		 *
		 * @mvc Controller
		 *
		 * @param array $field
		 */
		public function markup_fields($field)
		{
			global $q_config;
			echo self::render_template('wpctc-settings/page-settings-fields.php', array('settings' => $this->settings, 'field' => $field, 'q_config' => $q_config), 'always');
		}

		private function add_settings_field($id, $title, $section)
		{
			add_settings_field(
				$id,
				$title,
				array($this, 'markup_fields'),
				'wpctc_settings',
				$section,
				array('label_for' => $id)
			);
		}

		private function add_settings_field_general($id, $title)
		{
			$this->add_settings_field($id, $title, 'wpctc_section-general');
		}

		/**
		 * Adds the section introduction text to the Settings page
		 *
		 * @mvc Controller
		 *
		 * @param array $section
		 */
		public static function markup_section_headers($section)
		{
			echo self::render_template('wpctc-settings/page-settings-section-headers.php', array('section' => $section), 'always');
		}

		private function add_settings_section($id, $title)
		{
			add_settings_section(
				$id,
				$title,
				__CLASS__ . '::markup_section_headers',
				'wpctc_settings'
			);
		}

		/**
		 * Registers settings sections, fields and settings
		 *
		 * @mvc Controller
		 */
		public function register_settings() {
			/*
             * General Section
             */
			$this->add_settings_section('wpctc_section-general', 'General');

			$this->add_settings_field_general('wpctc_clear-cache-on-save', 'Clear cache on widget save');
			$this->add_settings_field_general('wpctc_do-not-load-scripts', 'Do not load scripts if no widgets used on page');

			// The settings container
			register_setting( 'wpctc_settings', 'wpctc_settings', array( $this, 'validate_settings' ) );
		}

		private function setting_default_if_not_set($new_settings, $section, $id, $value)
		{
			if (!isset($new_settings[$section][$id])) {
				$new_settings[$section][$id] = $value;
			}
		}

		private function setting_zero_if_not_set($new_settings, $section, $id)
		{
			$this->setting_default_if_not_set($new_settings, $section, $id, '0');
		}

		/**
		 * Validates submitted setting values before they get saved to the database. Invalid data will be overwritten with defaults.
		 *
		 * @mvc Model
		 *
		 * @param array $new_settings
		 *
		 * @return array
		 */
		public function validate_settings( $new_settings ) {
			$new_settings = shortcode_atts( $this->settings, $new_settings );

			if ( ! is_string( $new_settings['db-version'] ) ) {
				$new_settings['db-version'] = WordPress_Category_Tag_Cloud::VERSION;
			}

			/*
             * General Settings
             */

			if (!isset($new_settings['general'])) {
				$new_settings['general'] = array();
			}

			$this->setting_zero_if_not_set($new_settings, 'general', 'clear-cache-on-save');
			$this->setting_zero_if_not_set($new_settings, 'general', 'do-not-load-scripts');

			WordPress_Category_Tag_Cloud::clear_caching_plugins();

			return $new_settings;
		}
	} // end WPCTC_Settings
}
