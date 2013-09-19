<?php
/**
 * Handles all Admin access functionality.
 */
class Wdgpo_AdminPages {

	var $data;

	function Wdgpo_AdminPages () { $this->__construct(); }

	function __construct () {
		$this->data = new Wdgpo_Options;
	}

	/**
	 * Main entry point.
	 *
	 * @static
	 */
	function serve () {
		$me = new Wdgpo_AdminPages;
		$me->add_hooks();
	}

	function create_site_admin_menu_entry () {
		if (@$_POST && isset($_POST['option_page']) && 'wdgpo' == @$_POST['option_page']) {
			if (isset($_POST['wdgpo'])) {
				$this->data->set_options($_POST['wdgpo']);
			}
			$goback = add_query_arg('settings-updated', 'true',  wp_get_referer());
			wp_redirect($goback);
			die;
		}
		add_submenu_page('settings.php', 'Google+', 'Google+', 'manage_network_options', 'wdgpo', array($this, 'create_admin_page'));
	}

	function register_settings () {
		$form = new Wdgpo_AdminFormRenderer;

		register_setting('wdgpo', 'wdgpo');
		add_settings_section('wdgpo_settings', __('Google +1 settings', 'wdgpo'), create_function('', ''), 'wdgpo_options_page');
		add_settings_field('wdgpo_appearance', __('Appearance', 'wdgpo'), array($form, 'create_appearance_box'), 'wdgpo_options_page', 'wdgpo_settings');
		add_settings_field('wdgpo_show_cout', __('Show +1s count', 'wdgpo'), array($form, 'create_show_count_box'), 'wdgpo_options_page', 'wdgpo_settings');
		add_settings_field('wdgpo_position', __('Google +1 box position', 'wdgpo'), array($form, 'create_position_box'), 'wdgpo_options_page', 'wdgpo_settings');
		add_settings_field('wdgpo_skip_post_types', __('Do <strong>NOT</strong> Google +1 box for these post types', 'wdgpo'), array($form, 'create_skip_post_types_box'), 'wdgpo_options_page', 'wdgpo_settings');
		add_settings_field('wdgpo_language', __('Language', 'wdgpo'), array($form, 'create_language_box'), 'wdgpo_options_page', 'wdgpo_settings');
		add_settings_field('wdgpo_front_page', __('Show +1 on Front Page', 'wdgpo'), array($form, 'create_front_page_box'), 'wdgpo_options_page', 'wdgpo_settings');
		add_settings_field('wdgpo_footer_render', __('Add scripts to my footer', 'wdgpo'), array($form, 'create_footer_render_box'), 'wdgpo_options_page', 'wdgpo_settings');

		add_settings_section('wdgpo_gplus_pages', __('Google+ Pages and Profiles', 'wdgpo'), create_function('', ''), 'wdgpo_options_page');
		add_settings_field('wdgpo_gplus_page_id', __('My Google+ page ID', 'wdgpo'), array($form, 'create_gplus_page_id_box'), 'wdgpo_options_page', 'wdgpo_gplus_pages');
		add_settings_field('wdgpo_gplus_profile_id', __('My Google+ profile ID', 'wdgpo'), array($form, 'create_gplus_profile_id_box'), 'wdgpo_options_page', 'wdgpo_gplus_pages');
		add_settings_field('wdgpo_gplus_profile_fields', __('Google+ author profile fields', 'wdgpo'), array($form, 'create_gplus_profile_fields_box'), 'wdgpo_options_page', 'wdgpo_gplus_pages');

		add_settings_section('wdgpo_gplus_import', __('Google+ activities import', 'wdgpo'), array($form, 'create_import_check_box'), 'wdgpo_options_page');
		if (function_exists('curl_init')) {
			add_settings_field('wdgpo_gplus_settings', __('My Google+ App settings', 'wdgpo'), array($form, 'create_gplus_app_settings_box'), 'wdgpo_options_page', 'wdgpo_gplus_import');
			add_settings_field('wdgpo_gplus_import', __('Import settings', 'wdgpo'), array($form, 'create_gplus_import_settings_box'), 'wdgpo_options_page', 'wdgpo_gplus_import');
		}

		add_settings_section('wdgpo_analytics', __('Google Analytics integration', 'wdgpo'), create_function('', ''), 'wdgpo_options_page');
		add_settings_field('wdgpo_analytics_enable', __('Enable Google Analytics integration', 'wdgpo'), array($form, 'create_enable_analytics_box'), 'wdgpo_options_page', 'wdgpo_analytics');
		add_settings_field('wdgpo_analytics_category', __('Analytics category', 'wdgpo'), array($form, 'create_analytics_category_box'), 'wdgpo_options_page', 'wdgpo_analytics');
		
		add_settings_section('wdgpo_logging', __('Logging', 'wdgpo'), create_function('', ''), 'wdgpo_options_page');
		add_settings_field('wdgpo_log_level', __('Log level', 'wdgpo'), array($form, 'create_log_level_box'), 'wdgpo_options_page', 'wdgpo_logging');
		add_settings_field('wdgpo_log_output', __('Log', 'wdgpo'), array($form, 'create_log_output_box'), 'wdgpo_options_page', 'wdgpo_logging');
	}

