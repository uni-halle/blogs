<?php
/*
 Plugin Name: Another Wordpress Classifieds Plugin (AWPCP)
 Plugin URI: http://www.awpcp.com
 Description: AWPCP - A plugin that provides the ability to run a free or paid classified ads service on your wordpress blog. <strong>!!!IMPORTANT!!!</strong> Whether updating a previous installation of Another Wordpress Classifieds Plugin or installing Another Wordpress Classifieds Plugin for the first time, please backup your wordpress database before you install/uninstall/activate/deactivate/upgrade Another Wordpress Classifieds Plugin.
 Version: 3.0.1
 Author: D. Rodenbaugh
 License: GPLv2 or any later version
 Author URI: http://www.skylineconsult.com
 */

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * dcfunctions.php and filop.class.php used with permission of Dan Caragea, http://datemill.com
 * AWPCP Classifieds icon set courtesy of http://www.famfamfam.com/lab/icons/silk/
 */

if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('You are not allowed to call this page directly.');
}


define('AWPCP_BASENAME', basename(dirname(__FILE__)));
define('AWPCP_DIR', WP_PLUGIN_DIR . '/' . AWPCP_BASENAME);
define('AWPCP_URL', WP_PLUGIN_URL . '/' . AWPCP_BASENAME);

// TODO: Why do we need a custom error handler?
if (!function_exists('AWPCPErrorHandler')) {

	/**
	 * Set custom error handler functions.
	 */
	function AWPCPErrorHandler($errno, $errstr, $errfile, $errline){
		$output = '';
		switch ($errno) {
			case E_USER_ERROR:
				if ($errstr == "(SQL)"){
					// handling an sql error
					$output .= "<b>AWPCP SQL Error</b> Errno: [$errno] SQLError:" . SQLMESSAGE . "<br />\n";
					$output .= "Query : " . SQLQUERY . "<br />\n";
					$output .= "Called by line " . SQLERRORLINE . " in file " . SQLERRORFILE . ", error in ".$errfile." at line ".$errline;
					$output .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
					$output .= "Aborting...<br />\n";
				} else {
					$output .= "<b>AWPCP PHP Error</b> [$errno] $errstr<br />\n";
					$output .= "  Fatal error called by line $errline in file $errfile, error in ".$errfile." at line ".$errline;
					$output .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
					$output .= "Aborting...<br />\n";
				}
				//Echo OK here:
				echo $output;
				exit(1);
				break;

			case E_USER_WARNING:
			case E_USER_NOTICE:
		}
		/* true=Don't execute PHP internal error handler */
		return true;
	}

}

if (file_exists(AWPCP_DIR . '/DEBUG')) {
	// let's see some errors
} else {
	set_error_handler("AWPCPErrorHandler");
}

global $awpcp;

global $awpcp_plugin_data;
global $awpcp_db_version;

global $wpcontenturl;
global $wpcontentdir;
global $awpcp_plugin_path;
global $awpcp_plugin_url;
global $imagespath;
global $awpcp_imagesurl;

global $nameofsite;


// get_plugin_data accounts for about 2% of the cost of
// each request, defining the version manually is a less
// expensive way --wvega
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$awpcp_plugin_data = get_plugin_data(__FILE__);
$awpcp_db_version = $awpcp_plugin_data['Version'];

$wpcontenturl = WP_CONTENT_URL;
$wpcontentdir = WP_CONTENT_DIR;
$awpcp_plugin_path = AWPCP_DIR;
$awpcp_plugin_url = AWPCP_URL;
$imagespath = $awpcp_plugin_path . '/images';
$awpcp_imagesurl = $awpcp_plugin_url .'/images';


// common
require_once(AWPCP_DIR . "/debug.php");
require_once(AWPCP_DIR . "/functions.php");

$nameofsite = awpcp_get_blog_name();

// cron
require_once(AWPCP_DIR . "/cron.php");

// other resources
require_once(AWPCP_DIR . "/dcfunctions.php");
require_once(AWPCP_DIR . "/functions_awpcp.php");
require_once(AWPCP_DIR . "/upload_awpcp.php");

// API & Classes
require_once(AWPCP_DIR . "/classes/compatibility/compatibility.php");

require_once(AWPCP_DIR . "/classes/helpers/list-table.php");
require_once(AWPCP_DIR . "/classes/helpers/email.php");
require_once(AWPCP_DIR . "/classes/helpers/javascript.php");
require_once(AWPCP_DIR . "/classes/helpers/captcha.php");

require_once(AWPCP_DIR . "/classes/models/ad.php");
require_once(AWPCP_DIR . "/classes/models/category.php");
require_once(AWPCP_DIR . "/classes/models/image.php");
require_once(AWPCP_DIR . "/classes/models/payment-transaction.php");

require_once(AWPCP_DIR . "/classes/settings-api.php");
require_once(AWPCP_DIR . "/classes/payments-api.php");

require_once(AWPCP_DIR . "/classes/credit-plan.php");

require_once(AWPCP_DIR . "/classes/payment-term-type.php");
require_once(AWPCP_DIR . "/classes/payment-term.php");
require_once(AWPCP_DIR . "/classes/payment-term-fee-type.php");
require_once(AWPCP_DIR . "/classes/payment-term-fee.php");

