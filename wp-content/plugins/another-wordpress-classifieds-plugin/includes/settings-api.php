<?php

class AWPCP_Settings_API {

	private static $instance = null;

	public $setting_name = 'awpcp-options';

	public $options = array();
	private $runtime_options = array();

	public $defaults = array();
	public $groups = array();

	private function __construct() {
		$this->load();
	}

	public static function instance() {
		if (is_null(self::$instance)) {
			self::$instance = new AWPCP_Settings_API();
		}
		return self::$instance;
	}

	public function load() {
		$options = get_option($this->setting_name);
		$this->options = is_array($options) ? $options : array();
	}

	public function register_settings() {

		register_setting($this->setting_name, $this->setting_name, array($this, 'validate'));

		// Group: Private

		$group = $this->add_group( __( 'Private Settings', 'another-wordpress-classifieds-plugin' ), 'private-settings', 0 );

		// Group: Classified Pages

		$group = $this->add_group(__('Classifieds Pages', 'another-wordpress-classifieds-plugin'), 'pages-settings', 20);

		// Section: Classifieds Pages - Default

		$key = $this->add_section($group, __('Classifieds Pages', 'another-wordpress-classifieds-plugin'), 'default', 10, array($this, 'section'));

		$this->add_setting( $key, 'main-page-name', __( 'AWPCP Main page', 'another-wordpress-classifieds-plugin' ), 'textfield', 'another-wordpress-classifieds-plugin', __( 'Name for Classifieds page.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'show-ads-page-name', __( 'Show Ad page', 'another-wordpress-classifieds-plugin' ), 'textfield', 'Show Ad', __( 'Name for Show Ads page.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'place-ad-page-name', __( 'Place Ad page', 'another-wordpress-classifieds-plugin' ), 'textfield', 'Place Ad', __( 'Name for Place Ads page.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'edit-ad-page-name', __( 'Edit Ad page', 'another-wordpress-classifieds-plugin' ), 'textfield', 'Edit Ad', __( 'Name for edit ad page.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'renew-ad-page-name', __( 'Renew Ad page', 'another-wordpress-classifieds-plugin' ), 'textfield', 'Renew Ad', __( 'Name for Renew Ad page.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'reply-to-ad-page-name', __( 'Reply to Ad page', 'another-wordpress-classifieds-plugin' ), 'textfield', 'Reply To Ad', __( 'Name for Reply to Ad page.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'browse-ads-page-name', __( 'Browse Ads page', 'another-wordpress-classifieds-plugin' ), 'textfield', 'Browse Ads', __( 'Name for Browse Ads page.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'search-ads-page-name', __( 'Search Ads page', 'another-wordpress-classifieds-plugin' ), 'textfield', 'Search Ads', __( 'Name for Search Ads page.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'view-categories-page-name', __( 'View Categories page', 'another-wordpress-classifieds-plugin' ), 'textfield', 'View Categories', __( 'Name for categories view page. (Dynamic Page)', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'payment-thankyou-page-name', __( 'Payment Thank You page', 'another-wordpress-classifieds-plugin' ), 'textfield', 'Payment Thank You', __( 'Name for Payment Thank You page.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'payment-cancel-page-name', __( 'Payment Cancel page', 'another-wordpress-classifieds-plugin' ), 'textfield', 'Cancel Payment', __( 'Name for Payment Cancel page.', 'another-wordpress-classifieds-plugin' ) );

		// Group: Ad/Listings

		$group = $this->add_group(__('Ad/Listings', 'another-wordpress-classifieds-plugin'), 'listings-settings', 30);

		// Section: Ad/Listings - Regions

		$key = $this->add_section( $group, __( 'Regions Settings', 'another-wordpress-classifieds-plugin' ), 'regions-settings', 20, array( $this, 'section' ) );

		$this->add_setting( $key, 'allow-regions-modification', __( 'Allow Regions modification', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, __( 'If enabled, users will be allowed to change the region information associated with their Ads.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'allow-user-to-search-in-multiple-regions', __( 'Allow users to search Ads in multiple regions', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'If enabled, users will be allowed to search Ads in multiple regions.', 'another-wordpress-classifieds-plugin' ) );

		// Section: Ad/Listings - Layout and Presentation

		$key = $this->add_section($group, __('Layout and Presentation', 'another-wordpress-classifieds-plugin'), 'layout', 30, array($this, 'section'));

		$this->add_setting( $key, 'show-ad-preview-before-payment', __( 'Show Ad preview before payment.', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'If enabled, a preview of the Ad being posted will be shown after the images have been uploaded and before the user is asked to pay. The user is allowed to go back and edit the Ad details and uploaded images or proceed with the posting process.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'allowhtmlinadtext', __( 'Allow HTML in Ad text', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'Allow HTML in ad text (Not recommended).', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'htmlstatustext', __( 'Display this text above ad detail text input box on ad post page', 'another-wordpress-classifieds-plugin' ), 'textarea', __( 'No HTML Allowed', 'another-wordpress-classifieds-plugin' ), '');
		$this->add_setting( $key, 'characters-allowed-in-title', __( 'Maximum Ad title length', 'another-wordpress-classifieds-plugin' ), 'textfield', 100, __( 'Number of characters allowed in Ad title. Please note this is the default value and can be overwritten in Fees and Subscription Plans.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'maxcharactersallowed', __( 'Maximum Ad details length', 'another-wordpress-classifieds-plugin' ), 'textfield', 750, __( 'Number of characters allowed in Ad details. Please note this is the default value and can be overwritten in Fees and Subscription Plans.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'words-in-listing-excerpt', __( 'Number of words in Ad excerpt', 'another-wordpress-classifieds-plugin' ), 'textfield', 20, __( 'Number of words shown by the Ad excerpt placeholder.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'hidelistingcontactname', __( 'Hide contact name to anonymous users?', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'Hide listing contact name to anonymous (non logged in) users.', 'another-wordpress-classifieds-plugin' ) );

		$this->add_setting(
            $key,
            'displayadlayoutcode',
            __( 'Ad Listings page layout', 'another-wordpress-classifieds-plugin' ),
            'textarea', '
<div class="awpcp-listing-excerpt $awpcpdisplayaditems $isfeaturedclass" data-breakpoints-class-prefix="awpcp-listing-excerpt" data-breakpoints=\'{"tiny": [0,328], "small": [328,600], "medium": [600,999999]}\'>
    <div class="awpcp-listing-excerpt-thumbnail">
        $awpcp_image_name_srccode
    </div>
    <div class="awpcp-listing-excerpt-inner" style="w">
        <h4 class="awpcp-listing-title">$title_link</h4>
        <div class="awpcp-listing-excerpt-content">$excerpt</div>
    </div>
    <div class="awpcp-listing-excerpt-extra">
        $awpcpadpostdate
        $awpcp_city_display
        $awpcp_state_display
        $awpcp_display_adviews
        $awpcp_display_price
        $awpcpextrafields
    </div>
    <span class="fixfloat"></span>
</div>
<div class="fixfloat"></div>',
            __( 'Modify as needed to control layout of ad listings page. Maintain code formatted as \$somecodetitle. Changing the code keys will prevent the elements they represent from displaying.', 'another-wordpress-classifieds-plugin' )
        );

		$this->add_setting( $key, 'awpcpshowtheadlayout', __( 'Single Ad page layout', 'another-wordpress-classifieds-plugin' ),
							'textarea', '
							<div id="showawpcpadpage">
								<div class="awpcp-title">$ad_title</div><br/>
								<div class="showawpcpadpage">
									$featureimg
									<div class="awpcp-subtitle">' . __( "Contact Information",'another-wordpress-classifieds-plugin' ). '</div>
									<a href="$codecontact">' . __("Contact",'another-wordpress-classifieds-plugin') . ' $adcontact_name</a>
									$adcontactphone
									$location
									$awpcpvisitwebsite
								</div>
								$aditemprice
								$awpcpextrafields
								<div class="fixfloat"></div>
								$showadsense1
								<div class="showawpcpadpage">
									<div class="awpcp-subtitle">' . __( "More Information", 'another-wordpress-classifieds-plugin' ) . '</div>
									$addetails
								</div>
								$showadsense2
								<div class="fixfloat"></div>
								<div id="displayimagethumbswrapper">
									<div id="displayimagethumbs">
										<ul>
											$awpcpshowadotherimages
										</ul>
									</div>
								</div>
								<span class="fixfloat">$tweetbtn $sharebtn $flagad</span>
								$awpcpadviews
								$showadsense3
								$edit_listing_link
							</div>', __( 'Modify as needed to control layout of single ad view page. Maintain code formatted as \$somecodetitle. Changing the code keys will prevent the elements they represent from displaying.', 'another-wordpress-classifieds-plugin' ) );

        $this->add_setting(
            $key,
            'allow-wordpress-shortcodes-in-single-template',
            __( 'Allow WordPress Shortcodes in Single Ad page layout', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            0,
            __( 'Shortcodes executed this way will be executed as if they were entered in the content of the WordPress page showing the listing (normally the Show Ad page, but in general any page that has the AWPCPSHOWAD shortcode).', 'another-wordpress-classifieds-plugin' )
        );

		$radio_options = array(1 => __( 'Date (newest first)', 'another-wordpress-classifieds-plugin' ),
							   9 => __( 'Date (oldest first)', 'another-wordpress-classifieds-plugin' ),
							   2 => __( 'Title (ascending)', 'another-wordpress-classifieds-plugin' ),
							   10 => __( 'Title (descending)', 'another-wordpress-classifieds-plugin' ),
							   3 => __( 'Paid status and date (paid first, then most recent)', 'another-wordpress-classifieds-plugin' ),
							   4 => __( 'Paid status and title (paid first, then by title)', 'another-wordpress-classifieds-plugin' ),
							   5 => __( 'Views (most viewed first, then by title)', 'another-wordpress-classifieds-plugin' ),
							   6 => __( 'Views (most viewed first, then by date)', 'another-wordpress-classifieds-plugin' ),
							   11 => __( 'Views (least viewed first, then by title)', 'another-wordpress-classifieds-plugin' ),
							   12 => __( 'Views (least viewed first, then by date)', 'another-wordpress-classifieds-plugin' ),
							   7 => __( 'Price (high to low, then by date)', 'another-wordpress-classifieds-plugin' ),
							   8 => __( 'Price (low to high, then by date)', 'another-wordpress-classifieds-plugin' ),
							);

		$this->add_setting( $key, 'groupbrowseadsby', __( 'Order Ad Listings by', 'another-wordpress-classifieds-plugin' ), 'select', 1, '', array('options' => $radio_options));
		$this->add_setting( $key, 'search-results-order', __( 'Order Ad Listings in Search results by', 'another-wordpress-classifieds-plugin' ), 'select', 1, '', array('options' => $radio_options));
        $this->add_setting(
            $key,
            'search-form-in-results',
            __(  'Search form display', 'another-wordpress-classifieds-plugin' ),
            'radio',
            'none',
            '',
            array( 'options' => array(
                'above' => __( 'Above results', 'another-wordpress-classifieds-plugin' ),
                'below' => __( 'Below results', 'another-wordpress-classifieds-plugin' ),
                'none'  => __( 'Don\'t show with results', 'another-wordpress-classifieds-plugin' ),
            ) ) );
		$this->add_setting( $key, 'adresultsperpage', __( 'Default number of Ads per page', 'another-wordpress-classifieds-plugin' ), 'textfield', 10, '');

		$pagination_options = array( 5, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 500 );
		$this->add_setting( $key, 'pagination-options', __( 'Pagination Options', 'another-wordpress-classifieds-plugin' ), 'choice', $pagination_options, '', array( 'choices' => array_combine( $pagination_options, $pagination_options ) ) );

		$this->add_setting( $key, 'buildsearchdropdownlists', __( 'Limits search to available locations.', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'The search form can attempt to build drop down country, state, city and county lists if data is available in the system. Note that with the regions module installed the value for this option is overridden.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'showadcount', __( 'Show Ad count in categories', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, __( 'Show how many ads a category contains.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'hide-empty-categories', __( 'Hide empty categories?', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( "If checked, categories with 0 listings in it won't be shown.", 'another-wordpress-classifieds-plugin' ) );

        $this->add_setting(
            $key,
            'displayadviews',
            __( 'Show Ad views', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            1,
            __( 'Show the number of times the ad has been viewed (simple count made by AWPCP &endash; warning, may not be accurate!)', 'another-wordpress-classifieds-plugin' )
        );

		$this->add_setting( $key, 'hyperlinkurlsinadtext', __( 'Make URLs in ad text clickable', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, '' );
		$this->add_setting( $key, 'visitwebsitelinknofollow', __( 'Add no follow to links in Ads', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, '' );

        // Section: Ad/Listings - Classifieds Bar

        $key = $this->add_section( $group, __( 'Classifieds Bar', 'another-wordpress-classifieds-plugin' ), 'classifieds-bar', 60, array( $this, 'section' ) );

        $this->add_setting(
            $key,
            'show-classifieds-bar',
            __( 'Show Classifieds Bar', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            1,
            __( 'The Classifieds Bar is a section shown at the top of the plugin pages, displaying a Search Bar and multiple menu items. Each element of the bar can be enabled or disabled using the settings below.', 'another-wordpress-classifieds-plugin' )
        );

        $this->add_setting(
            $key,
            'show-classifieds-search-bar',
            __( 'Show Search Bar', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            1,
            ''
        );

		$this->add_setting( $key, 'show-menu-item-place-ad', __( 'Show Place Ad menu item', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, '' );
		$this->add_setting( $key, 'show-menu-item-edit-ad', __( 'Show Edit Ad menu item', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, '' );
		$this->add_setting( $key, 'show-menu-item-browse-ads', __( 'Show Browse Ads menu item', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, '' );
		$this->add_setting( $key, 'show-menu-item-search-ads', __( 'Show Search Ads menu item', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, '' );

		$this->add_behavior( $key, 'show-classifieds-search-bar', 'enabledIf', 'show-classifieds-bar' );
		$this->add_behavior( $key, 'show-menu-item-place-ad', 'enabledIf', 'show-classifieds-bar' );
		$this->add_behavior( $key, 'show-menu-item-edit-ad', 'enabledIf', 'show-classifieds-bar' );
		$this->add_behavior( $key, 'show-menu-item-browse-ads', 'enabledIf', 'show-classifieds-bar' );
		$this->add_behavior( $key, 'show-menu-item-search-ads', 'enabledIf', 'show-classifieds-bar' );

		// Group: Payment Settings

		$group = $this->add_group( __( 'Payment', 'another-wordpress-classifieds-plugin') , 'payment-settings', 40 );

		// Section: Payment Settings - PayPal

		$key = $this->add_section( $group, __( 'PayPal Settings', 'another-wordpress-classifieds-plugin' ), 'paypal', 20, array( $this, 'section' ) );

		$this->add_setting($key, 'activatepaypal', __( 'Activate PayPal?', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, __( 'Activate PayPal?', 'another-wordpress-classifieds-plugin' ) );

		$this->add_setting(
			$key,
			'paypalemail',
			__( 'PayPal receiver email', 'another-wordpress-classifieds-plugin' ),
			'textfield',
			'',
			__( 'Email address for PayPal payments (if running in pay mode and if PayPal is activated).', 'another-wordpress-classifieds-plugin' )
		);

        $this->add_setting(
            $key,
            'paypal_merchant_id',
            __( 'PayPal Merchant ID', 'another-wordpress-classifieds-plugin' ),
            'textfield',
            '',
            __( 'Merchant ID associated with the PayPal account that will receive the payments. Go to <a href="https://www.paypal.com/myaccount/settings/" target="_blank">https://www.paypal.com/myaccount/settings/</a> to obtain your Merchant ID.' )
        );

		$this->add_validation_rule( $key, 'paypalemail', 'required', array( 'depends' => 'activatepaypal' ) );
		$this->add_validation_rule( $key, 'paypalemail', 'email', true, __( 'Please enter a valid email address.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_behavior( $key, 'paypalemail', 'enabledIf', 'activatepaypal' );
        $this->add_behavior( $key, 'paypal_merchant_id', 'enabledIf', 'activatepaypal' );

		$this->add_setting(
			$key,
			'paypalcurrencycode',
			__( 'PayPal currency code', 'another-wordpress-classifieds-plugin' ),
			'textfield',
			'USD',
			__( 'The currency in which you would like to receive your PayPal payments', 'another-wordpress-classifieds-plugin' )
		);

		$supported_currencies = awpcp_paypal_supported_currencies();
		$message = __( 'The PayPal Currency Code must be one of <currency-codes>.', 'another-wordpress-classifieds-plugin' );
		$message = str_replace( '<currency-codes>', implode( ', ', $supported_currencies ), $message );

		$this->add_validation_rule( $key, 'paypalcurrencycode', 'required', array( 'depends' => 'activatepaypal' ) );
		$this->add_validation_rule( $key, 'paypalcurrencycode', 'oneof', array( 'param' => $supported_currencies ), $message );
		$this->add_behavior( $key, 'paypalcurrencycode', 'enabledIf', 'activatepaypal' );

		// Section: Payment Settings - 2Checkout

		$key = $this->add_section($group, __('2Checkout Settings', 'another-wordpress-classifieds-plugin'), '2checkout', 30, array($this, 'section'));

		$this->add_setting( $key, 'activate2checkout', __( 'Activate 2Checkout', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, __( 'Activate 2Checkout?', 'another-wordpress-classifieds-plugin' ) );

		$this->add_setting( $key, '2checkout', __( '2Checkout account', 'another-wordpress-classifieds-plugin' ), 'textfield', '', __( 'Account for 2Checkout payments.', 'another-wordpress-classifieds-plugin' ) );

		$this->add_validation_rule( $key, '2checkout', 'required', array( 'depends' => 'activate2checkout' ) );
		$this->add_behavior( $key, '2checkout', 'enabledIf', 'activate2checkout' );

		$this->add_setting( $key, '2checkoutcurrencycode', __( '2Checkout Currency Code', 'another-wordpress-classifieds-plugin' ), 'textfield', 'USD', __( 'The currency in which you would like to receive your 2Checkout payments', 'another-wordpress-classifieds-plugin' ) );

		$this->add_validation_rule( $key, '2checkoutcurrencycode', 'required', array( 'depends' => 'activate2checkout' ) );
		$this->add_behavior( $key, '2checkoutcurrencycode', 'enabledIf', 'activate2checkout' );

		// Group: AdSense

		$group = $this->add_group( __( 'AdSense', 'another-wordpress-classifieds-plugin' ), 'adsense-settings', 60 );

		// Section: AdSense Settings

		$key = $this->add_section( $group, __( 'AdSense Settings', 'another-wordpress-classifieds-plugin' ), 'default', 10, array( $this, 'section' ) );

		$options = array(
			1 => __( 'Above Ad text.', 'another-wordpress-classifieds-plugin' ),
			2 => __( 'Under Ad text.', 'another-wordpress-classifieds-plugin' ),
			3 => __( 'Below Ad images.', 'another-wordpress-classifieds-plugin' ),
		);

		$this->add_setting( $key, 'useadsense', __( 'Activate AdSense', 'another-wordpress-classifieds-plugin'), 'checkbox', 1, '');
		$this->add_setting( $key, 'adsense', __( 'AdSense code', 'another-wordpress-classifieds-plugin' ), 'textarea', __( 'AdSense code', 'another-wordpress-classifieds-plugin' ), __( 'Your AdSense code (Best if 468x60 text or banner.)', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'adsenseposition', __( 'Show AdSense at position', 'another-wordpress-classifieds-plugin' ), 'radio', 2, '', array( 'options' => $options ) );

		// Group: Registration

		$group = $this->add_group( __( 'Registration', 'another-wordpress-classifieds-plugin' ), 'registration-settings', 80);

		// Group: Email

		$group = $this->add_group('Email', 'email-settings', 90);

		// Section: General Email Settings

		$key = $this->add_section($group, __('General Email Settings', 'another-wordpress-classifieds-plugin'), 'default', 20, array($this, 'section'));

		$this->add_setting( $key, 'admin-recipient-email', __( 'TO email address for outgoing emails', 'another-wordpress-classifieds-plugin' ), 'textfield', '', __( 'Emails are sent to your WordPress admin email. If you prefer to receive emails in a different address, please enter it here.', 'another-wordpress-classifieds-plugin' ) );

		$this->add_setting(
			$key,
			'awpcpadminemail',
			__( 'FROM email address for outgoing emails', 'another-wordpress-classifieds-plugin' ),
			'textfield',
			'',
			__( 'Emails go out using your WordPress admin email. If you prefer to use a different email enter it here. Some servers will not process outgoing emails that have an email address from gmail, yahoo, hotmail and other free email services in the FROM field. Some servers will also not process emails that have an email address that is different from the email address associated with your hosting account in the FROM field. If you are with such a webhost you need to make sure your WordPress admin email address is tied to your hosting account.', 'another-wordpress-classifieds-plugin' )
		);

		$setting_label = __( 'Use wordpress@<website-domain> as the FROM email address for outgoing emails.', 'another-wordpress-classifieds-plugin' );
		$setting_label = str_replace( '<website-domain>', awpcp_request()->domain( false ), $setting_label );

		$this->add_setting(
			$key,
			'sent-emails-using-wordpress-email-address',
			$setting_label,
			'checkbox',
			0,
			__( "That's the address WordPress uses to send its emails. If you are receiving the registration emails and other WordPress notifications succesfully, then you may want to enable this setting to use the same email address for all the outgoing messages. If enabled, the FROM email address for outgoing emails setting is ignored.", 'another-wordpress-classifieds-plugin' )
		);

		$this->add_setting( $key, 'usesenderemailinsteadofadmin', __( 'Use sender email for reply messages', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'Check this to use the name and email of the sender in the FROM field when someone replies to an ad. When unchecked the messages go out with the website name and WP admin email address in the from field. Some servers will not process outgoing emails that have an email address from gmail, yahoo, hotmail and other free email services in the FROM field. Some servers will also not process emails that have an email address that is different from the email address associated with your hosting account in the FROM field. If you are with such a webhost you need to leave this option unchecked and make sure your WordPress admin email address is tied to your hosting account.', 'another-wordpress-classifieds-plugin' ) );

        /* translators: full-email-address=John Doe <john.doe@example.com>, short-email-address=john.doe@example.com */
        $description = __( 'If checked, whenever the name of the recipient is available, emails will be sent to <full-email-address> instead of just <short-email-address>. Some email servers, however, have problems handling email address that include the name of the recipient. If emails sent by the plugin are not being delivered properly, try unchecking this settting.' );
        $description = str_replace( '<full-email-address>', '<strong>' . esc_html( 'John Doe <john.doe@example.com>' ) . '</strong>', $description );
        $description = str_replace( '<short-email-address>', '<strong>' . esc_html( 'john.doe@example.com' ) . '</strong>', $description );

        $this->add_setting(
            $key,
            'include-recipient-name-in-email-address',
            __( 'Include the name of the recipient in the email address', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            1,
            $description
        );

		$this->add_setting( $key, 'include-ad-access-key', __( 'Include Ad access key in email messages', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, __( "Include Ad access key in email notifications. You may want to uncheck this option if you are using the Ad Management panel, but is not necessary.", 'another-wordpress-classifieds-plugin' ) );

		// Section: Ad Posted Message

		$key = $this->add_section($group, __('Ad Posted Message', 'another-wordpress-classifieds-plugin'), 'ad-posted-message', 10, array($this, 'section'));

		$this->add_setting( $key, 'listingaddedsubject', __( 'Subject for Ad posted notification email', 'another-wordpress-classifieds-plugin' ), 'textfield', __( 'Your Classified Ad listing has been submitted', 'another-wordpress-classifieds-plugin' ), __( 'Subject line for email sent out when someone posts an Ad', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'listingaddedbody', __( 'Body for Ad posted notification email', 'another-wordpress-classifieds-plugin' ), 'textarea', __( 'Thank you for submitting your Classified Ad. The details of your ad are shown below.', 'another-wordpress-classifieds-plugin' ), __( 'Message body text for email sent out when someone posts an Ad', 'another-wordpress-classifieds-plugin' ) );

		// Section: Reply to Ad Message

		$key = $this->add_section($group, __('Reply to Ad Message', 'another-wordpress-classifieds-plugin'), 'reply-to-ad-message', 10, array($this, 'section'));

		$this->add_setting( $key, 'contactformsubjectline', __( 'Subject for Reply to Ad email', 'another-wordpress-classifieds-plugin' ), 'textfield', __( 'Response to your AWPCP Demo Ad', 'another-wordpress-classifieds-plugin' ), __( 'Subject line for email sent out when someone replies to Ad', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'contactformbodymessage', __( 'Body for Reply to Ad email', 'another-wordpress-classifieds-plugin' ), 'textarea', __( 'Someone has responded to your AWPCP Demo Ad', 'another-wordpress-classifieds-plugin' ), __( 'Message body text for email sent out when someone replies to Ad', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'notify-admin-about-contact-message', __( 'Notify admin about contact message', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, __( 'An email will be sent to the administrator every time a visitor sends a message to one of the Ad posters through the Reply to Ad page.', 'another-wordpress-classifieds-plugin' ) );

		// Section: Request Ad Message

		$key = $this->add_section($group, __('Resend Access Key Message', 'another-wordpress-classifieds-plugin'), 'request-ad-message', 10, array($this, 'section'));

		$this->add_setting( $key, 'resendakeyformsubjectline', __( 'Subject for Request Ad Access Key email', 'another-wordpress-classifieds-plugin' ), 'textfield', __( "The Classified Ad's ad access key you requested", 'another-wordpress-classifieds-plugin' ), __( 'Subject line for email sent out when someone requests their ad access key resent', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'resendakeyformbodymessage', __( 'Body for Request Ad Access Key email', 'another-wordpress-classifieds-plugin' ), 'textarea', __( "You asked to have your Classified Ad's access key resent. Below are all the Ad access keys in the system that are tied to the email address you provided", 'another-wordpress-classifieds-plugin' ), __('Message body text for email sent out when someone requests their ad access key resent', 'another-wordpress-classifieds-plugin' ) );

		// Section: Verify Email Message

		$key = $this->add_section($group, __('Verify Email Message', 'another-wordpress-classifieds-plugin'), 'verify-email-message', 10, array($this, 'section'));

		$this->add_setting( $key, 'verifyemailsubjectline', __( 'Subject for Verification email', 'another-wordpress-classifieds-plugin' ), 'textfield', __( 'Verify the email address used for Ad $title', 'another-wordpress-classifieds-plugin' ), __( 'Subject line for email sent out to verify the email address.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'verifyemailbodymessage', __( 'Body for Verification email', 'another-wordpress-classifieds-plugin' ), 'textarea', _x( "Hello \$author_name \n\nYou recently posted the Ad \$title to \$website_name. \n\nIn order to complete the posting process you have to verify your email address. Please click the link below to complete the verification process. You will be redirected to the website where you can see your Ad. \n\n\$verification_link \n\nAfter you verify your email address, the administrator will be notified about the new Ad. If moderation is enabled, your Ad will remain in a disabled status until the administrator approves it.\n\n\$website_name\n\n\$website_url", 'another-wordpress-classifieds-plugin' ), __('You can use the following placeholders to personalize the body of the email: $title, $author_name,$verification_email, $website_name, $website_url.', 'another-wordpress-classifieds-plugin' ) );

		// Section: Incomplete Payment Message

		$key = $this->add_section($group, __('Incomplete Payment Message', 'another-wordpress-classifieds-plugin'), 'incomplete-payment-message', 10, array($this, 'section'));

		$this->add_setting( $key, 'paymentabortedsubjectline', __( 'Subject for Incomplete Payment email', 'another-wordpress-classifieds-plugin' ), 'textfield', __( 'There was a problem processing your payment', 'another-wordpress-classifieds-plugin' ), __( 'Subject line for email sent out when the payment processing does not complete', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'paymentabortedbodymessage', __( 'Body for Incomplete Payment email', 'another-wordpress-classifieds-plugin' ), 'textarea', __( 'There was a problem encountered during your attempt to submit payment. If funds were removed from the account you tried to use to make a payment please contact the website admin or the payment website customer service for assistance.', 'another-wordpress-classifieds-plugin' ), __( 'Message body text for email sent out when the payment processing does not complete', 'another-wordpress-classifieds-plugin' ) );

		// Section: Renew Ad Message

		$key = $this->add_section($group, __('Renew Ad Message', 'another-wordpress-classifieds-plugin'), 'renew-ad-message', 10, array($this, 'section'));

		$this->add_setting( $key, 'renew-ad-email-subject', __( 'Subject for Renew Ad email', 'another-wordpress-classifieds-plugin' ), 'textfield', __( 'Your classifieds listing Ad will expire in %d days.', 'another-wordpress-classifieds-plugin' ), __( 'Subject line for email sent out when an Ad is about to expire.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'renew-ad-email-body', __( 'Body for Renew Ad email', 'another-wordpress-classifieds-plugin' ), 'textarea', __( 'This is an automated notification that your Classified Ad will expire in %d days.', 'another-wordpress-classifieds-plugin' ), __( 'Message body text for email sent out when an Ad is about to expire. Use %d as placeholder for the number of days before the Ad expires.', 'another-wordpress-classifieds-plugin' ) );

		// Section: Ad Renewed Message

		$key = $this->add_section($group, __('Ad Renewed Message', 'another-wordpress-classifieds-plugin'), 'ad-renewed-message', 10, array($this, 'section'));

		$this->add_setting( $key, 'ad-renewed-email-subject', __( 'Subject for Ad Renewed email', 'another-wordpress-classifieds-plugin' ), 'textfield', __( 'Your classifieds listing "%s" has been successfully renewed.', 'another-wordpress-classifieds-plugin' ), __( 'Subject line for email sent out when an Ad is successfully renewed.', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'ad-renewed-email-body', __( 'Body for Renew Ad email', 'another-wordpress-classifieds-plugin' ), 'textarea', __( 'Your classifieds listing Ad has been successfully renewed. More information below:', 'another-wordpress-classifieds-plugin' ), __( 'Message body text for email sent out when an Ad is successfully renewed. ', 'another-wordpress-classifieds-plugin' ) );

		// Section: Ad Expired Message

		$key = $this->add_section($group, __('Ad Expired Message', 'another-wordpress-classifieds-plugin'), 'ad-expired-message', 10, array($this, 'section'));

		$this->add_setting( $key, 'adexpiredsubjectline', __( 'Subject for Ad Expired email', 'another-wordpress-classifieds-plugin' ), 'textfield', __( 'Your classifieds listing at %s has expired', 'another-wordpress-classifieds-plugin' ), __( 'Subject line for email sent out when an ad has auto-expired', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'adexpiredbodymessage', __( 'Body for Ad Expired email', 'another-wordpress-classifieds-plugin' ), 'textarea', __( 'This is an automated notification that your Classified Ad has expired.', 'another-wordpress-classifieds-plugin' ), __( 'Message body text for email sent out when an ad has auto-expired', 'another-wordpress-classifieds-plugin' ) );

		// Section: Advanced Email Configuration

		$key = $this->add_section( $group, __( 'Advanced Email Configuration', 'another-wordpress-classifieds-plugin' ), 'advanced', 30, array( $this, 'section' ) );

		$this->add_setting( $key, 'usesmtp', __( 'Enable external SMTP server', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'Enabled external SMTP server (if emails not processing normally).', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'smtphost', __( 'SMTP host', 'another-wordpress-classifieds-plugin' ), 'textfield', 'mail.example.com', __( 'SMTP host (if emails not processing normally).', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'smtpport', __( 'SMTP port', 'another-wordpress-classifieds-plugin' ), 'textfield', '25', __( 'SMTP port (if emails not processing normally).', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'smtpusername', __( 'SMTP username', 'another-wordpress-classifieds-plugin' ), 'textfield', 'smtp_username', __( 'SMTP username (if emails not processing normally).', 'another-wordpress-classifieds-plugin' ) );
		$this->add_setting( $key, 'smtppassword', __( 'SMTP password', 'another-wordpress-classifieds-plugin' ), 'password', '', __( 'SMTP password (if emails not processing normally).', 'another-wordpress-classifieds-plugin' ) );

		// Group: Facebook

		$group = $this->add_group('Facebook', 'facebook-settings', 100);

		$key = $this->add_section( $group, __( 'General Settings', 'another-wordpress-classifieds-plugin' ), 'general', 10, array( $this, 'section' ) );

        $this->add_setting(
            $key,
            'facebook-integration-method',
            __( 'Facebook Integration Method', 'another-wordpress-classifieds-plugin' ),
            'radio',
            'webhooks',
            __( 'Please note that sending ads to Facebook Groups is currently not available using Webhooks, after Facebook significantly reduced access to their APIs across all apps. You can read more about these changes here: <a href="https://developers.facebook.com/blog/post/2018/04/04/facebook-api-platform-product-changes/">https://developers.facebook.com/blog/post/2018/04/04/facebook-api-platform-product-changes/</a>', 'another-wordpress-classifieds-plugin' ),
            array(
                'options'  => array(
                    'facebook-api' => __( 'Facebook API', 'another-wordpress-classifieds-plugin' ),
                    'webhooks'     => __( 'Zapier/IFTTT Webhooks', 'another-wordpress-classifieds-plugin' ),
                ),
            )
        );

		$this->add_setting( $key, 'sends-listings-to-facebook-automatically', __( 'Send Ads to Facebook Automatically', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, __( 'If checked, Ads will be sent to Facebook shortly after they are posted, enabled or edited, whichever occurs first. Please note that ads will be sent only once and disabled ads cannot be sent to Facebook.', 'another-wordpress-classifieds-plugin' ) );

        $this->add_setting(
            $key,
            'clear-facebook-cache-for-ads-pages',
            __( 'Ask Facebook to clear cache for ads pages', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            false,
            __( 'Clearing Facebook cache is useful to ensure users always see the latest version when the ad is shared on Facebook Pages, Groups and user feeds.' )
        );

        $key = $this->add_section(
            $group,
            __( 'Facebook Application', 'another-wordpress-classifieds-plugin' ),
            'facebook-application',
            10,
            array( $this, 'facebook_application_settings_section' )
        );

        $this->add_setting(
            $key,
            'facebook-app-id',
            __( 'App Id', 'another-wordpress-classifieds-plugin' ),
            'textfield',
            '',
            __( 'An application identifier associates your site, its pages, and visitor actions with a registered Facebook application.', 'another-wordpress-classifieds-plugin' )
        );

        $this->add_setting(
            $key,
            'facebook-app-secret',
            __( 'App Secret', 'another-wordpress-classifieds-plugin' ),
            'textfield',
            '',
            __( 'An application secret is a secret shared between Facebook and your application, similar to a password.', 'another-wordpress-classifieds-plugin' )
        );

        $key = $this->add_section(
            $group,
            __( 'Facebook User Authorization', 'another-wordpress-classifieds-plugin' ),
            'facebook-user-authorization',
            10,
            array( $this, 'facebook_user_authorization_section' )
        );

        $this->add_setting(
            $key,
            'facebook-user-access-token',
            __( 'User Access Token', 'another-wordpress-classifieds-plugin' ),
            'textfield',
            '',
            __( 'You can manually enter your user access token (if you know it) or log in to Facebook to get one using the link above.', 'another-wordpress-classifieds-plugin' )
        );

        $key = $this->add_section(
            $group,
            __( 'Facebook Page and Group Selection', 'another-wordpress-classifieds-plugin' ),
            'facebook-page-and-group-selection',
            10,
            array( $this, 'facebook_page_and_group_selection_section' )
        );

        $this->add_setting(
            $key,
            'facebook-page',
            __( 'Facebook Page', 'another-wordpress-classifieds-plugin' ),
            'radio',
            '',
            '',
            array(
                'options' => array( $this, 'facebook_page_options' ),
            )
        );

        $this->add_setting(
            $key,
            'facebook-group',
            __( 'Facebook Group', 'another-wordpress-classifieds-plugin' ),
            'radio',
            '',
            '',
            array(
                'options' => array( $this, 'facebook_group_options' ),
            )
        );

        $this->add_setting(
            $key,
            'facebook-page-access-token',
            __( 'Facebook Page Access Token', 'another-wordpress-classifieds-plugin' ),
            'textfield',
            '',
            '',
            array(
                'readonly' => true,
            )
        );

        $key = $this->add_section(
            $group,
            __( 'Zapier Integration', 'another-wordpress-classifieds-plugin' ),
            'zapier',
            10,
            array( $this, 'section' )
        );

        $this->add_setting(
            $key,
            'zapier-webhook-for-facebook-page-integration',
            __( 'Zapier webhook used to send ads to a Facebook page', 'another-wordpress-classifieds-plugin' ),
            'textfield',
            '',
            __( 'The plugin will post information to this URL the first time an ad becomes publicly available (after they are posted, enabled or edited) on the website or using the Send to Facebook Page action from the list of classified ads. Disabled ads are excluded.', 'another-wordpress-classifieds-plugin' )
        );

        $key = $this->add_section(
            $group,
            __( 'IFTTT Integration', 'another-wordpress-classifieds-plugin' ),
            'ifttt',
            10,
            array( $this, 'section' )
        );

        $this->add_setting(
            $key,
            'ifttt-webhook-base-url-for-facebook-page-integration',
            __( 'URL used to send requests to IFTTT Webhooks service', 'another-wordpress-classifieds-plugin' ),
            'textfield',
            '',
            __( 'The plugin will post information to the Webhooks service the first time an ad becomes publicly available (after they are posted, enabled or edited) on the website or when someone uses the Send to Facebook Page action from the list of classified ads. Disabled ads are excluded.', 'another-wordpress-classifieds-plugin' )
        );

        $this->add_setting(
            $key,
            'ifttt-webhook-event-name-for-facebook-page-integration',
            __( 'The name of the event that will be posted to the Webhooks service', 'another-wordpress-classifieds-plugin' ),
            'textfield',
            '',
            __( 'The plugin will post information about new ads to a unique URL built using the Webhooks URL and Event Name you define.', 'another-wordpress-classifieds-plugin' )
        );

		// save settings to database
		$this->skip = true;
		$this->save_settings();
		$this->skip = false;
	}

	private function save_settings() {
		update_option( $this->setting_name, $this->options );
	}

	/**
	 * Hook actions and filters required by AWPCP Settings
	 * to work.
	 */
	public function setup() {
		add_action('init', array($this, 'init'), 9999);
		add_action('admin_init', array($this, 'register'));

		// setup validate functions
		add_filter('awpcp_validate_settings_general-settings',
				   array($this, 'validate_general_settings'), 10, 2);
		add_filter('awpcp_validate_settings_pages-settings',
				   array($this, 'validate_classifieds_pages_settings'), 10, 2);
		add_filter('awpcp_validate_settings_payment-settings',
				   array($this, 'validate_payment_settings'), 10, 2);
		add_filter('awpcp_validate_settings_registration-settings',
				   array($this, 'validate_registration_settings'), 10, 2);
		add_filter('awpcp_validate_settings_smtp-settings',
				   array($this, 'validate_smtp_settings'), 10, 2);
        add_filter( 'awpcp_validate_settings_facebook-settings', array( $this, 'validate_facebook_settings' ), 10, 2 );

        add_filter(
            'awpcp_validate_settings_email-settings',
            array( $this, 'validate_email_settings' ),
            10,
            2
        );
	}

	public function init() {
		do_action('awpcp_register_settings', $this);

		// save settings to database
		$this->skip = true;
		$this->save_settings();
		$this->skip = false;

		$this->set_javascript_data();
	}

	private function set_javascript_data() {
		$awpcp = awpcp();

		$awpcp->js->set(
			'show-popup-if-user-did-not-upload-files',
			(bool) $this->get_option( 'show-popup-if-user-did-not-upload-files' )
		);

		$awpcp->js->set( 'overwrite-contact-information-on-user-change', (bool) get_awpcp_option( 'overwrite-contact-information-on-user-change' ) );
		$awpcp->js->set( 'decimal-separator', get_awpcp_option( 'decimal-separator' ) );
		$awpcp->js->set( 'thousands-separator', get_awpcp_option( 'thousands-separator' ) );
		$awpcp->js->set( 'date-format', awpcp_datepicker_format( get_awpcp_option( 'date-format') ) );
		$awpcp->js->set( 'datetime-formats', array(
			'american' => array(
				'date' => 'm/d/Y',
				'time' => 'h:i:s',
				'format' => '<date> <time>',
			),
			'european' => array(
				'date' => 'd/m/Y',
				'time' => 'H:i:s',
				'format' => '<date> <time>',
			),
			'custom' => array(
				'date' => 'l F j, Y',
				'time' => 'g:i a T',
				'format' => '<date> at <time>',
			),
		) );
	}

	public function register() {
        if ( version_compare( phpversion(), '5.3.0', '>='  ) ) {
            $sort_callback = function( $a, $b ) {
                return $a->priority - $b->priority;
            };
        } else {
            $sort_callback = create_function( '$a, $b', 'return $a->priority - $b->priority;');
        }

        uasort( $this->groups, $sort_callback );

		foreach ($this->groups as $group) {

            uasort( $group->sections, $sort_callback );

			foreach ($group->sections as $section) {
				add_settings_section($section->slug, $section->name, $section->callback, $group->slug);
				foreach ($section->settings as $setting) {
					if ( method_exists( $this, $setting->type ) ) {
						$callback = array( $this, $setting->type );
					} else if ( has_filter( 'awpcp-render-setting-' . $setting->name ) || has_filter( 'awpcp-render-setting-type-' . $setting->type ) ) {
						$callback = array( $this, 'render_custom_setting' );
					} else {
						continue;
					}

					$args = array('label_for' => $setting->name, 'setting' => $setting);
					$args = array_merge($args, $setting->args);

					add_settings_field( $setting->name, $setting->label, $callback, $group->slug, $section->slug, $args );
				}
			}
		}
	}


	/* Settings API */

	public function add_group($name, $slug, $priority) {
		$group = new stdClass();
		$group->name = $name;
		$group->slug = $slug;
		$group->priority = $priority;
		$group->sections = array();

		$this->groups[$slug] = $group;

		return $slug;
	}

	public function add_section( $group, $name, $slug, $priority, $callback = null ) {
		$section = new stdClass();
		$section->name = $name;
		$section->slug = $slug;
		$section->priority = $priority;
		$section->callback = $callback ? $callback : array( $this, 'section' );
		$section->settings = array();

		$this->groups[$group]->sections[$slug] = $section;

		return "$group:$slug";
	}

	public function add_setting( $key, $name, $label, $type, $default, $helptext = '', $args = array() ) {
		// add the setting to the right section and group

		list($group, $section) = explode(':', $key);

		if (empty($group) || empty($section)) {
			return false;
		}

		if (isset($this->groups[$group]) &&
			isset($this->groups[$group]->sections[$section])) {
			$setting = new stdClass();
			$setting->name = $name;
			$setting->label = $label;
			$setting->helptext = $helptext;
			$setting->default = $default;
			$setting->type = $type;
			$setting->args = wp_parse_args( $args, array( 'behavior' => array(), ) );

			$this->groups[$group]->sections[$section]->settings[$name] = $setting;
		}

		// make sure the setting is available to other components in the plugin
		if (!isset($this->options[$name])) {
			$this->options[$name] = $default;
		}

		// store the default value
		$this->defaults[$name] = $default;

		return true;
	}

	public function add_validation_rule( $key, $setting_name, $validator, $definition, $message = null ) {
		list( $group, $section ) = explode( ':', $key );

		if ( ! isset( $this->groups[ $group ]->sections[ $section ]->settings[ $setting_name ] ) ) {
			return;
		}

		$setting = $this->groups[ $group ]->sections[ $section ]->settings[ $setting_name ];

		if ( ! is_null( $message ) ) {
			$setting->args['behavior']['validation']['messages'][ $validator ] = $message;
		}

		$setting->args['behavior']['validation']['rules'][ $validator ] = $definition;
	}

	public function add_behavior( $key, $setting_name, $behavior, $definition ) {
		list( $group, $section ) = explode( ':', $key );

		if ( ! isset( $this->groups[ $group ]->sections[ $section ]->settings[ $setting_name ] ) ) {
			return;
		}

		$setting = $this->groups[ $group ]->sections[ $section ]->settings[ $setting_name ];
		$setting->args['behavior']['behavior'][ $behavior ] = $definition;
	}

	public function add_license_setting( $module_name, $module_slug ) {
        $section = $this->enable_licenses_settings_section();

        $setting_label = str_replace( '<module-name>', $module_name, __( '<module-name> License Key', 'another-wordpress-classifieds-plugin' ) );
        $this->add_setting( $section, "$module_slug-license", $setting_label, 'license', '', '', compact( 'module_name', 'module_slug' ) );
	}

	private function enable_licenses_settings_section() {
		$group_slug = 'licenses-settings';
		$section_slug = 'premium-modules';

		if ( ! isset( $this->groups[ $group_slug ] ) ) {
			$this->add_group( __( 'Licenses', 'another-wordpress-classifieds-plugin' ), $group_slug, 100000 );
			$this->add_section( $group_slug, 'Premium Modules', $section_slug, 10, array( $this, 'licenses_section' ) );
		}

		return "$group_slug:$section_slug";
	}

	public function get_option($name, $default='', $reload=false) {
		// reload options
		if ($reload) { $this->load(); }

		if (isset($this->options[$name])) {
			$value = $this->options[$name];
		} else {
			$value = $default;
		}

		// TODO: provide a method for filtering options and move there the code below.
		$strip_slashes_from = array('awpcpshowtheadlayout',
								    'sidebarwidgetaftertitle',
								    'sidebarwidgetbeforetitle',
								    'sidebarwidgetaftercontent',
								    'sidebarwidgetbeforecontent',
								    'adsense',
								    'displayadlayoutcode');

		if (in_array($name, $strip_slashes_from)) {
			$value = stripslashes_deep($value);
		}

        if ( ! is_array( $value ) ) {
            $value = trim( $value );
        }

		return $value;
	}

	public function get_option_default_value($name) {
		if (isset($this->defaults[$name])) {
			return $this->defaults[$name];
		}
		return null;
	}

	/**
	 * @since 3.0.1
	 */
	public function get_option_label($name) {
		$label = null;

		foreach ( $this->groups as $group ) {
			foreach ( $group->sections as $section ) {
				if ( isset( $section->settings[ $name ] ) ) {
					$label = $section->settings[ $name ]->label;
					break 2;
				}
			}
		}

		return $label;
	}

	/**
	 * @param $force boolean - true to update unregistered options
	 */
	public function update_option($name, $value, $force=false) {
		if (isset($this->options[$name]) || $force) {
			$this->options[$name] = $value;
			$this->save_settings();
			return true;
		}
		return false;
	}

	/**
	 * @since 3.2.2
	 */
	public function set_or_update_option( $name, $value ) {
		$this->options[$name] = $value;
		return $this->save_settings();
	}

	/**
	 * @since 3.3
	 */
	public function option_exists( $name ) {
		return isset( $this->options[ $name ] );
	}

	public function set_runtime_option( $name, $value ) {
		$this->runtime_settings[ $name ] = $value;
	}

	public function get_runtime_option( $name, $default = '' ) {
		if ( isset( $this->runtime_settings[ $name ] ) ) {
			return $this->runtime_settings[ $name ];
		} else {
			return $default;
		}
	}

	/* Auxiliar methods to validate settings */

	/**
	 * Validates AWPCP settings before being saved.
	 */
	public function validate($options) {
		if ($this->skip) { return $options; }

		$group = awpcp_post_param('group', '');

		// populate array with all plugin options before attempt validation
		$this->load();
		$options = array_merge($this->options, $options);

		$filters = array('awpcp_validate_settings_' . $group, 'awpcp_validate_settings');

		foreach ($filters as $filter) {
			$_options = apply_filters($filter, $options, $group);
			$options = is_array($_options) ? $_options : $options;
		}

		// make sure we have the updated and validated options
		$this->options = $options;

		return $this->options;
	}

	/**
	 * General Settings checks
	 */
	public function validate_general_settings($options, $group) {
		$this->validate_akismet_settings( $options );
		$this->validate_captcha_settings( $options );

		// Enabling User Ad Management Panel will automatically enable
		// require Registration, if it isn’t enabled. Disabling this feature
		// will not disable Require Registration.
		$setting = 'enable-user-panel';
		if (isset($options[$setting]) && $options[$setting] == 1 && !get_awpcp_option('requireuserregistration')) {
			awpcp_flash(__('Require Registration setting was enabled automatically because you activated the User Ad Management panel.', 'another-wordpress-classifieds-plugin'));
			$options['requireuserregistration'] = 1;
		}

		return $options;
	}

	private function validate_akismet_settings( &$options ) {
		$setting_name = 'use-akismet-in-place-listing-form';
		$is_akismet_enabled_in_place_listing_form = isset( $options[ $setting_name ] ) && $options[ $setting_name ];

		$setting_name = 'use-akismet-in-reply-to-listing-form';
		$is_akismet_enabled_in_reply_to_listing_form = isset( $options[ $setting_name ] ) && $options[ $setting_name ];

		if ( $is_akismet_enabled_in_place_listing_form || $is_akismet_enabled_in_reply_to_listing_form ) {
			$wpcom_api_key = get_option( 'wordpress_api_key' );
			if ( !function_exists( 'akismet_init' ) ) {
				awpcp_flash( __( 'Akismet SPAM control cannot be enabled because Akismet plugin is not installed or activated.', 'another-wordpress-classifieds-plugin' ), 'error' );
				$options[ 'use-akismet-in-place-listing-form' ] = 0;
				$options[ 'use-akismet-in-reply-to-listing-form' ] = 0;
			} else if ( empty( $wpcom_api_key ) ) {
				awpcp_flash( __( 'Akismet SPAM control cannot be enabled because Akismet is not properly configured.', 'another-wordpress-classifieds-plugin' ), 'error' );
				$options[ 'use-akismet-in-place-listing-form' ] = 0;
				$options[ 'use-akismet-in-reply-to-listing-form' ] = 0;
			}
		}
	}

	private function validate_captcha_settings( &$options ) {
	$option_name = 'captcha-enabled-in-place-listing-form';
		$captcha_enabled_in_place_listing_form = isset( $options[ $option_name ] ) && $options[ $option_name ];

		$option_name = 'captcha-enabled-in-reply-to-listing-form';
		$captcha_enabled_in_reply_to_listing_form = isset( $options[ $option_name ] ) && $options[ $option_name ];

		$is_captcha_enabled = $captcha_enabled_in_place_listing_form || $captcha_enabled_in_reply_to_listing_form;

		// Verify reCAPTCHA is properly configured
		if ( $is_captcha_enabled && $options['captcha-provider'] === 'recaptcha' ) {
			if ( empty( $options[ 'recaptcha-public-key' ] ) || empty( $options[ 'recaptcha-private-key' ] ) ) {
				$options['captcha-provider'] = 'math';
			}

			if ( empty( $options[ 'recaptcha-public-key' ] ) && empty( $options[ 'recaptcha-private-key' ] )  ) {
				awpcp_flash( __( "reCAPTCHA can't be used because the public key and private key settings are required for reCAPTCHA to work properly.", 'another-wordpress-classifieds-plugin' ), 'error' );
			} else if ( empty( $options[ 'recaptcha-public-key' ] ) ) {
				awpcp_flash( __( "reCAPTCHA can't be used because the public key setting is required for reCAPTCHA to work properly.", 'another-wordpress-classifieds-plugin' ), 'error' );
			} else if ( empty( $options[ 'recaptcha-private-key' ] ) ){
				awpcp_flash( __( "reCAPTCHA can't be used because the private key setting is required for reCAPTCHA to work properly.", 'another-wordpress-classifieds-plugin' ), 'error' );
			}
		}
	}

	/**
	 * Registration Settings checks
	 */
	public function validate_registration_settings($options, $group) {
		// if Require Registration is disabled, User Ad Management Panel should be
		// disabled as well.
		$setting = 'requireuserregistration';
		if (isset($options[$setting]) && $options[$setting] == 0 && get_awpcp_option('enable-user-panel')) {
			awpcp_flash(__('User Ad Management panel was automatically deactivated because you disabled Require Registration setting.', 'another-wordpress-classifieds-plugin'));
			$options['enable-user-panel'] = 0;
		}

		if (isset($options[$setting]) && $options[$setting] == 0 && get_awpcp_option('enable-credit-system')) {
			awpcp_flash(__('Credit System was automatically disabled because you disabled Require Registration setting.', 'another-wordpress-classifieds-plugin'));
			$options['enable-credit-system'] = 0;
		}

		return $options;
	}

	/**
	 * Payment Settings checks
	 * XXX: Referenced in FAQ: http://awpcp.com/forum/faq/why-doesnt-my-currency-code-change-when-i-set-it/
	 */
	public function validate_payment_settings($options, $group) {
		$setting = 'paypalcurrencycode';

		if ( isset( $options[ $setting ] ) && ! awpcp_paypal_supports_currency( $options[ $setting ] ) ) {
			$currency_codes = awpcp_paypal_supported_currencies();
			$message = __( 'There is a problem with the PayPal Currency Code you have entered. It does not match any of the codes in our list of curencies supported by PayPal.', 'another-wordpress-classifieds-plugin' );
			$message.= '<br/><br/><strong>' . __( 'The available currency codes are', 'another-wordpress-classifieds-plugin' ) . '</strong>:<br/>';
			$message.= join(' | ', $currency_codes);
			awpcp_flash($message);

			$options[$setting] = 'USD';
		}

		$setting = 'enable-credit-system';
		if (isset($options[$setting]) && $options[$setting] == 1 && !get_awpcp_option('requireuserregistration')) {
			awpcp_flash(__('Require Registration setting was enabled automatically because you activated the Credit System.', 'another-wordpress-classifieds-plugin'));
			$options['requireuserregistration'] = 1;
		}

		if (isset($options[$setting]) && $options[$setting] == 1 && !get_awpcp_option('freepay')) {
			awpcp_flash(__('Charge Listing Fee setting was enabled automatically because you activated the Credit System.', 'another-wordpress-classifieds-plugin'));
			$options['freepay'] = 1;
		}

		$setting = 'freepay';
		if (isset($options[$setting]) && $options[$setting] == 0 && get_awpcp_option('enable-credit-system')) {
			awpcp_flash(__('Credit System was disabled automatically because you disabled Charge Listing Fee.', 'another-wordpress-classifieds-plugin'));
			$options['enable-credit-system'] = 0;
		}


		return $options;
	}

	/**
	 * SMTP Settings checks
	 */
	public function validate_smtp_settings($options, $group) {
		// Not sure if this works, but that's what the old code did
		$setting = 'smtppassword';
		if (isset($options[$setting])) {
			$options[$setting] = md5($options[$setting]);
		}

		return $options;
	}

    public function validate_email_settings( $options, $group ) {
        $settings = array(
            'awpcpadminemail' => __( '<new-value> is not a valid email address. Please check the value you entered to use as the FROM email address for outgoing messages.', 'another-wordpress-classifieds-plugin' ),
            'admin-recipient-email' => __( '<new-value> is not a valid email address. Please check the value you entered to use as recipient email address for admin notifications.', 'another-wordpress-classifieds-plugin' ),
        );

        foreach( $settings as $setting_name => $message ) {
            $validated_value = $this->validate_email_setting(
                $options,
                $setting_name,
                $message
            );

            if ( is_null( $validated_value ) ) {
                continue;
            }

            $options[ $setting_name ] = $validated_value;
        }

        return $options;
    }

    private function validate_email_setting( $options, $setting_name, $message ) {
        if ( ! isset( $options[ $setting_name ] ) ) {
            return null;
        }

        if ( empty( $options[ $setting_name ] ) ) {
            return $options[ $setting_name ];
        }

        if ( ! awpcp_is_valid_email_address( $options[ $setting_name ] ) ) {
            $new_value = '<strong>' . esc_html( $options[ $setting_name ] ) . '</strong>';
            $message = str_replace( '<new-value>', $new_value, $message );

            awpcp_flash( $message, 'notice notice-error' );

            return $this->get_option( $setting_name );
        }

        return $options[ $setting_name ];
    }

	/**
	 * Classifieds Pages Settings checks
	 */
	public function validate_classifieds_pages_settings($options, $group) {
		global $wpdb, $wp_rewrite;

		$pageids = awpcp_get_plugin_pages_ids();
		$pages_updated = 0;

		foreach ( awpcp_pages() as $key => $data ) {
			$id = intval( $pageids[ $key ] );

			if ( $id <= 0 ) {
				continue;
			}

			$page = get_post( $id );

			if ( is_null( $page ) ) {
				continue;
			}

			if ( sanitize_title( $page->post_title ) != $page->post_name ) {
				$post_name = $page->post_name;
			} else {
				$post_name = sanitize_title( $options[ $key ] );
			}

			$page = array(
				'ID' => $id,
				'post_title' => add_slashes_recursive( $options[ $key ] ),
				'post_name' => $post_name,
			);

			wp_update_post($page);

			$pages_updated = $pages_updated + 1;
		}

		if ( $pages_updated ) {
			do_action( 'awpcp-pages-updated' );
		}

		flush_rewrite_rules();

		return $options;
	}

    /**
     * @since 3.8.6
     */
    public function validate_facebook_settings( $options, $group ) {
        $options['facebook-app-id']             = trim( $options['facebook-app-id'] );
        $options['facebook-app-secret']         = trim( $options['facebook-app-secret'] );
        $options['facebook-user-access-token']  = trim( $options['facebook-user-access-token'] );

        if ( $options['facebook-app-id'] !== $this->get_option( 'facebook-app-id' ) || $options['facebook-app-secret'] !== $this->get_option( 'facebook-app-secret' ) ) {
            $options['facebook-user-access-token'] = '';
            $options['facebook-page']              = '';
            $options['facebook-group']             = '';
            $options['facebook-page-access-token'] = '';
        }

        if ( $options['facebook-user-access-token'] !== $this->get_option( 'facebook-user-access-token' ) ) {
            $options['facebook-page']              = '';
            $options['facebook-group']             = '';
            $options['facebook-page-access-token'] = '';
        }

        if ( ! empty( $options['facebook-page'] ) && 'none' === $options['facebook-page'] ) {
            $options['facebook-page-access-token'] = '';
        }

        if ( ! empty( $options['facebook-page'] ) && strpos( $options['facebook-page'], '|' ) !== false ) {
            $parts = explode( '|', $options['facebook-page'] );

            $options['facebook-page']              = $parts[0];
            $options['facebook-page-access-token'] = $parts[1];
        }

        return $options;
    }

	/* Auxiliar methods to render settings forms */

	public function textfield($args, $type='text') {
		$setting = $args['setting'];

		$value = esc_html(stripslashes($this->get_option($setting->name)));

		$html = '<input id="'. $setting->name . '" class="regular-text" ';
		$html.= 'value="' . $value . '" type="' . $type . '" ';
		$html.= 'name="awpcp-options[' . $setting->name . ']" ';

        if ( ! empty( $args['readonly'] ) ) {
            $html .= 'disabled="disabled" ';
        }

		if ( ! empty( $setting->args['behavior'] ) ) {
			$html.= 'awpcp-setting="' . esc_attr( json_encode( $setting->args['behavior'] ) ) . '" />';
		} else {
			$html.= '/>';
		}

		$html.= strlen($setting->helptext) > 20 ? '<br/>' : '&nbsp;';
		$html.= '<span class="description">' . $setting->helptext . '</span>';

		echo $html;
	}

	public function password($args) {
		return $this->textfield($args, 'password');
	}

	public function checkbox($args) {
		$setting = $args['setting'];

		$value = intval($this->get_option($setting->name));

		$html = '<input type="hidden" value="0" name="awpcp-options['. $setting->name .']" ';

        if ( ! empty( $setting->args['behavior'] ) ) {
            $html.= 'awpcp-setting="' . esc_attr( json_encode( $setting->args['behavior'] ) ) . '" />';
        } else {
            $html.= '/>';
        }

		$html.= '<input id="'. $setting->name . '" value="1" ';
		$html.= 'type="checkbox" name="awpcp-options[' . $setting->name . ']" ';

		if ( $value ) {
			$html.= 'checked="checked" ';
		}

		if ( ! empty( $setting->args['behavior'] ) ) {
			$html.= 'awpcp-setting="' . esc_attr( json_encode( $setting->args['behavior'] ) ) . '" />';
		} else {
			$html.= '/>';
		}

		$html.= '<label for="'. $setting->name . '">';
		$html.= '&nbsp;<span class="description">' . $setting->helptext . '</span>';
		$html.= '</label>';

		echo $html;
	}

	public function textarea($args) {
		$setting = $args['setting'];

		$value = esc_html(stripslashes($this->get_option($setting->name)));

		$html = '<textarea id="'. $setting->name . '" class="all-options" ';
		$html.= 'name="awpcp-options['. $setting->name .']">';
		$html.= $value;
		$html.= '</textarea><br/>';
		$html.= '<span class="description">' . $setting->helptext . '</span>';

		echo $html;
	}

	public function select($args) {
		$setting = $args['setting'];
		$options = $args['options'];

		$current = esc_html(stripslashes($this->get_option($setting->name)));

		$html = '<select id="' . $setting->name . '" name="awpcp-options['. $setting->name .']">';
		foreach ($options as $value => $label) {
			if ($value == $current) {
				$html.= '<option value="' . $value . '" selected="selected">' . $label . '</option>';
			} else {
				$html.= '<option value="' . $value . '">' . $label . '</option>';
			}
		}
		$html.= '</select><br/>';
		$html.= '<span class="description">' . $setting->helptext . '</span>';

		echo $html;
	}

	public function radio($args) {
		$setting = $args['setting'];
        $options = array();

        if ( is_array( $args['options'] ) ) {
            $options = $args['options'];
        }

        if ( is_callable( $args['options'] ) ) {
            $options = call_user_func( $args['options'] );
        }

		$current = esc_html(stripslashes($this->get_option($setting->name)));

		$html = '';
        foreach ( $options as $key => $label ) {
            $value = $key;

            if ( is_array( $label ) ) {
                $value = $label['value'];
                $label = $label['label'];
            }

			$id = "{$setting->name}-$key";
			$label = ' <label for="' . $id . '">' . $label . '</label>';

			$html.= '<input id="' . $id . '"type="radio" value="' . $value . '" ';
			$html.= 'name="awpcp-options['. $setting->name .']" ';
            if ( $key == $current ) {
				$html.= 'checked="checked" />' . $label;
			} else {
				$html.= '>' . $label;
			}
			$html.= '<br/>';
        }
		$html.= '<span class="description">' . $setting->helptext . '</span>';

		echo $html;
	}

	public function choice( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'choices' => array(),
			'multiple' => true,
		) );

		$setting = $args['setting'];

		$field_name = 'awpcp-options[' . $setting->name . '][]';
		$field_type = $args['multiple'] ? 'checkbox' : 'radio';
		$selected = array_filter( $this->get_option( $setting->name, array() ), 'strlen' );

		$html = array( sprintf( '<input type="hidden" name="%s" value="">', $field_name ) );

		foreach ( $args['choices'] as $value => $label ) {
			$id = "{$setting->name}-$value";
			$checked = in_array( $value, $selected ) ? 'checked="checked"' : '';

			$html_field = '<input id="%s" type="%s" name="%s" value="%s" %s />';
			$html_field = sprintf( $html_field, $id, $field_type, $field_name, $value, $checked );
			$html_label = '<label for="' . $id . '">' . $label . '</label><br/>';

			$html[] = $html_field . '&nbsp;' . $html_label;
		}

		$html[] = '<span class="description">' . $setting->helptext . '</span>';

		echo join( '', $html );
	}

	public function categories( $args ) {
		$setting = $args['setting'];

        $params = array(
        	'field_name' => 'awpcp-options[' . $setting->name . ']',
            'selected' => $this->get_option( $setting->name ),

            'first_level_ul_class' => 'awpcp-categories-list',
            'no-cache' => time()
        );
		$checklist = awpcp_categories_checkbox_list_renderer()->render( $params );

        echo sprintf( '<div class="cat-checklist category-checklist">%s</div>', $checklist );
		echo '<span class="description">' . $setting->helptext . '</span>';
	}

	public function license( $args ) {
		$setting = $args['setting'];

		$module_name = $args['module_name'];
		$module_slug = $args['module_slug'];

		$this->licenses_manager = awpcp_licenses_manager();

		$license = $this->get_option( $setting->name );

		echo '<input id="' . $setting->name . '" class="regular-text" type="text" name="awpcp-options[' . $setting->name . ']" value="' . esc_attr( $license ) . '">';

		if ( ! empty( $license ) ) {
			if ( $this->licenses_manager->is_license_valid( $module_name, $module_slug ) ) {
				echo '<input class="button-secondary" type="submit" name="awpcp-deactivate-' . $module_slug . '-license" value="' . __( 'Deactivate', 'another-wordpress-classifieds-plugin' ) . '"/>';
				echo '<br>' . str_replace( '<license-status>', '<span class="awpcp-license-status awpcp-license-valid">' . __( 'active', 'another-wordpress-classifieds-plugin' ) . '</span>.', __( 'Status: <license-status>', 'another-wordpress-classifieds-plugin' ) );
			} else if ( $this->licenses_manager->is_license_inactive( $module_name, $module_slug ) ) {
				echo '<input class="button-secondary" type="submit" name="awpcp-activate-' . $module_slug . '-license" value="' . __( 'Activate', 'another-wordpress-classifieds-plugin' ) . '"/>';
				echo '<br>' . str_replace( '<license-status>', '<span class="awpcp-license-status awpcp-license-inactive">' . __( 'inactive', 'another-wordpress-classifieds-plugin' ) . '</span>.', __( 'Status: <license-status>', 'another-wordpress-classifieds-plugin' ) );
			} else {
				echo '<input class="button-secondary" type="submit" name="awpcp-activate-' . $module_slug . '-license" value="' . __( 'Activate', 'another-wordpress-classifieds-plugin' ) . '"/>';

				$contact_url = 'http://awpcp.com/contact';
				$contact_message = __( "Click the button above to check the status of your license. Please <contact-link>contact customer support</a> if you think the reported status is wrong.", 'another-wordpress-classifieds-plugin' );

				echo '<br>' . str_replace( '<contact-link>', '<a href="' . esc_url( $contact_url ) . '" target="_blank">', $contact_message );

				if ( $this->licenses_manager->is_license_expired( $module_name, $module_slug ) ) {
					echo '<br>' . str_replace( '<license-status>', '<span class="awpcp-license-status awpcp-license-expired">' . __( 'expired', 'another-wordpress-classifieds-plugin' ) . '</span>.', __( 'Status: <license-status>', 'another-wordpress-classifieds-plugin' ) );
				} else if ( $this->licenses_manager->is_license_disabled( $module_name, $module_slug ) ) {
					echo '<br>' . str_replace( '<license-status>', '<span class="awpcp-license-status awpcp-license-invalid">' . __( 'disabled', 'another-wordpress-classifieds-plugin' ) . '</span>.', __( 'Status: <license-status>', 'another-wordpress-classifieds-plugin' ) );
				} else {
					echo '<br>' . str_replace( '<license-status>', '<span class="awpcp-license-status awpcp-license-invalid">' . __( 'unknown', 'another-wordpress-classifieds-plugin' ) . '</span>.', __( 'Status: <license-status>', 'another-wordpress-classifieds-plugin' ) );
				}
			}
			wp_nonce_field( 'awpcp-update-license-status-nonce', 'awpcp-update-license-status-nonce' );
		}
	}

	/**
	 * Dummy function to render an (empty) introduction
	 * for each settings section.
	 */
	public function section($args) {
	}

    /**
     * @since 3.7.6
     */
    public function licenses_section( $args ) {
        echo '<p class="description">' . $this->get_licenses_section_description() . '</p>';
    }

    /**
     * @since 3.7.6
     */
    private function get_licenses_section_description() {
        $ip_address = awpcp_get_server_ip_address();

        if ( ! $ip_address ) {
            return '';
        }

        $description = _x( 'The IP address of your server is <ip-address>. Please make sure to include that information if you need to contact support about problems trying to activate your licenses.', 'settings', 'WPBDM' );
        $description = str_replace( '<ip-address>', '<strong>' . $ip_address . '</strong>', $description );

        return $description;
    }

	public function section_date_time_format($args) {
		$link = '<a href="http://codex.wordpress.org/Formatting_Date_and_Time">%s</a>.';
		echo sprintf( $link, __( 'Documentation on date and time formatting', 'another-wordpress-classifieds-plugin' ) );
	}

    /**
     * @since 3.8.6
     */
    public function facebook_application_settings_section( $args ) {
        $content = __( 'You can find your application information in the <a>Facebook Developer Apps</a> page.', 'another-wordpress-classifieds-plugin' );
        $content = str_replace( '<a>', '<a href="https://developers.facebook.com/apps/" target="_blank">', $content );

        echo $content; // XSS Ok.
    }

    /**
     * @since 3.8.6
     */
    public function facebook_user_authorization_section( $args ) {
        // Choosing Public is important because:
        // - http://stackoverflow.com/a/19653226/201354
        // - https://github.com/drodenbaugh/awpcp/issues/1288#issuecomment-134198377
        $content  = '<p>' . esc_html__( 'AWPCP needs to get an authorization token from Facebook to work correctly. You\'ll be redirected to Facebook to login. AWPCP does not store or obtain any personal information from your profile.', 'another-wordpress-classifieds-plugin' ) . '</p>';
        $content .= '<p>' . esc_html__( "Please choose Public as the audience for posts made by the application, even if you are just testing the integration. Facebook won't allow us to post content in some cases if you choose something else.", 'another-wordpress-classifieds-plugin' ) . '</p>';

        if ( $this->get_option( 'facebook-app-id' ) && $this->get_option( 'facebook-app-secret' ) ) {
            $facebook = AWPCP_Facebook::instance();

            $redirect_uri         = add_query_arg( 'obtain_user_token', 1, admin_url( '/admin.php?page=awpcp-admin-settings&g=facebook-settings' ) );
            $required_permissions = $facebook->get_required_permissions();
            $login_url            = $facebook->get_login_url( $redirect_uri, implode( ',', $required_permissions ) );

            $content .= '<p><a href="' . $login_url . '">' . __( 'Click here to obtain an access token from Facebook', 'another-wordpress-classifieds-plugin' ) . '</a></p>';
        } else {
            $content .= '<p><strong>' . esc_html__( 'Please provide a value for the App Id and App Secret settings before trying to get an access token from Facebook.', 'another-wordpress-classifieds-plugin' ) . '</strong></p>';
        }

        echo $content; // XSS Ok.
    }

    /**
     * @sicne 3.8.6
     */
    public function facebook_page_and_group_selection_section( $args ) {
        $content  = '<p><strong>' . esc_html__( 'Available Facebook Pages and Groups will be displayed after you enter a valid User Access Token.', 'another-wordpress-classifieds-plugin' ) . '</strong></p>';
        $content .= '<p>' . __( 'As of April 4, 2018, all applications need to go through <a href="https://developers.facebook.com/docs/apps/review" rel="nofollow">App Review</a> in order to get access to the <a href="https://developers.facebook.com/docs/graph-api/reference/page/" rel="nofollow">Page API</a> and <a href="https://developers.facebook.com/docs/graph-api/reference/user/groups/" rel="nofollow">Groups API</a>. That means that you may need to <a href="https://developers.facebook.com/docs/facebook-login/review" rel="nofollow">submit your app for review</a> (ask for the <code>manage_pages</code>, <code>publish_pages</code>, <code>publish_to_groups</code> permissions), before AWPCP can display the list of pages and groups you manage and be able to post classifieds ads to those groups and pages.', 'another-wordpress-classifieds-plugin' ) . '</p>';

        echo $content;
    }

    /**
     * @since 3.8.6
     */
    public function facebook_page_options() {
        $facebook       = AWPCP_Facebook::instance();
        $facebook_pages = $facebook->get_user_pages();

        if ( empty( $facebook_pages ) ) {
            return array();
        }

        $pages         = array(
            'none' => __( 'None (Do not sent ads to a Facebook Page)', 'another-wordpress-classifieds-plugin' ),
        );

        foreach ( $facebook_pages as $page ) {
            $page_name = $page['name'];

            if ( ! empty( $page['profile'] ) ) {
                $page_name = $page_name . ' ' . __( '(Your own profile page)', 'another-wordpress-classifieds-plugin' );
            }

            $pages[ $page['id'] ]         = array(
                'value' => "{$page['id']}|{$page['access_token']}",
                'label' => $page_name,
            );
        }

        return $pages;
    }

    /**
     * @since 3.8.6
     */
    public function facebook_group_options() {
        $facebook        = AWPCP_Facebook::instance();
        $facebook_groups = $facebook->get_user_groups();

        if ( empty( $facebook_groups ) ) {
            return array();
        }

        $groups   = array(
            'none' => __( 'None (Do not sent ads to a Facebook Group)', 'another-wordpress-classifieds-plugin' ),
        );

        foreach ( $facebook_groups as $group ) {
            $groups[ $group['id'] ] = $group['name'];
        }

        return $groups;
    }

	/**
	 * @since 3.6
	 */
	public function render_custom_setting( $args ) {
		$content = apply_filters( 'awpcp-render-setting-' . $args['setting']->name, null, $args, $this );

		if ( ! empty( $content ) ) {
			echo $content;
			return;
		}

		$content = apply_filters( 'awpcp-render-setting-type-' . $args['setting']->type, null, $args, $this );

		if ( ! empty( $content ) ) {
			echo $content;
			return;
		}

		$message = __( 'Setting <setting-name> not available.', 'another-wordpress-classifieds-plugin' );
		$message = str_replace( '<setting-name>', '<strong>' . $args['setting']->name . '</strong>', $message );

		echo $message;
	}
}