	function create_blog_admin_menu_entry () {
		add_options_page('Google+', 'Google+', 'manage_options', 'wdgpo', array($this, 'create_admin_page'));
	}

	function create_admin_page () {
		include(WDGPO_PLUGIN_BASE_DIR . '/lib/forms/plugin_settings.php');
	}

	function generate_profile_fields ($fields) {
		$fields['wdgpo_gplus'] = __('Google+ Profile', 'wdgpo');
		return $fields;
	}

	function js_print_scripts () {
		if (!isset($_GET['page']) || 'wdgpo' != $_GET['page']) return false;
		wp_enqueue_script('wdgpo_admin', WDGPO_PLUGIN_URL . '/js/wdgpo-admin.js', array('jquery'));
	}

	function json_gplus_deauthenticate () {
		if (!function_exists('curl_init')) return false;
		$auth = Wdgpo_GoogleAuth::get_instance();
		$auth->reset_token();
		die;
	}

	function json_gplus_test_import () {
		if (!function_exists('curl_init')) return false;
		$auth = Wdgpo_GoogleAuth::get_instance();
		$data = $auth->get_gplus_data(true);
		$results = array();
		foreach ($data as $feed) {
			$results[] = array(
				"status" => (@$feed['title'] ? 1 : 0),
				"title" => @$feed['title'],
			);
		}
		header('Content-type: application/json');
		echo json_encode(array(
			"results" => $results,
		));
		die;
	}

	function json_gplus_import_now () {
		if (!function_exists('curl_init')) return false;
		$google = Wdgpo_GoogleAuth::get_instance();
		$data = $google->import_gplus_data();
		header('Content-type: application/json');
		echo json_encode(array(
			"results" => $data,
		));
		die;
	}

	function json_gplus_clear_log () {
		$log = new Wdgpo_Logger;
		$log->clear();
		die;
	}

	function add_hooks () {
		// Step0: Register options and menu
		add_action('admin_init', array($this, 'register_settings'));
		if (is_network_admin()) {
			add_action('network_admin_menu', array($this, 'create_site_admin_menu_entry'));
		} else {
			add_action('admin_menu', array($this, 'create_blog_admin_menu_entry'));
		}

		add_action('admin_print_scripts', array($this, 'js_print_scripts'));
		
		if ($this->data->get_option('gplus_profile_fields')) {
			add_filter('user_contactmethods', array($this, 'generate_profile_fields'));
		}

		// Register the shortcodes, so Membership picks them up
		$rpl = new Wdgpo_Codec; $rpl->register();

		// AJAX handlers
		add_action('wp_ajax_wdgpo_gplus_deauthenticate', array($this, 'json_gplus_deauthenticate'));
		add_action('wp_ajax_wdgpo_gplus_test_import', array($this, 'json_gplus_test_import'));
		add_action('wp_ajax_wdgpo_gplus_import_now', array($this, 'json_gplus_import_now'));
		add_action('wp_ajax_wdgpo_gplus_clear_log', array($this, 'json_gplus_clear_log'));
	}
}