require_once(AWPCP_DIR . "/classes/payment-gateway.php");
require_once(AWPCP_DIR . "/classes/payment-gateway-paypal-standard.php");
require_once(AWPCP_DIR . "/classes/payment-gateway-2checkout.php");

require_once(AWPCP_DIR . "/classes/payment-terms-table.php");

// installation functions
require_once(AWPCP_DIR . "/install.php");

// admin functions
require_once(AWPCP_DIR . "/admin/admin-panel.php");
require_once(AWPCP_DIR . "/admin/user-panel.php");

// frontend functions
require_once(AWPCP_DIR . "/frontend/placeholders.php");
require_once(AWPCP_DIR . "/frontend/payment-functions.php");
require_once(AWPCP_DIR . "/frontend/ad-functions.php");
require_once(AWPCP_DIR . "/frontend/shortcode.php");

require_once(AWPCP_DIR . "/frontend/widget-search.php");
require_once(AWPCP_DIR . "/frontend/widget-latest-ads.php");
require_once(AWPCP_DIR . "/frontend/widget-random-ad.php");
require_once(AWPCP_DIR . "/frontend/widget-categories.php");


class AWPCP {

	public $installer = null;

	public $admin = null; // Admin section
	public $panel = null; // User Ad Management panel
	public $pages = null; // Frontend pages

	public $settings = null;
	public $payments = null;
	public $js = null;

	public $flush_rewrite_rules = false;

	// TODO: I want to register all plugin scripts here, enqueue on demand in each page.
	// is that a good idea? -@wvega

	public function __construct() {
		// stored options are loaded when the settings API is instatiated
		$this->settings = AWPCP_Settings_API::instance();

		$this->js = AWPCP_JavaScript::instance();

		// l10n MO file can be in the top level directory or inside the languages
		// directory. A file inside the languages directory is prefered.
		if ($this->settings->get_option('activatelanguages')) {
			$basename = dirname(plugin_basename(__FILE__));
			if (!load_plugin_textdomain('AWPCP', false, $basename . '/languages/')) {
				load_plugin_textdomain('AWPCP', false, $basename);
			}
		}

		// register settings, this will define default values for settings
		// that have never been stored
		$this->settings->register_settings();

		$this->installer = AWPCP_Installer::instance();

		$file = WP_CONTENT_DIR . '/plugins/' . basename(dirname(__FILE__)) . '/' . basename(__FILE__);
        register_activation_hook($file, array($this->installer, 'activate'));

        add_action('plugins_loaded', array($this, 'setup'), 10);

        // register rewrite rules when the plugin file is loaded.
		// generate_rewrite_rules or rewrite_rules_array hooks are
		// too late to add rules using add_rewrite_rule function
		add_action('page_rewrite_rules', 'awpcp_add_rewrite_rules');
		add_filter('query_vars', 'awpcp_query_vars');
	}

	/**
	 * Check if AWPCP DB version corresponds to current AWPCP plugin version
	 */
	public function updated() {
		global $awpcp_db_version;
		$installed = get_option('awpcp_db_version', '');
		// if installed version is greater than plugin version
		// not sure what to do. Downgrade is not currently supported.
		return version_compare($installed, $awpcp_db_version) === 0;
	}

	/**
	 * Single entry point for AWPCP plugin.
	 *
	 * This is functional but still a work in progress...
	 */
	public function setup() {
		if ( !function_exists( 'imagecreatefrompng' ) ) {
			add_action( 'admin_notices', array( $this, 'missing_gd_library' ) );
		}

		if (!$this->updated()) {
			$this->installer->install();
			// we can't call flush_rewrite_rules() because
			// $wp_rewrite is not available yet. It is initialized
			// after plugins_load hook is executed.
			$this->flush_rewrite_rules = true;
		}

		if (!$this->updated()) {
			return;
		}

		$this->settings->setup();
		$this->payments = awpcp_payments_api();

		$this->admin = new AWPCP_Admin();
		$this->panel = new AWPCP_User_Panel();
		$this->compatibility = new AWPCP_Compatibility();

		add_action( 'init', array($this, 'init' ));
		add_action( 'init', array($this, 'register_custom_style'), 1000000 );
		add_action('admin_init', array($this, 'check_premium_modules_compatibility'));
		add_action('admin_notices', array($this, 'admin_notices'));

		add_action('awpcp_register_settings', array($this, 'register_settings'));

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1000 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1000 );
		add_action( 'wp_footer', array( $this, 'localize_scripts' ) );
		add_action( 'admin_footer', array( $this, 'localize_scripts' ) );

		// we need to dalay insertion of inline JavaScript to avoid problems
		// with wpauotp and wptexturize functions
		add_filter('the_content', 'awpcp_inline_javascript', 1000);
		add_filter('admin_footer', 'awpcp_print_inline_javascript', 1000);

		// some upgrade operations can't be done in background.
		// if one those is pending, we will disable all other features
		// until the user executes the upgrade operaton
		if (get_option('awpcp-pending-manual-upgrade')) return;

