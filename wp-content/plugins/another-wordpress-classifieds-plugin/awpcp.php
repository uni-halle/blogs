<?php
/*
 Plugin Name: Another Wordpress Classifieds Plugin (AWPCP)
 Plugin URI: http://www.awpcp.com
 Description: AWPCP - A plugin that provides the ability to run a free or paid classified ads service on your wordpress blog. <strong>!!!IMPORTANT!!!</strong> Whether updating a previous installation of Another Wordpress Classifieds Plugin or installing Another Wordpress Classifieds Plugin for the first time, please backup your wordpress database before you install/uninstall/activate/deactivate/upgrade Another Wordpress Classifieds Plugin.
 Version: 3.3.2
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

define( 'AWPCP_BASENAME', basename( dirname( __FILE__ ) ) );
define( 'AWPCP_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'AWPCP_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

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
$imagespath = $awpcp_plugin_path . '/resources/images';
$awpcp_imagesurl = $awpcp_plugin_url .'/resources/images';


// common
require_once(AWPCP_DIR . "/debug.php");
require_once(AWPCP_DIR . "/functions.php");
require_once( AWPCP_DIR . "/includes/functions/format.php" );

$nameofsite = awpcp_get_blog_name();

// cron
require_once(AWPCP_DIR . "/cron.php");

// other resources
require_once(AWPCP_DIR . "/dcfunctions.php");
require_once(AWPCP_DIR . "/functions_awpcp.php");
require_once(AWPCP_DIR . "/upload_awpcp.php");

// API & Classes
require_once(AWPCP_DIR . "/includes/exceptions.php");

require_once(AWPCP_DIR . "/includes/compatibility/compatibility.php");
require_once(AWPCP_DIR . "/includes/compatibility/class-all-in-one-seo-pack-plugin-integration.php");
require_once(AWPCP_DIR . "/includes/compatibility/class-facebook-plugin-integration.php");
require_once( AWPCP_DIR . "/includes/compatibility/class-yoast-wordpress-seo-plugin-integration.php" );

require_once( AWPCP_DIR . "/includes/helpers/class-easy-digital-downloads.php" );
require_once( AWPCP_DIR . "/includes/helpers/class-licenses-manager.php" );
require_once( AWPCP_DIR . '/includes/helpers/class-module.php' );
require_once( AWPCP_DIR . "/includes/helpers/class-modules-manager.php" );
require_once( AWPCP_DIR . "/includes/helpers/class-modules-updater.php" );

require_once( AWPCP_DIR . '/includes/helpers/class-admin-page-links-builder.php' );
require_once(AWPCP_DIR . "/includes/helpers/class-akismet-wrapper-base.php");
require_once(AWPCP_DIR . "/includes/helpers/class-akismet-wrapper.php");
require_once(AWPCP_DIR . "/includes/helpers/class-akismet-wrapper-factory.php");
require_once(AWPCP_DIR . "/includes/helpers/class-awpcp-request.php");
require_once( AWPCP_DIR . '/includes/helpers/class-facebook-cache-helper.php' );
require_once(AWPCP_DIR . "/includes/helpers/class-file-cache.php");
require_once( AWPCP_DIR . "/includes/helpers/class-http.php" );
require_once( AWPCP_DIR . "/includes/helpers/class-listing-akismet-data-source.php" );
require_once( AWPCP_DIR . "/includes/helpers/class-listing-renderer.php" );
require_once( AWPCP_DIR . "/includes/helpers/class-listing-reply-akismet-data-source.php" );
require_once( AWPCP_DIR . "/includes/helpers/class-page-title-builder.php" );
require_once( AWPCP_DIR . "/includes/helpers/class-payment-transaction-helper.php" );
require_once( AWPCP_DIR . "/includes/helpers/class-send-listing-to-facebook-helper.php" );
require_once( AWPCP_DIR . "/includes/helpers/class-send-to-facebook-helper.php" );
require_once( AWPCP_DIR . "/includes/helpers/class-spam-filter.php" );
require_once( AWPCP_DIR . "/includes/helpers/class-spam-submitter.php" );
require_once( AWPCP_DIR . '/includes/helpers/facebook.php' );
require_once(AWPCP_DIR . "/includes/helpers/list-table.php");
require_once(AWPCP_DIR . "/includes/helpers/email.php");
require_once(AWPCP_DIR . "/includes/helpers/javascript.php");
require_once(AWPCP_DIR . "/includes/helpers/captcha.php");
require_once(AWPCP_DIR . "/includes/helpers/widgets/categories-dropdown.php");
require_once(AWPCP_DIR . "/includes/helpers/widgets/multiple-region-selector.php");
require_once(AWPCP_DIR . "/includes/helpers/widgets/class-asynchronous-tasks-component.php");
require_once(AWPCP_DIR . "/includes/helpers/widgets/class-file-manager-component.php");
require_once(AWPCP_DIR . "/includes/helpers/widgets/class-users-dropdown.php");
require_once(AWPCP_DIR . "/includes/helpers/widgets/class-users-autocomplete.php");

require_once(AWPCP_DIR . "/includes/models/class-media.php");
require_once(AWPCP_DIR . "/includes/models/ad.php");
require_once(AWPCP_DIR . "/includes/models/category.php");
require_once(AWPCP_DIR . "/includes/models/image.php");
require_once(AWPCP_DIR . "/includes/models/payment-transaction.php");

require_once( AWPCP_DIR . "/includes/db/class-database-column-creator.php" );

require_once( AWPCP_DIR . "/includes/views/class-ajax-handler.php" );
require_once( AWPCP_DIR . "/includes/views/class-base-page.php" );
require_once( AWPCP_DIR . "/includes/views/class-file-action-ajax-handler.php" );
require_once( AWPCP_DIR . "/includes/views/class-step-decorator.php" );
require_once( AWPCP_DIR . "/includes/views/class-payment-step-decorator.php" );
require_once( AWPCP_DIR . "/includes/views/class-prepare-transaction-for-payment-step-decorator.php" );
require_once( AWPCP_DIR . "/includes/views/class-set-credit-plan-step-decorator.php" );
require_once( AWPCP_DIR . "/includes/views/class-set-payment-method-step-decorator.php" );
require_once( AWPCP_DIR . "/includes/views/class-set-transaction-status-to-open-step-decorator.php" );
require_once( AWPCP_DIR . "/includes/views/class-set-transaction-status-to-checkout-step-decorator.php" );
require_once( AWPCP_DIR . "/includes/views/class-set-transaction-status-to-completed-step-decorator.php" );
require_once( AWPCP_DIR . "/includes/views/class-skip-payment-step-if-payment-is-not-required.php" );
require_once( AWPCP_DIR . "/includes/views/class-users-autocomplete-ajax-handler.php" );
require_once( AWPCP_DIR . "/includes/views/class-verify-credit-plan-was-set-step-decorator.php" );
require_once( AWPCP_DIR . "/includes/views/class-verify-payment-can-be-processed-step-decorator.php" );
require_once( AWPCP_DIR . "/includes/views/class-verify-transaction-exists-step-decorator.php" );
// load frontend views first, some frontend pages are required in admin pages
require_once( AWPCP_DIR . '/includes/views/frontend/buy-credits/class-buy-credits-page.php');
require_once( AWPCP_DIR . "/includes/views/frontend/buy-credits/class-buy-credits-page-select-credit-plan-step.php" );
require_once( AWPCP_DIR . "/includes/views/frontend/buy-credits/class-buy-credits-page-checkout-step.php" );
require_once( AWPCP_DIR . "/includes/views/frontend/buy-credits/class-buy-credits-page-payment-completed-step.php" );
require_once( AWPCP_DIR . "/includes/views/frontend/buy-credits/class-buy-credits-page-final-step.php" );
require_once( AWPCP_DIR . "/includes/views/frontend/class-categories-list-walker.php" );
require_once( AWPCP_DIR . "/includes/views/frontend/class-categories-renderer.php" );
require_once( AWPCP_DIR . "/includes/views/frontend/class-category-shortcode.php" );
require_once( AWPCP_DIR . "/includes/views/admin/class-fee-payment-terms-notices.php" );
require_once( AWPCP_DIR . "/includes/views/admin/class-credit-plans-notices.php" );
require_once( AWPCP_DIR . "/includes/views/admin/class-categories-checkbox-list-walker.php" );
require_once( AWPCP_DIR . "/includes/views/admin/listings/class-listing-action-admin-page.php" );
require_once( AWPCP_DIR . "/includes/views/admin/listings/class-renew-listings-admin-page.php" );
require_once( AWPCP_DIR . "/includes/views/admin/listings/class-send-listing-to-facebook-admin-page.php" );
require_once( AWPCP_DIR . "/includes/views/admin/listings/class-listings-table-search-by-id-condition.php" );
require_once( AWPCP_DIR . "/includes/views/admin/listings/class-listings-table-search-by-keyword-condition.php" );
require_once( AWPCP_DIR . "/includes/views/admin/listings/class-listings-table-search-by-location-condition.php" );
require_once( AWPCP_DIR . "/includes/views/admin/listings/class-listings-table-search-by-payer-email-condition.php" );
require_once( AWPCP_DIR . "/includes/views/admin/listings/class-listings-table-search-by-title-condition.php" );
require_once( AWPCP_DIR . "/includes/views/admin/listings/class-listings-table-search-by-user-condition.php" );
require_once( AWPCP_DIR . "/includes/views/admin/listings/class-listings-table-search-conditions-parser.php" );
require_once( AWPCP_DIR . "/includes/views/admin/account-balance/class-account-balance-page.php" );
require_once( AWPCP_DIR . "/includes/views/admin/account-balance/class-account-balance-page-summary-step.php" );
require_once( AWPCP_DIR . "/includes/views/admin/settings/class-update-license-status-request-handler.php" );

require_once( AWPCP_DIR . "/includes/settings/class-credit-plans-settings.php" );
require_once( AWPCP_DIR . "/includes/settings/class-listings-moderation-settings.php" );
require_once( AWPCP_DIR . "/includes/settings/class-payment-general-settings.php" );
require_once( AWPCP_DIR . "/includes/settings/class-registration-settings.php" );

require_once( AWPCP_DIR . "/includes/upgrade/class-fix-empty-media-mime-type-upgrade-routine.php" );

require_once( AWPCP_DIR . "/includes/class-awpcp-listings-api.php" );
require_once( AWPCP_DIR . "/includes/class-fees-collection.php" );
require_once( AWPCP_DIR . "/includes/class-listing-payment-transaction-handler.php" );
require_once( AWPCP_DIR . "/includes/class-listing-is-about-to-expire-notification.php" );
require_once( AWPCP_DIR . "/includes/class-listings-collection.php" );
require_once( AWPCP_DIR . "/includes/class-listings-metadata.php" );
require_once( AWPCP_DIR . "/includes/class-media-api.php" );
require_once( AWPCP_DIR . "/includes/class-secure-url-redirection-handler.php" );
require_once( AWPCP_DIR . "/includes/class-users-collection.php" );
require_once(AWPCP_DIR . "/includes/payments-api.php");
require_once(AWPCP_DIR . "/includes/regions-api.php");
require_once(AWPCP_DIR . "/includes/settings-api.php");

require_once(AWPCP_DIR . "/includes/credit-plan.php");

require_once(AWPCP_DIR . "/includes/payment-term-type.php");
require_once(AWPCP_DIR . "/includes/payment-term.php");
require_once(AWPCP_DIR . "/includes/payment-term-fee-type.php");
require_once(AWPCP_DIR . "/includes/payment-term-fee.php");

require_once(AWPCP_DIR . "/includes/payment-gateway.php");
require_once(AWPCP_DIR . "/includes/payment-gateway-paypal-standard.php");
require_once(AWPCP_DIR . "/includes/payment-gateway-2checkout.php");

require_once(AWPCP_DIR . "/includes/payment-terms-table.php");

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

	public $modules_manager;
    public $modules_updater;
	public $settings = null;
	public $payments = null;
	public $js = null;

	public $flush_rewrite_rules = false;

	public function __construct() {
		global $awpcp_db_version;

		$this->version = $awpcp_db_version;

		// stored options are loaded when the settings API is instatiated
		$this->settings = AWPCP_Settings_API::instance();
		$this->js = AWPCP_JavaScript::instance();
        $this->installer = AWPCP_Installer::instance();
	}

    public function bootstrap() {
        if ( $this->settings->get_option( 'activatelanguages' ) ) {
            awpcp_load_plugin_textdomain( __FILE__, 'AWPCP' );
        }

        $this->settings->set_runtime_option( 'easy-digital-downloads-store-url', 'http://awpcp.com' );

        // register settings, this will define default values for settings
        // that have never been stored
        $this->settings->register_settings();

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
	 * Check if AWPCP DB version corresponds to current AWPCP plugin version.
	 *
	 * @deprecated since 3.0.2
	 */
	public function updated() {
		_deprecated_function( __FUNCTION__, '3.0.2', 'AWPCP::is_updated()' );
		return false;
	}

	/**
	 * Check if AWPCP DB version corresponds to current AWPCP plugin version.
	 */
	public function is_up_to_date() {
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
		global $wpdb;

		if (!$this->is_up_to_date()) {
			$this->installer->install();
			// we can't call flush_rewrite_rules() because
			// $wp_rewrite is not available yet. It is initialized
			// after plugins_load hook is executed.
			$this->flush_rewrite_rules = true;
		}

		if (!$this->is_up_to_date()) {
			return;
		}

		$this->setup_register_settings_handlers();

		// Ad metadata integration.
        $wpdb->awpcp_admeta = AWPCP_TABLE_AD_META;

		$this->settings->setup();
		$this->modules_manager = awpcp_modules_manager();
        $this->modules_updater = awpcp_modules_updater();
		$this->payments = awpcp_payments_api();
		$this->listings = awpcp_listings_api();

		$this->admin = new AWPCP_Admin();
		$this->panel = new AWPCP_User_Panel();

		$this->compatibility = new AWPCP_Compatibility();
		$this->compatibility->load_plugin_integrations();

        add_action( 'generate_rewrite_rules', array( $this, 'clear_categories_list_cache' ) );

		add_action( 'init', array($this, 'init' ));
		add_action( 'init', array($this, 'register_custom_style'), 1000000 );

		add_action( 'admin_init', array( $this, 'check_compatibility_with_premium_modules' ) );
		add_action('admin_notices', array($this, 'admin_notices'));
		add_action( 'admin_notices', array( $this->modules_manager, 'show_admin_notices' ) );

		add_action('awpcp_register_settings', array($this, 'register_settings'));
		add_action( 'awpcp-register-payment-term-types', array( $this, 'register_payment_term_types' ) );
		add_action( 'awpcp-register-payment-methods', array( $this, 'register_payment_methods' ) );

        add_filter( 'pre_set_site_transient_update_plugins', array( $this->modules_updater, 'filter_plugins_version_information' ) );
        add_filter( 'plugins_api', array( $this->modules_updater, 'filter_detailed_plugin_information' ), 10, 3 );
        add_filter( 'http_request_args', array( $this->modules_updater, 'filter_http_request_args' ), 10, 2 );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1000 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1000 );
		add_action( 'wp_footer', array( $this, 'localize_scripts' ) );
		add_action( 'admin_footer', array( $this, 'localize_scripts' ) );

		// some upgrade operations can't be done in background.
		// if one those is pending, we will disable all other features
		// until the user executes the upgrade operaton
		if ( ! get_option( 'awpcp-pending-manual-upgrade' ) ) {
    		$this->pages = new AWPCP_Pages();

            add_action( 'awpcp-process-payment-transaction', array( $this, 'process_transaction_update_payment_status' ) );
            add_action( 'awpcp-process-payment-transaction', array( $this, 'process_transaction_notify_wp_affiliate_platform' ) );

            add_action( 'wp_ajax_awpcp-get-regions-options', array( $this, 'get_regions_options' ) );
            add_action( 'wp_ajax_nopriv_awpcp-get-regions-options', array( $this, 'get_regions_options' ) );

    		// actions and filters from functions_awpcp.php
    		add_action('phpmailer_init','awpcp_phpmailer_init_smtp');

    		add_action('widgets_init', array($this, 'register_widgets'));

    		if (get_awpcp_option('awpcppagefilterswitch') == 1) {
    			add_filter('wp_list_pages_excludes', 'exclude_awpcp_child_pages');
    		}

    		add_filter('cron_schedules', 'awpcp_cron_schedules');

    		awpcp_schedule_activation();

    		$this->modules_manager->load_modules();
        }
	}

	public function setup_register_settings_handlers() {
		add_action( 'awpcp_register_settings', array( new AWPCP_CreditPlansSettings, 'register_settings' ) );
		add_action( 'awpcp_register_settings', array( new AWPCP_RegistrationSettings, 'register_settings' ) );

		$listings_moderation_settings = new AWPCP_ListingsModerationSettings;
		add_action( 'awpcp_register_settings', array( $listings_moderation_settings, 'register_settings' ) );
		add_filter( 'awpcp_validate_settings', array( $listings_moderation_settings, 'validate_all_settings' ), 10, 2 );
		add_filter( 'awpcp_validate_settings_listings-settings', array( $listings_moderation_settings, 'validate_group_settings' ), 10, 2 );

		$payment_general_settings = new AWPCP_PaymentGeneralSettings;
		add_action( 'awpcp_register_settings', array( $payment_general_settings, 'register_settings' ) );
		add_filter( 'awpcp_validate_settings_payment-settings', array( $payment_general_settings, 'validate_group_settings' ), 10, 2 );
	}

	public function init() {
		$this->initialize_session();

        // load resources always required
        $facebook_cache_helper = awpcp_facebook_cache_helper();
        add_action( 'awpcp-clear-ad-facebook-cache', array( $facebook_cache_helper, 'handle_clear_cache_event_hook' ), 10, 1 );

        $send_new_listings_to_facebook_helper = awpcp_send_listing_to_facebook_helper();
        add_action( 'awpcp-listing-facebook-cache-cleared', array( $send_new_listings_to_facebook_helper, 'schedule_listing_if_necessary' ) );
        add_action( 'awpcp-send-listing-to-facebook', array( $send_new_listings_to_facebook_helper, 'send_listing_to_facebook' ) );

        add_action( 'awpcp-place-ad', array( $this, 'clear_categories_list_cache' ) );
        add_action( 'awpcp_approve_ad', array( $this, 'clear_categories_list_cache' ) );
        add_action( 'awpcp_edit_ad', array( $this, 'clear_categories_list_cache' ) );
        add_action( 'awpcp_disable_ad', array( $this, 'clear_categories_list_cache' ) );
        add_action( 'awpcp_delete_ad', array( $this, 'clear_categories_list_cache' ) );
        add_action( 'awpcp-category-added', array( $this, 'clear_categories_list_cache' ) );
        add_action( 'awpcp-category-edited', array( $this, 'clear_categories_list_cache' ) );
        add_action( 'awpcp-category-deleted', array( $this, 'clear_categories_list_cache' ) );
        add_action( 'awpcp-pages-updated', array( $this, 'clear_categories_list_cache' ) );

        // load resources required both in front end and admin screens, but not during ajax calls.
        if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
            $listing_payment_transaction_handler = awpcp_listing_payment_transaction_handler();
            add_action( 'awpcp-transaction-status-updated', array( $listing_payment_transaction_handler, 'transaction_status_updated' ), 10, 2 );
            add_filter( 'awpcp-process-payment-transaction', array( $listing_payment_transaction_handler, 'process_payment_transaction' ) );

            add_action( 'awpcp-place-ad', array( $facebook_cache_helper, 'on_place_ad' ) );
            add_action( 'awpcp_approve_ad', array( $facebook_cache_helper, 'on_approve_ad' ) );
            add_action( 'awpcp_edit_ad', array( $facebook_cache_helper, 'on_edit_ad' ) );
        }

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            // load resources required to handle Ajax requests only.
            $handler = awpcp_users_autocomplete_ajax_handler();
            add_action( 'wp_ajax_awpcp-autocomplete-users', array( $handler, 'ajax' ) );
            add_action( 'wp_ajax_nopriv_awpcp-autocomplete-users', array( $handler, 'ajax' ) );

            $handler = awpcp_set_image_as_primary_ajax_handler();
            add_action( 'wp_ajax_awpcp-set-image-as-primary', array( $handler, 'ajax' ) );
            add_action( 'wp_ajax_nopriv_awpcp-set-image-as-primary', array( $handler, 'ajax' ) );

            $handler = awpcp_update_file_enabled_status_ajax_handler();
            add_action( 'wp_ajax_awpcp-update-file-enabled-status', array( $handler, 'ajax' ) );
            add_action( 'wp_ajax_nopriv_awpcp-update-file-enabled-status', array( $handler, 'ajax' ) );

            $handler = awpcp_delete_file_ajax_handler();
            add_action( 'wp_ajax_awpcp-delete-file', array( $handler, 'ajax' ) );
            add_action( 'wp_ajax_nopriv_awpcp-delete-file', array( $handler, 'ajax' ) );
        } else if ( is_admin() && awpcp_current_user_is_admin() ) {
            // load resources required in admin screens only, visible to admin users only.
            add_action( 'admin_notices', array( awpcp_fee_payment_terms_notices(), 'dispatch' ) );
            add_action( 'admin_notices', array( awpcp_credit_plans_notices(), 'dispatch' ) );

            $handler = awpcp_update_license_status_request_handler();
            add_action( 'admin_init', array( $handler, 'dispatch' ) );
        } else if ( is_admin() ) {
            // load resources required in admin screens only
        } else {
            // load resources required in frontend screens only.
            add_action( 'template_redirect', array( new AWPCP_SecureURLRedirectionHandler(), 'dispatch' ) );
        }

		if (!get_option('awpcp_installationcomplete', 0)) {
			update_option('awpcp_installationcomplete', 1);
			awpcp_create_pages(__('AWPCP', 'AWPCP'));
			$this->flush_rewrite_rules = true;
		}

        if ( get_option( 'awpcp-enable-fix-media-mime-type-upgrde' ) ) {
            awpcp_fix_empty_media_mime_type_upgrade_routine()->run();
        }

		if ( $this->flush_rewrite_rules || get_option( 'awpcp-flush-rewrite-rules' ) ) {
			flush_rewrite_rules();
		}

		$this->register_scripts();
	}

	public function admin_notices() {
		foreach (awpcp_get_property($this, 'errors', array()) as $error) {
			echo awpcp_print_error($error);
		}

		if ( ! function_exists( 'imagecreatefrompng' ) ) {
			echo $this->missing_gd_library_notice();
		}
	}

	private function missing_gd_library_notice() {
        $message = __( "AWPCP requires the graphics processing library GD and it is not installed. Contact your web host to fix this.", "AWPCP" );
        $message = sprintf( '<strong>%s</strong> %s', __( 'Warning', 'AWPCP' ), $message );
        return awpcp_print_error( $message );
	}

	/**
	 * Returns information about available and installed
	 * premium modules.
	 *
	 * @since  3.0
	 */
	public function get_premium_modules_information() {
		static $modules = null;

		if ( is_null( $modules ) ) {
			$modules = array(
				'attachments' => array(
					'name' => __( 'Attachments', 'AWPCP' ),
					'url' => 'http://awpcp.com/premium-modules/attachments-module/?ref=panel',
					'installed' => defined( 'AWPCP_ATTACHMENTS_MODULE' ),
					'version' => 'AWPCP_ATTACHMENTS_MODULE_DB_VERSION',
					'required' => '3.2.4',
				),
				'authorize.net' => array(
					'name' => __(  'Authorize.Net', 'AWPCP'  ),
					'url' => 'http://www.awpcp.com/premium-modules/authorizenet-payment-module/?ref=user-panel',
					'installed' => defined( 'AWPCP_AUTHORIZE_NET_MODULE' ),
					'version' => 'AWPCP_AUTHORIZE_NET_MODULE_DB_VERSION',
					'required' => '3.0.3',
				),
				'buddypress-listings' => array(
					'name' => __( 'BuddyPress Listings', 'AWPCP' ),
					'url' => 'http://www.awpcp.com/premium-modules/?ref=panel',
					'installed' => defined( 'AWPCP_BUDDYPRESS_LISTINGS_MODULE_DB_VERSION' ),
					'version' => 'AWPCP_BUDDYPRESS_LISTINGS_MODULE_DB_VERSION',
					'required' => '1.0.3',
				),
                'campaign-manager' => array(
                    'name' => __( 'Campaign Manager', 'AWPCP' ),
                    'url' => 'http://www.awpcp.com/',
                    'installed' => defined( 'AWPCP_CAMPAIGN_MANAGER_MODULE' ),
                    'version' => 'AWPCP_CAMPAIGN_MANAGER_MODULE_DB_VERSION',
                    'required' => '1.0.0-RC4',
                ),
				'category-icons' => array(
					'name' => __( 'Category Icons', 'AWPCP' ),
					'url' => 'http://www.awpcp.com/premium-modules/category-icons-module?ref=panel',
					'installed' => defined( 'AWPCP_CATEGORY_ICONS_MODULE_DB_VERSION' ),
					'version' => 'AWPCP_CATEGORY_ICONS_MODULE_DB_VERSION',
					'required' => '3.2.3',
				),
				'comments' => array(
					'name' => __(  'Comments & Ratings', 'AWPCP'  ),
					'url' => 'http://www.awpcp.com/premium-modules/comments-ratings-module/?ref=panel',
					'installed' => defined( 'AWPCP_COMMENTS_MODULE' ),
					'version' => 'AWPCP_COMMENTS_MODULE_VERSION',
					'required' => '3.2.6',
				),
				'coupons' => array(
					'name' => __( 'Coupons/Discount', 'AWPCP' ),
					'url' => 'http://www.awpcp.com/premium-modules/coupon-module/?ref=panel',
					'installed' => defined( 'AWPCP_COUPONS_MODULE' ),
					'version' => 'AWPCP_COUPONS_MODULE_DB_VERSION',
					'required' => '3.0.3',
				),
				'extra-fields' => array(
					'name' => __( 'Extra Fields', 'AWPCP' ),
					'url' => 'http://www.awpcp.com/premium-modules/extra-fields-module?ref=panel',
					'installed' => defined( 'AWPCP_EXTRA_FIELDS_MODULE' ),
					'version' => 'AWPCP_EXTRA_FIELDS_MODULE_DB_VERSION',
					'required' => '3.2.14',
				),
				'featured-ads' => array(
					'name' => __( 'Featured Ads', 'AWPCP' ),
					'url' => 'http://www.awpcp.com/premium-modules/featured-ads-module?ref=panel',
					'installed' => defined( 'AWPCP_FEATURED_ADS_MODULE' ),
					'version' => 'AWPCP_FEATURED_ADS_MODULE_DB_VERSION',
					'required' => '3.0.2',
				),
				'fee-per-category' => array(
					'name' => __( 'Fee per Category', 'AWPCP' ),
					'url' =>'http://www.awpcp.com/premium-modules/fee-per-category-module?ref=panel',
					'installed' => function_exists( 'awpcp_price_cats' ),
					'version' => 'AWPCP_FPC_MODULE_DB_VERSION',
					'required' => '3.2.2',
				),
				'google-checkout' => array(
					'name' => __( 'Google Checkout', 'AWPCP' ),
					'url' => 'http://www.awpcp.com/premium-modules/google-checkout-module/?ref=panel',
					'installed' => defined( 'AWPCP_GOOGLE_CHECKOUT_MODULE' ),
					'version' => 'AWPCP_GOOGLE_CHECKOUT_MODULE_DB_VERSION',
					'required' => '3.0.1',
				),
				'paypal-pro' => array(
					'name' => __(  'PayPal Pro', 'AWPCP'  ),
					'url' => 'http://www.awpcp.com/premium-modules/paypalpro-payment-module/?ref=user-panel',
					'installed' => defined( 'AWPCP_PAYPAL_PRO_MODULE' ),
					'version' => 'AWPCP_PAYPAL_PRO_MODULE_DB_VERSION',
					'required' => '3.0.2',
				),
				'region-control' => array(
					'name' => __( 'Regions Control', 'AWPCP' ),
					'url' => 'http://www.awpcp.com/premium-modules/regions-control-module?ref=panel',
					'installed' => defined( 'AWPCP_REGION_CONTROL_MODULE' ),
					'version' => 'AWPCP_REGION_CONTROL_MODULE_DB_VERSION',
					'required' => '3.2.17',
				),
				'restricted-categories' => array(
					'name' => __( 'Restricted Categories', 'AWPCP' ),
					'url' => 'http://www.awpcp.com/premium-modules/',
					'installed' => defined( 'AWPCP_RESTRICTED_CATEGORIES_MODULE' ),
					'version' => 'AWPCP_RESTRICTED_CATEGORIES_MODULE_DB_VERSION',
					'required' => '1.0',
				),
				'rss' => array(
					'name' => __( 'RSS', 'AWPCP' ),
					'url' => 'http://www.awpcp.com/premium-modules/rss-module?ref=panel',
					'installed' => defined( 'AWPCP_RSS_MODULE' ),
					'version' => 'AWPCP_RSS_MODULE_DB_VERSION',
					'required' => '3.0.3',
				),
				'subscriptions' => array(
					'name' => __( 'Subscriptions', 'AWPCP' ),
					'url' => 'http://www.awpcp.com/premium-modules/subscriptions-module/?ref=panel',
					'installed' => defined( 'AWPCP_SUBSCRIPTIONS_MODULE' ),
					'version' => 'AWPCP_SUBSCRIPTIONS_MODULE_DB_VERSION',
					'required' => '3.2.8',
				),
				'xml-sitemap' => array(
					'name' => __(  'XML Sitemap', 'AWPCP'  ),
					'url' => 'http://www.awpcp.com/premium-modules/',
					'installed' => function_exists( 'awpcp_generate_ad_entries' ),
					'version' => 'AWPCP_XML_SITEMAP_MODULE_DB_VERSION',
					'required' => '3.0.1',
				),
			);
		}

		return $modules;
	}

	/**
	 * @since 3.0.2
	 */
	public function is_compatible_with( $module, $version ) {
		$modules = $this->get_premium_modules_information();

		if ( ! isset( $modules[ $module ] ) ) {
			return false;
		}

		if ( version_compare( $version, $modules[ $module ]['required'], '<' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * @since 3.0.2
	 */
	public function check_compatibility_with_premium_modules() {
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
			$request = awpcp_request();
			// if we are in a subdomain, let PHP choose the right domain
			if ( strcmp( $request->domain(), $request->domain( false ) ) == 0 ) {
				$domain = '';
			// otherwise strip the www part
			} else {
				$domain = $request->domain( false, '.' );
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

		$js = AWPCP_URL . '/resources/js';
		$css = AWPCP_URL . '/resources/css';

		/* vendors */

		if (isset($wp_scripts->registered['jquery-ui-core'])) {
			$ui_version = $wp_scripts->registered['jquery-ui-core']->ver;
		} else {
			$ui_version = '1.9.2';
		}

		wp_register_style('awpcp-jquery-ui', "//ajax.googleapis.com/ajax/libs/jqueryui/$ui_version/themes/smoothness/jquery-ui.css", array(), $ui_version);

		wp_register_script('awpcp-jquery-validate', "{$js}/jquery-validate/all.js", array('jquery'), '1.10.0', true);

		/* helpers */

		wp_register_script('awpcp', "{$js}/awpcp.min.js", array('jquery'), $awpcp_db_version, true);
		wp_register_script( 'awpcp-billing-form', "{$js}/awpcp-billing-form.js", array( 'awpcp' ), $awpcp_db_version, true );
		wp_register_script( 'awpcp-multiple-region-selector', "{$js}/awpcp-multiple-region-selector.js", array( 'awpcp', 'awpcp-jquery-validate' ), $awpcp_db_version, true );

		wp_register_script('awpcp-admin-wp-table-ajax', "{$js}/admin-wp-table-ajax.js", array('jquery-form'), $awpcp_db_version, true);

		// register again with old name too (awpcp-table-ajax-admin), for backwards compatibility
		wp_register_script('awpcp-table-ajax-admin', "{$js}/admin-wp-table-ajax.js", array('jquery-form'), $awpcp_db_version, true);

		wp_register_script('awpcp-toggle-checkboxes', "{$js}/checkuncheckboxes.js", array('jquery'), $awpcp_db_version, true);

		/* admin */

		wp_register_style('awpcp-admin-style', "{$css}/awpcp-admin.css", array(), $awpcp_db_version);

		wp_register_script('awpcp-admin-general', "{$js}/admin-general.js", array('awpcp'), $awpcp_db_version, true);
		wp_register_script('awpcp-admin-settings', "{$js}/admin-settings.js", array('awpcp'), $awpcp_db_version, true);
		wp_register_script('awpcp-admin-fees', "{$js}/admin-fees.js", array('awpcp-admin-wp-table-ajax'), $awpcp_db_version, true);
		wp_register_script('awpcp-admin-credit-plans', "{$js}/admin-credit-plans.js", array('awpcp-admin-wp-table-ajax'), $awpcp_db_version, true);
		wp_register_script( 'awpcp-admin-listings', "{$js}/admin-listings.js", array( 'awpcp', 'awpcp-admin-wp-table-ajax' ), $awpcp_db_version, true );
		wp_register_script('awpcp-admin-users', "{$js}/admin-users.js", array('awpcp-admin-wp-table-ajax'), $awpcp_db_version, true);
		wp_register_script( 'awpcp-admin-attachments', "{$js}/admin-attachments.js", array( 'awpcp' ), $awpcp_db_version, true );
		wp_register_script( 'awpcp-admin-import', "{$js}/admin-import.js", array( 'awpcp', 'jquery-ui-datepicker', 'jquery-ui-autocomplete' ), $awpcp_db_version, true );

		/* frontend */

		wp_register_style('awpcp-frontend-style', "{$css}/awpcpstyle.css", array(), $awpcp_db_version);

		wp_register_style('awpcp-frontend-style-ie-6', "{$css}/awpcpstyle-ie-6.css", array('awpcp-frontend-style'), $awpcp_db_version);
		$wp_styles->add_data( 'awpcp-frontend-style-ie-6', 'conditional', 'lte IE 6' );

		wp_register_style( 'awpcp-frontend-style-lte-ie-7', "{$css}/awpcpstyle-lte-ie-7.css", array( 'awpcp-frontend-style' ), $awpcp_db_version );
		$wp_styles->add_data( 'awpcp-frontend-style-lte-ie-7', 'conditional', 'lte IE 7' );


		wp_register_script('awpcp-page-place-ad', "{$js}/page-place-ad.js", array('awpcp', 'awpcp-multiple-region-selector', 'awpcp-jquery-validate', 'jquery-ui-datepicker', 'jquery-ui-autocomplete'), $awpcp_db_version, true);
		wp_register_script('awpcp-page-reply-to-ad', "{$js}/page-reply-to-ad.js", array('awpcp', 'awpcp-jquery-validate'), $awpcp_db_version, true);
		wp_register_script('awpcp-page-search-listings', "{$js}/page-search-listings.js", array('awpcp', 'awpcp-multiple-region-selector', 'awpcp-jquery-validate'), $awpcp_db_version, true);
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
			wp_enqueue_style('awpcp-frontend-style-lte-ie-7');
	        wp_enqueue_style('awpcp-custom-css');
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


	/**------------------------------------------------------------------------
	 * Payment Transaction Integration
	 */

	/**
	 * Set payment status to Not Required in requiredtransactions made by
	 * admin users.
	 *
	 * TODO: move this into one of the steps decorator, when steps decorators become widely used.
	 *
	 * @since  2.2.2
	 */
	public function process_transaction_update_payment_status($transaction) {
		switch ($transaction->get_status()) {
            case AWPCP_Payment_Transaction::STATUS_OPEN:
                if (awpcp_current_user_is_admin()/* || get_awpcp_option('freepay') == 0*/)
                    $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_NOT_REQUIRED;
                break;
		}
	}

	/**
	 * WP Affiliate Platform integration.
	 *
	 * Notifies WP Affiliate Platform plugin when a transaction
	 * that involves money exchange has been completed.
	 *
	 * @since 3.0.2
	 */
	public function process_transaction_notify_wp_affiliate_platform($transaction) {
		if ( ! ( $transaction->is_payment_completed() || $transaction->is_completed() ) ) {
			return;
		}

		if ( $transaction->payment_is_not_required() ) {
			return;
		}

		if ( ! $transaction->was_payment_successful() ) {
			return;
		}

		$allowed_context = array( 'add-credit', 'place-ad', 'renew-ad', 'buy-subscription' );
		$context = $transaction->get('context');

		if ( ! in_array( $context, $allowed_context ) ) {
			return;
		}

		$amount = $transaction->get_total_amount();

		if ( $amount <= 0 ) {
			return;
		}

		$unique_transaction_id = $transaction->id;
		$referrer = isset( $_COOKIE['ap_id'] ) ? $_COOKIE['ap_id'] : null;
		$email = '';

		if ( $transaction->get( 'ad_id' ) ) {
			$ad = AWPCP_Ad::find_by_id( $transaction->get( 'ad_id' ) );
			$email = $ad->ad_contact_email;
		} else if ( $transaction->user_id ) {
			$user = get_userdata( $transaction->user_id );
			$email = $user->user_email;
		}

		$data = array(
			'sale_amt' => $amount,
			'txn_id'=> $unique_transaction_id,
			'referrer' => $referrer,
			'buyer_email' => $email,
		);

		do_action( 'wp_affiliate_process_cart_commission', $data );
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
		$query.= "WHERE posts.ID IS NULL OR posts.post_status != 'publish'";

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

	/**
	 * Handler for AJAX request from the Multiple Region Selector to get new options
	 * for a given field.
	 *
	 * @since 3.0.2
	 */
	public function get_regions_options() {
		$type = awpcp_request_param( 'type', '', $_GET );
		$parent_type = awpcp_request_param( 'parent_type', '', $_GET );
		$parent = awpcp_request_param( 'parent', '', $_GET );
		$context = awpcp_request_param( 'context', '', $_GET );

		$options = apply_filters( 'awpcp-get-regions-options', false, $type, $parent_type, $parent, $context );

		if ( $options === false ) {
		    $options = array();

			if ( $context === 'search' && get_awpcp_option( 'buildsearchdropdownlists' ) ) {
				$regions = awpcp_basic_regions_api()->find_by_parent_name( $parent, $parent_type, $type );
				$regions = array_filter( $regions, 'strlen' );

		        foreach ( $regions as $key => $option ) {
		            $options[] = array( 'id' => $option, 'name' => $option );
		        }
		    }
		}

        $response = array( 'status' => 'ok', 'options' => $options );

		header( "Content-Type: application/json" );
    	echo json_encode($response);
    	die();
	}

	public function clear_categories_list_cache() {
		$transient_keys = get_option( 'awpcp-categories-list-cache-keys', array() );
		foreach ( $transient_keys as $transient_key ) {
			delete_transient( $transient_key );
		}
		delete_option( 'awpcp-categories-list-cache-keys' );
	}
}

function awpcp() {
	global $awpcp;

	if (!is_object($awpcp)) {
		$awpcp = new AWPCP();
        $awpcp->bootstrap();
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

	$child_pages = $wpdb->get_col( $query );

	if ( is_array( $child_pages ) ) {
		return array_merge( $child_pages, $excluded );
	} else {
		return $excluded;
	}
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
		'index.php?awpcpx=1&awpcp-module=payments&awpcp-action=return&awpcp-txn=$matches[1]', 'top');
	add_rewrite_rule('awpcpx/payments/notify/([a-zA-Z0-9]+)',
		'index.php?awpcpx=1&awpcp-module=payments&awpcp-action=notify&awpcp-txn=$matches[1]', 'top');
	add_rewrite_rule('awpcpx/payments/cancel/([a-zA-Z0-9]+)',
		'index.php?awpcpx=1&awpcp-module=payments&awpcp-action=cancel&awpcp-txn=$matches[1]', 'top');

	// Ad Email Verification rewrite rules
	add_rewrite_rule( 'awpcpx/listings/verify/([0-9]+)/([a-zA-Z0-9]+)',
		'index.php?awpcpx=1&awpcp-module=listings&awpcp-action=verify&awpcp-ad=$matches[1]&awpcp-hash=$matches[2]', 'top' );

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
		add_rewrite_rule('('.$patterns['main-page-name'].')/('.$view_categories.')($|[/?])',
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
		'awpcp-module',
		'awpcp-action',
		'module',
		'action',

		// Payments API
		'awpcp-txn',

		// Listings API
		'awpcp-ad',
		'awpcp-hash',

		// misc
		"cid",
		"i",
		"id",
		"layout",
		"regionid",
	);

	return array_merge($query_vars, $vars);
}

/**
 * @since 3.2.1
 */
function awpcp_rel_canonical_url() {
	global $wp_the_query;

	if ( ! is_singular() )
		return false;

	if ( ! $page_id = $wp_the_query->get_queried_object_id() ) {
		return false;
	}

	if ( $page_id != awpcp_get_page_id_by_ref( 'show-ads-page-name' ) ) {
		return false;
	}

	$ad_id = intval( awpcp_request_param( 'id', '' ) );
	$ad_id = empty( $ad_id ) ? intval( get_query_var( 'id' ) ) : $ad_id;

	if ( empty( $ad_id ) ) {
		$url = get_permalink( $page_id );
	} else {
		$url = url_showad( $ad_id );
	}

	return $url;
}

/**
 * Set canonical URL to the Ad URL when in viewing on of AWPCP Ads.
 *
 * @since unknown
 * @since 3.2.1	logic moved to awpcp_rel_canonical_url()
 */
function awpcp_rel_canonical() {
	$url = awpcp_rel_canonical_url();

	if ( $url ) {
		echo "<link rel='canonical' href='$url' />\n";
	} else {
		rel_canonical();
	}
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

    $awpcp_rewrite = false;
	$ids = awpcp_get_page_ids_by_ref(awpcp_pages_with_rewrite_rules());

	// do not redirect requests to AWPCP pages with rewrite rules
	if (is_page() && in_array(awpcp_request_param('page_id', 0), $ids)) {
        $awpcp_rewrite = true;

	// do not redirect requests to the front page, if any of the AWPCP pages
	// with rewrite rules is the front page
	} else if (is_page() && !is_feed() && isset($wp_query->queried_object) &&
			  'page' == get_option('show_on_front') && in_array($wp_query->queried_object->ID, $ids) &&
			   $wp_query->queried_object->ID == get_option('page_on_front'))
	{
        $awpcp_rewrite = true;
	}

    if ( $awpcp_rewrite ) {
        // Fix for #943.
        $requested_host = parse_url( $requested_url, PHP_URL_HOST );
        $redirect_host = parse_url( $redirect_url, PHP_URL_HOST );

        if ( $requested_host != $redirect_host ) {
            if ( strtolower( $redirect_host ) == ( 'www.' . $requested_host ) ) {
                return str_replace( $requested_host, 'www.' . $requested_host, $requested_url );
            } elseif ( strtolower( $requested_host ) == ( 'www.' . $redirect_host ) ) {
                return str_replace( 'www.', '', $requested_url );
            }
        }

        return $requested_url;
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