		$this->pages = new AWPCP_Pages();

		add_action('awpcp-register-payment-term-types', array($this, 'register_payment_term_types'));
		add_action('awpcp-register-payment-methods', array($this, 'register_payment_methods'));

        add_action('awpcp-process-payment-transaction', array($this, 'process_transaction'));
        add_action('awpcp-process-payment-transaction', array($this, 'process_ad_payment_transaction'));

		// actions and filters from functions_awpcp.php
		add_action('phpmailer_init','awpcp_phpmailer_init_smtp');

		add_filter('cron_schedules', 'awpcp_cron_schedules');

		add_action('init', 'awpcp_schedule_activation');

		add_action('widgets_init', array($this, 'register_widgets'));

		if (get_awpcp_option('awpcppagefilterswitch') == 1) {
			add_filter('wp_list_pages_excludes', 'exclude_awpcp_child_pages');
		}

		remove_action('wp_head', 'rel_canonical');
		add_action('wp_head', 'awpcp_rel_canonical');
	}

	public function admin_notices() {
		foreach (awpcp_get_property($this, 'errors', array()) as $error) {
			echo awpcp_print_error($error);
		}
	}

	public function missing_gd_library() {
        $message = __( "AWPCP requires the graphics processing library GD and it is not installed. Contact your web host to fix this.", "AWPCP" );
        $message = sprintf( '<strong>%s</strong> %s', __( 'Warning', 'AWPCP' ), $message );
        echo '<div class="error"><p>' . $message . '</p></div>';
	}

	public function init() {
		$this->initialize_session();

		if (!get_option('awpcp_installationcomplete', 0)) {
			update_option('awpcp_installationcomplete', 1);
			awpcp_create_pages(__('AWPCP', 'AWPCP'));
			$this->flush_rewrite_rules = true;
		}

		if ($this->flush_rewrite_rules) {
			flush_rewrite_rules();
		}

		$this->register_scripts();
	}

	/**
	 * Returns information about available and installed
	 * premium modules.
	 *
	 * @since  3.0
	 */
	public function get_premium_modules_information() {
		global $hasextrafieldsmodule, $hasregionsmodule;
		global $hascaticonsmodule, $hasgooglecheckoutmodule;
		global $hasrssmodule;

		return array(
			'Regions Control' => array(
				'name' => __('Regions Control', 'AWPCP'),
				'url' => 'http://www.awpcp.com/premium-modules/regions-control-module?ref=panel',
				'installed' => $hasregionsmodule,
				'version' => 'AWPCP_REGION_CONTROL_MODULE_DB_VERSION',
				'required' => '3.0-beta',
			),

			'Category Icons' => array(
				'name' => __('Category Icons', 'AWPCP'),
				'url' => 'http://www.awpcp.com/premium-modules/category-icons-module?ref=panel',
				'installed' => $hascaticonsmodule,
				'version' => '0',
				'required' => '0',
			),

			'Extra Fields' => array(
				'name' => __('Extra Fields', 'AWPCP'),
				'url' => 'http://www.awpcp.com/premium-modules/extra-fields-module?ref=panel',
				'installed' => $hasextrafieldsmodule,
				'version' => 'AWPCP_EXTRA_FIELDS_MODULE_DB_VERSION',
				'required' => '3.0-beta',
			),

			'XML Sitemap' => array(
				'name' => __( 'XML Sitemap', 'AWPCP' ),
				'url' => 'http://www.awpcp.com/premium-modules/',
				'installed' => function_exists( 'awpcp_generate_ad_entries' ),
				'version' => 'AWPCP_XML_SITEMAP_MODULE_DB_VERSION',
				'required' => '3.0-beta',
			),

			'RSS' => array(
				'name' => __('RSS', 'AWPCP'),
				'url' => 'http://www.awpcp.com/premium-modules/rss-module?ref=panel',
				'installed' => $hasrssmodule,
				'version' => 'AWPCP_RSS_MODULE_DB_VERSION',
				'required' => '3.0-beta',
			),

			'Featured Ads' => array(
				'name' => __('Featured Ads', 'AWPCP'),
				'url' => 'http://www.awpcp.com/premium-modules/featured-ads-module?ref=panel',
				'installed' => function_exists('awpcp_show_featured_ads'),
				'version' => 'AWPCP_FEATURED_ADS_MODULE_DB_VERSION',
				'required' => '3.0-beta',
			),

			'Fee per Category' => array(
				'name' => __('Fee per Category', 'AWPCP'),
				'url' =>'http://www.awpcp.com/premium-modules/fee-per-category-module?ref=panel',
				'installed' => function_exists('awpcp_price_cats'),
				'version' => 'AWPCP_FPC_MODULE_DB_VERSION',
				'required' => '3.0-beta',
			),

			'Subscriptions' => array(
				'name' => __('Subscriptions', 'AWPCP'),
				'url' => 'http://www.awpcp.com/premium-modules/subscriptions-module/?ref=panel',
				'installed' => defined('AWPCP_SUBSCRIPTIONS_MODULE'),
				'version' => 'AWPCP_SUBSCRIPTIONS_MODULE_DB_VERSION',
				'required' => '3.0-beta',
			),

			'Coupons' => array(
				'name' => __('Coupons/Discount', 'AWPCP'),
				'url' => 'http://www.awpcp.com/premium-modules/coupon-module/?ref=panel',
				'installed' => defined('AWPCP_COUPONS_MODULE'),
				'version' => 'AWPCP_COUPONS_MODULE_DB_VERSION',
				'required' => '3.0-beta',
			),

			'Comments & Ratings' => array(
				'name' => __( 'Comments & Ratings', 'AWPCP' ),
				'url' => 'http://www.awpcp.com/premium-modules/comments-ratings-module/?ref=user-panel',
				'installed' => defined( 'AWPCP_COMMENTS_MODULE' ),
				'version' => 'AWPCP_COMMENTS_MODULE_VERSION',
				'required' => '3.0-beta',
			),

			'Authorize.Net' => array(
				'name' => __( 'Authorize.Net', 'AWPCP' ),
				'url' => 'http://www.awpcp.com/premium-modules/authorizenet-payment-module/?ref=user-panel',
				'installed' => defined( 'AWPCP_AUTHORIZE_NET_MODULE' ),
				'version' => 'AWPCP_AUTHORIZE_NET_MODULE_DB_VERSION',
				'required' => '3.0-beta',
			),

			'PayPal Pro' => array(
				'name' => __( 'PayPal Pro', 'AWPCP' ),
				'url' => 'http://www.awpcp.com/premium-modules/paypalpro-payment-module/?ref=user-panel',
				'installed' => defined( 'AWPCP_PAYPAL_PRO_MODULE' ),
				'version' => 'AWPCP_PAYPAL_PRO_MODULE_DB_VERSION',
				'required' => '3.0-beta',
			),

			'Google Checkout' => array(
				'name' => __('Google Checkout', 'AWPCP'),
				'url' => 'http://www.awpcp.com/premium-modules/google-checkout-module/?ref=panel',
				'installed' => $hasgooglecheckoutmodule,
				'version' => 'AWPCP_GOOGLE_CHECKOUT_MODULE_DB_VERSION',
				'required' => '3.0-beta',
			),
		);
	}

	public function check_premium_modules_compatibility() {
		$this->errors = awpcp_get_property($this, 'errors', array());

		$modules = $this->get_premium_modules_information();

		foreach ($modules as $module => $params) {
			if (!$params['installed']) continue;

			if (defined($params['version'])) {
				$version = constant($params['version']);
			} else {
				$version = '0.0.1';
			}

			if (version_compare($version, $params['required']) < 0) {
				$message = __('The %1$s module you have installed is outdated and not compatible with this version of AWPCP. Please get %1$s %2$s or newer.', 'AWPCP');
				$name = "<strong>{$params['name']}</strong>";
				$required = "<strong>{$params['required']}</strong>";
				$this->errors[] = sprintf($message, $name, $required);
			}
		}
	}

	/**
	 * Conditionally start session if not already active.
	 *
	 * @since  2.1.4
	 */
	public function initialize_session() {
		$session_id = session_id();
		if (empty($session_id)) {
			// if we are in a subdomain, let PHP choose the right domain
			if (strcmp(awpcp_get_current_domain(), awpcp_get_current_domain(false)) == 0) {
				$domain = '';
			// otherwise strip the www part
			} else {
				$domain = awpcp_get_current_domain(false, '.');
			}

			@session_set_cookie_params(0, '/', $domain, false, true);
			@session_start();
		}
	}

	/**
	 * A good place to register all AWPCP standard scripts that can be
	 * used from other sections.
	 */
	public function register_scripts() {
		global $wp_styles;
		global $wp_scripts;

		global $awpcp_db_version;

		$js = AWPCP_URL . '/js';
		$css = AWPCP_URL . '/css';

		/* vendors */

		if (isset($wp_scripts->registered['jquery-ui-core'])) {
			$ui_version = $wp_scripts->registered['jquery-ui-core']->ver;
		} else {
			$ui_version = '1.9.2';
		}

		wp_register_style('awpcp-jquery-ui', "http://ajax.googleapis.com/ajax/libs/jqueryui/$ui_version/themes/smoothness/jquery-ui.css", array(), $ui_version);

		wp_register_script('awpcp-jquery-validate', "{$js}/jquery-validate/all.js", array('jquery'), '1.10.0', true);
        wp_register_script('awpcp-knockout', "{$js}/knockout-2.2.0.js", array(), '2.2.0', true);

		if (!get_awpcp_option('awpcp_thickbox_disabled')) {
			add_thickbox();
		}

		/* helpers */

		wp_register_script('awpcp', "{$js}/awpcp.js", array('jquery'), $awpcp_db_version, true);
		wp_register_script('awpcp-admin-wp-table-ajax', "{$js}/admin-wp-table-ajax.js", array('jquery-form'), $awpcp_db_version, true);
		wp_register_script('awpcp-billing-form', "{$js}/awpcp-billing-form.js", array('jquery', 'awpcp-knockout'), $awpcp_db_version, true);

		// register again with old name too (awpcp-table-ajax-admin), for backwards compatibility
		wp_register_script('awpcp-table-ajax-admin', "{$js}/admin-wp-table-ajax.js", array('jquery-form'), $awpcp_db_version, true);

		wp_register_script('awpcp-toggle-checkboxes', "{$js}/checkuncheckboxes.js", array('jquery'), $awpcp_db_version, true);


		/* admin */

		wp_register_style('awpcp-admin-style', "{$css}/awpcp-admin.css", array(), $awpcp_db_version);

		wp_register_script('awpcp-admin-general', "{$js}/admin-general.js", array('awpcp'), $awpcp_db_version, true);
		wp_register_script('awpcp-admin-settings', "{$js}/admin-settings.js", array('awpcp'), $awpcp_db_version, true);
		wp_register_script('awpcp-admin-fees', "{$js}/admin-fees.js", array('awpcp-admin-wp-table-ajax'), $awpcp_db_version, true);
		wp_register_script('awpcp-admin-credit-plans', "{$js}/admin-credit-plans.js", array('awpcp-admin-wp-table-ajax'), $awpcp_db_version, true);
		wp_register_script('awpcp-admin-listings', "{$js}/admin-listings.js", array('awpcp', 'awpcp-admin-wp-table-ajax'), $awpcp_db_version, true);
		wp_register_script('awpcp-admin-users', "{$js}/admin-users.js", array('awpcp-admin-wp-table-ajax'), $awpcp_db_version, true);

		/* frontend */

		wp_register_style('awpcp-frontend-style', "{$css}/awpcpstyle.css", array(), $awpcp_db_version);
		wp_register_style('awpcp-frontend-style-ie-6', "{$css}/awpcpstyle-ie-6.css", array('awpcp-frontend-style'), $awpcp_db_version);
		$wp_styles->add_data('awpcp-frontend-style-ie-6', 'conditional', 'lte IE 6');

		wp_register_script('awpcp-page-place-ad', "{$js}/page-place-ad.js", array('awpcp', 'awpcp-jquery-validate', 'jquery-ui-datepicker'), $awpcp_db_version, true);
		wp_register_script('awpcp-page-reply-to-ad', "{$js}/page-reply-to-ad.js", array('awpcp', 'awpcp-jquery-validate'), $awpcp_db_version, true);
		wp_register_script('awpcp-page-search-ads', "{$js}/page-search-ads.js", array('awpcp', 'awpcp-jquery-validate'), $awpcp_db_version, true);
		wp_register_script('awpcp-page-show-ad', "{$js}/page-show-ad.js", array('awpcp'), $awpcp_db_version, true);

	}

	public function register_custom_style() {
		global $awpcp_db_version;

		// load custom stylesheet if one exists in the wp-content/plugins directory:
		if (file_exists(WP_PLUGIN_DIR . '/awpcp_custom_stylesheet.css')) {
			wp_register_style('awpcp-custom-css', plugins_url('awpcp_custom_stylesheet.css'), array('awpcp-frontend-style'), $awpcp_db_version, 'all');
		}
	}

	public function enqueue_scripts() {
		if (is_admin()) {
			wp_enqueue_style('awpcp-admin-style');
			wp_enqueue_script('awpcp-admin-general');
			wp_enqueue_script('awpcp-toggle-checkboxes');
		} else {
			wp_enqueue_style('awpcp-frontend-style');
			wp_enqueue_style('awpcp-frontend-style-ie-6');
	        wp_enqueue_style('awpcp-custom-css');
	        wp_enqueue_script( 'awpcp' );
		}

		if (is_admin()) {
			// TODO: migrate the code below to use set_js_data to pass information to AWPCP scripts.
			$options = array('ajaxurl' => awpcp_ajaxurl());
			wp_localize_script('awpcp-admin-general', 'AWPCPAjaxOptions', $options);
		}
	}

	public function localize_scripts() {
		// localize jQuery Validate messages
		$this->js->set( 'default-validation-messages', array(
			'required' => __( 'This field is required.', 'AWPCP' ),
			'email' => __( 'Please enter a valid email address.', 'AWPCP' ),
			'url' => __( 'Please enter a valid URL.', 'AWPCP' ),
			'number' => __( 'Please enter a valid number.', 'AWPCP' ),
			'money' => __( 'Please enter a valid amount.', 'AWPCP' ),
		) );

		wp_localize_script('awpcp', '__awpcp_js_data', $this->js->get_data());
		wp_localize_script('awpcp', '__awpcp_js_l10n', $this->js->get_l10n());
	}

	/**
	 * Register other AWPCP settings, normally for private use.
	 */
	public function register_settings() {
		$this->settings->add_setting('private:notices', 'show-quick-start-guide-notice', '', 'checkbox', false, '');
	}

	/**
	 * @since 2.2.2
	 */
	public function register_payment_term_types($payments) {
		$payments->register_payment_term_type(new AWPCP_FeeType);
	}

	/**
	 * @since  2.2.2
	 */
	public function register_payment_methods($payments) {
		if (get_awpcp_option('activatepaypal')) {
			$payments->register_payment_method(new AWPCP_PayPalStandardPaymentGateway);
		}

		if (get_awpcp_option('activate2checkout')) {
			$payments->register_payment_method(new AWPCP_2CheckoutPaymentGateway);
		}
	}

	/**
	 * @since 3.0-beta
	 */
	public function register_widgets() {
	    register_widget("AWPCP_LatestAdsWidget");
	    register_widget('AWPCP_RandomAdWidget');
	    register_widget('AWPCP_Search_Widget');
	    register_widget( 'AWPCP_CategoriesWidget' );
	}


    /**
     * Update Ad payment status based on the transaction payment
     * status.
     *
     * The purpose of this method is to handle payment notifications sent
     * by the payment gateway after the Place Ad operation has been completed.
     *
     * @since 2.2.2
     */
    private function update_ad_payment_status($transaction) {
        $ad = AWPCP_Ad::find_by_id($transaction->get('ad-id'));

        if (is_null($ad)) return;

        if ($transaction->payment_is_completed() || $transaction->payment_is_pending() || $transaction->payment_is_failed()) {
            $ad->payment_status = $transaction->payment_status;
        }

        $ad->save();
    }

	/**
	 * @since  2.2.2
	 */
	public function process_transaction($transaction) {
		switch ($transaction->get_status()) {
            case AWPCP_Payment_Transaction::STATUS_OPEN:
                if (awpcp_current_user_is_admin()/* || get_awpcp_option('freepay') == 0*/)
                    $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_NOT_REQUIRED;
                break;
		}
	}

	/**
	 * @since 2.2.2
	 */
	public function process_ad_payment_transaction($transaction) {
        if (!in_array($transaction->get('context'), array('renew-ad', 'place-ad')));
            return;

        switch ($transaction->get_status()) {
            case AWPCP_Payment_Transaction::STATUS_NEW:
                break;

            case AWPCP_Payment_Transaction::STATUS_OPEN:
                break;

            case AWPCP_Payment_Transaction::STATUS_PAYMENT_COMPLETED:
            case AWPCP_Payment_Transaction::STATUS_COMPLETED:
                $this->update_ad_payment_status($transaction);
                break;
        }
	}

	public function get_missing_pages() {
		global $awpcp, $wpdb;

		// pages that are registered in the code but no referenced in the DB
		$shortcodes = awpcp_pages();
		$registered = array_keys($shortcodes);
		$referenced = $wpdb->get_col('SELECT page FROM ' . AWPCP_TABLE_PAGES);
		$missing = array_diff($registered, $referenced);

		// pages that are referenced but no longer registered in the code
		$leftovers = array_diff($referenced, $registered);

		$excluded = array_merge(array('view-categories-page-name'), $leftovers);

		$query = 'SELECT pages.page, pages.id, posts.ID post ';
		$query.= 'FROM ' . AWPCP_TABLE_PAGES . ' AS pages ';
		$query.= 'LEFT JOIN ' . $wpdb->posts . ' AS posts ON (posts.ID = pages.id) ';
		$query.= 'WHERE posts.ID IS NULL ';

		if (!empty($excluded)) {
			$query.= " AND pages.page NOT IN ('" . join("','", $excluded) . "')";
		}

		$orphan = $wpdb->get_results($query);

		// if a page is registered in the code but there is no reference
		// of it in the database, create it.
		foreach ($missing as $page) {
			$item = new stdClass();
			$item->page = $page;
			$item->id = -1;
			$item->post = null;
			array_push($orphan, $item);
		}

		return $orphan;
	}

	public function restore_pages() {
		global $wpdb;

		$shortcodes = awpcp_pages();
		$missing = $this->get_missing_pages();
		$pages = awpcp_get_properties($missing, 'page');

		// If we are restoring the main page, let's do it first!
		if (($p = array_search('main-page-name', $pages)) !== FALSE) {
			// put the main page as the first page to restore
			array_splice($missing, 0, 0, array($missing[$p]));
			array_splice($missing, $p + 1, 1);
		}

		foreach($missing as $page) {
			$refname = $page->page;
			$name = get_awpcp_option($refname);
			if (strcmp($refname, 'main-page-name') == 0) {
				awpcp_create_pages($name, $subpages=false);
			} else {
				awpcp_create_subpage($refname, $name, $shortcodes[$refname][1]);
			}
		}

		flush_rewrite_rules();
	}
}

function awpcp() {
	global $awpcp;

	if (!is_object($awpcp)) {
		$awpcp = new AWPCP();
	}

	return $awpcp;
}

awpcp();


$uploadfoldername = get_awpcp_option('uploadfoldername', "uploads");

define('MAINUPLOADURL', $wpcontenturl .'/' .$uploadfoldername);
define('MAINUPLOADDIR', $wpcontentdir .'/' .$uploadfoldername);
define('AWPCPUPLOADURL', $wpcontenturl .'/' .$uploadfoldername .'/awpcp');
define('AWPCPUPLOADDIR', $wpcontentdir .'/' .$uploadfoldername .'/awpcp/');
define('AWPCPTHUMBSUPLOADURL', $wpcontenturl .'/' .$uploadfoldername .'/awpcp/thumbs');
define('AWPCPTHUMBSUPLOADDIR', $wpcontentdir .'/' .$uploadfoldername .'/awpcp/thumbs/');
define('MENUICO', $awpcp_imagesurl .'/menuico.png');

global $awpcpthumbsurl;
global $hascaticonsmodule;
global $hasregionsmodule;
global $haspoweredbyremovalmodule;
global $hasgooglecheckoutmodule;
global $hasextrafieldsmodule;
global $hasrssmodule;
global $hasfeaturedadsmodule;

$hasextrafieldsmodule = $hasextrafieldsmodule ? true : false;
$hasregionsmodule = $hasregionsmodule ? true : false;
$hasfeaturedadsmodule = $hasfeaturedadsmodule ? true : false;
$hasrssmodule = $hasrssmodule ? true : false;

$awpcpthumbsurl = AWPCPTHUMBSUPLOADURL;
$hascaticonsmodule = 0;
$haspoweredbyremovalmodule = 0;
$hasgooglecheckoutmodule = 0;


if (!defined('AWPCP_REGION_CONTROL_MODULE') && file_exists(AWPCP_DIR . "/awpcp_region_control_module.php")) {
	require_once(AWPCP_DIR . "/awpcp_region_control_module.php");
	$hasregionsmodule = true;
}

if (!defined('AWPCP_EXTRA_FIELDS_MODULE') && file_exists(AWPCP_DIR . "/awpcp_extra_fields_module.php")) {
	require_once(AWPCP_DIR . "/awpcp_extra_fields_module.php");
	$hasextrafieldsmodule = true;
}

if (!defined('AWPCP_RSS_MODULE') && file_exists(AWPCP_DIR . "/awpcp_rss_module.php")) {
	require_once(AWPCP_DIR . "/awpcp_rss_module.php");
	$hasrssmodule = true;
}

if (!defined('AWPCP_GOOGLE_CHECKOUT_MODULE') && file_exists(AWPCP_DIR . "/awpcp_google_checkout_module.php")) {
	require_once(AWPCP_DIR . "/awpcp_google_checkout_module.php");
	$hasgooglecheckoutmodule = true;
}

if (file_exists(AWPCP_DIR . "/awpcp_category_icons_module.php")) {
	require_once(AWPCP_DIR . "/awpcp_category_icons_module.php");
	$hascaticonsmodule=1;
}

if (file_exists(AWPCP_DIR . "/awpcp_remove_powered_by_module.php")) {
	require_once(AWPCP_DIR . "/awpcp_remove_powered_by_module.php");
	$haspoweredbyremovalmodule=1;
}


/**
 * Returns the IDs of the pages used by the AWPCP plugin.
 */
function exclude_awpcp_child_pages($excluded=array()) {
	global $wpdb, $table_prefix;

	$awpcp_page_id = awpcp_get_page_id_by_ref('main-page-name');

	if (empty($awpcp_page_id)) {
		return array();
	}

	$query = "SELECT ID FROM {$table_prefix}posts ";
	$query.= "WHERE post_parent=$awpcp_page_id AND post_content LIKE '%AWPCP%'";
	$res = awpcp_query($query, __LINE__);

	$awpcpchildpages = array();
	while ($rsrow=mysql_fetch_row($res)) {
		$awpcpchildpages[] = $rsrow[0];
	}

	return array_merge($awpcpchildpages, $excluded);
}



// PROGRAM FUNCTIONS

/**
 * Return an array of refnames for pages associated with one or more
 * rewrite rules.
 *
 * @since 2.1.3
 * @return array Array of page refnames.
 */
function awpcp_pages_with_rewrite_rules() {
	return array(
		'main-page-name',
		'show-ads-page-name',
		'reply-to-ad-page-name',
		'browse-categories-page-name',
		'payment-thankyou-page-name',
		'payment-cancel-page-name'
	);
}

function awpcp_add_rewrite_rules($rules) {
	$pages = awpcp_pages_with_rewrite_rules();
	$patterns = array();

	foreach ($pages as $refname) {
		if ($id = awpcp_get_page_id_by_ref($refname)) {
			if ($page = get_page($id)) {
				$patterns[$refname] = get_page_uri($page->ID);
			}
		}
	}

	// Payments API rewrite rules
	add_rewrite_rule('awpcpx/payments/return/([a-zA-Z0-9]+)',
		'index.php?awpcpx=1&module=payments&action=return&awpcp-txn=$matches[1]', 'top');
	add_rewrite_rule('awpcpx/payments/notify/([a-zA-Z0-9]+)',
		'index.php?awpcpx=1&module=payments&action=notify&awpcp-txn=$matches[1]', 'top');
	add_rewrite_rule('awpcpx/payments/cancel/([a-zA-Z0-9]+)',
		'index.php?awpcpx=1&module=payments&action=cancel&awpcp-txn=$matches[1]', 'top');

	if (isset($patterns['show-ads-page-name'])) {
		add_rewrite_rule('('.$patterns['show-ads-page-name'].')/(.+?)/(.+?)',
						 'index.php?pagename=$matches[1]&id=$matches[2]', 'top');
	}

	if (isset($patterns['reply-to-ad-page-name'])) {
		add_rewrite_rule('('.$patterns['reply-to-ad-page-name'].')/(.+?)/(.+?)',
						 'index.php?pagename=$matches[1]&id=$matches[2]', 'top');
	}

	if (isset($patterns['browse-categories-page-name'])) {
		add_rewrite_rule('('.$patterns['browse-categories-page-name'].')/(.+?)/(.+?)',
						 'index.php?pagename=$matches[1]&cid=$matches[2]&a=browsecat',
						 'top');
	}

	if (isset($patterns['payment-thankyou-page-name'])) {
		add_rewrite_rule('('.$patterns['payment-thankyou-page-name'].')/([a-zA-Z0-9]+)',
						 'index.php?pagename=$matches[1]&awpcp-txn=$matches[2]', 'top');
	}

	if (isset($patterns['payment-cancel-page-name'])) {
		add_rewrite_rule('('.$patterns['payment-cancel-page-name'].')/([a-zA-Z0-9]+)',
						 'index.php?pagename=$matches[1]&awpcp-txn=$matches[2]', 'top');
	}

	$view_categories = sanitize_title(get_awpcp_option('view-categories-page-name'));

	if (isset($patterns['main-page-name'])) {
		add_rewrite_rule('('.$patterns['main-page-name'].')/('.$view_categories.')',
						 'index.php?pagename=$matches[1]&layout=2&cid='.$view_categories,
						 'top');
		add_rewrite_rule('('.$patterns['main-page-name'].')/(setregion)/(.+?)/(.+?)',
						 'index.php?pagename=$matches[1]&regionid=$matches[3]&a=setregion',
						 'top');
		add_rewrite_rule('('.$patterns['main-page-name'].')/(classifiedsrss)/(\d+)',
						 'index.php?pagename=$matches[1]&awpcp-action=rss&cid=$matches[3]',
						 'top');
		add_rewrite_rule('('.$patterns['main-page-name'].')/(classifiedsrss)',
						 'index.php?pagename=$matches[1]&awpcp-action=rss',
						 'top');
	}

	return $rules;
}


/**
 * Register AWPCP query vars
 */
function awpcp_query_vars($query_vars) {
	$vars = array(
		// API
		'awpcpx',
		'module',
		'action',

		// Payments API
		'awpcp-txn',

		// misc
		"cid",
		"i",
		"id",
		"layout",
		"regionid",
		'awpcp-action'
	);

	return array_merge($query_vars, $vars);
}


/**
 * Set canonical URL to the Ad URL when in viewing on of AWPCP Ads
 */
function awpcp_rel_canonical() {
	if (!is_singular())
		return;

	global $wp_the_query;
	if (!$page = $wp_the_query->get_queried_object_id()) {
		return;
	}

	if ($page != awpcp_get_page_id_by_ref('show-ads-page-name')) {
		return rel_canonical();
	}

	$ad = intval(awpcp_request_param('id', ''));
	$ad = empty($ad) ? intval(get_query_var('id')) : $ad;

	if (empty($ad)) {
		$link = get_permalink($page);
	} else {
		$link = url_showad($ad);
	}

	echo "<link rel='canonical' href='$link' />\n";
}


/**
 * Overwrittes WP canonicalisation to ensure our rewrite rules
 * work, even when the main AWPCP page is also the front page or
 * when the requested page slug is 'awpcp'.
 *
 * Required for the View Categories and Classifieds RSS rules to work
 * when AWPCP main page is also the front page.
 *
 * http://wordpress.stackexchange.com/questions/51530/rewrite-rules-problem-when-rule-includes-homepage-slug
 */
function awpcp_redirect_canonical($redirect_url, $requested_url) {
	global $wp_query;

	$ids = awpcp_get_page_ids_by_ref(awpcp_pages_with_rewrite_rules());

	// do not redirect requests to AWPCP pages with rewrite rules
	if (is_page() && in_array(awpcp_request_param('page_id', 0), $ids)) {
		$redirect_url = $requested_url;

	// do not redirect requests to the front page, if any of the AWPCP pages
	// with rewrite rules is the front page
	} else if (is_page() && !is_feed() && isset($wp_query->queried_object) &&
			  'page' == get_option('show_on_front') && in_array($wp_query->queried_object->ID, $ids) &&
			   $wp_query->queried_object->ID == get_option('page_on_front'))
	{
		$redirect_url = $requested_url;
	}

	// $id = awpcp_get_page_id_by_ref('main-page-name');

	// // do not redirect direct requests to AWPCP main page
	// if (is_page() && !empty($_GET['page_id']) && $id == $_GET['page_id']) {
	// 	$redirect_url = $requested_url;

	// // do not redirect request to the front page, if AWPCP main page is
	// // the front page
	// } else if (is_page() && !is_feed() && isset($wp_query->queried_object) &&
	// 		  'page' == get_option('show_on_front') && $id == $wp_query->queried_object->ID &&
	// 		   $wp_query->queried_object->ID == get_option('page_on_front'))
	// {
	// 	$redirect_url = $requested_url;
	// }

	return $redirect_url;
}
add_filter('redirect_canonical', 'awpcp_redirect_canonical', 10, 2);
