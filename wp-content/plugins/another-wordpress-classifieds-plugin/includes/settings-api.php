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

		$group = $this->add_group( __( 'Private Settings', 'AWPCP' ), 'private-settings', 0 );

		// Group: Classified Pages

		$group = $this->add_group(__('Classifieds Pages', 'AWPCP'), 'pages-settings', 20);

		// Section: Classifieds Pages - Default

		$key = $this->add_section($group, __('Classifieds Pages', 'AWPCP'), 'default', 10, array($this, 'section'));

		$this->add_setting( $key, 'main-page-name', __( 'AWPCP Main page', 'AWPCP' ), 'textfield', 'AWPCP', __( 'Name for Classifieds page.', 'AWPCP' ) );
		$this->add_setting( $key, 'show-ads-page-name', __( 'Show Ad page', 'AWPCP' ), 'textfield', 'Show Ad', __( 'Name for Show Ads page.', 'AWPCP' ) );
		$this->add_setting( $key, 'place-ad-page-name', __( 'Place Ad page', 'AWPCP' ), 'textfield', 'Place Ad', __( 'Name for Place Ads page.', 'AWPCP' ) );
		$this->add_setting( $key, 'edit-ad-page-name', __( 'Edit Ad page', 'AWPCP' ), 'textfield', 'Edit Ad', __( 'Name for edit ad page.', 'AWPCP' ) );
		$this->add_setting( $key, 'renew-ad-page-name', __( 'Renew Ad page', 'AWPCP' ), 'textfield', 'Renew Ad', __( 'Name for Renew Ad page.', 'AWPCP' ) );
		$this->add_setting( $key, 'reply-to-ad-page-name', __( 'Reply to Ad page', 'AWPCP' ), 'textfield', 'Reply To Ad', __( 'Name for Reply to Ad page.', 'AWPCP' ) );
		$this->add_setting( $key, 'browse-ads-page-name', __( 'Browse Ads page', 'AWPCP' ), 'textfield', 'Browse Ads', __( 'Name for Browse Ads page.', 'AWPCP' ) );
		$this->add_setting( $key, 'search-ads-page-name', __( 'Search Ads page', 'AWPCP' ), 'textfield', 'Search Ads', __( 'Name for Search Ads page.', 'AWPCP' ) );
		$this->add_setting( $key, 'browse-categories-page-name', __( 'Browse Categories page', 'AWPCP' ), 'textfield', 'Browse Categories', __( 'Name for Browse Categories page.', 'AWPCP' ) );
		$this->add_setting( $key, 'view-categories-page-name', __( 'View Categories page', 'AWPCP' ), 'textfield', 'View Categories', __( 'Name for categories view page. (Dynamic Page)', 'AWPCP' ) );
		$this->add_setting( $key, 'payment-thankyou-page-name', __( 'Payment Thank You page', 'AWPCP' ), 'textfield', 'Payment Thank You', __( 'Name for Payment Thank You page.', 'AWPCP' ) );
		$this->add_setting( $key, 'payment-cancel-page-name', __( 'Payment Cancel page', 'AWPCP' ), 'textfield', 'Cancel Payment', __( 'Name for Payment Cancel page.', 'AWPCP' ) );

		// Group: Ad/Listings

		$group = $this->add_group(__('Ad/Listings', 'AWPCP'), 'listings-settings', 30);

		// Section: Ad/Listings - Regions

		$key = $this->add_section( $group, __( 'Regions Settings', 'AWPCP' ), 'regions-settings', 20, array( $this, 'section' ) );

		$this->add_setting( $key, 'allow-regions-modification', __( 'Allow Regions modification', 'AWPCP' ), 'checkbox', 1, __( 'If enabled, users will be allowed to change the region information associated with their Ads.', 'AWPCP' ) );
		$this->add_setting( $key, 'allow-user-to-search-in-multiple-regions', __( 'Allow users to search Ads in multiple regions', 'AWPCP' ), 'checkbox', 0, __( 'If enabled, users will be allowed to search Ads in multiple regions.', 'AWPCP' ) );

		// Section: Ad/Listings - Layout and Presentation

		$key = $this->add_section($group, __('Layout and Presentation', 'AWPCP'), 'layout', 30, array($this, 'section'));

		$this->add_setting( $key, 'show-ad-preview-before-payment', __( 'Show Ad preview before payment.', 'AWPCP' ), 'checkbox', 0, __( 'If enabled, a preview of the Ad being posted will be shown after the images have been uploaded and before the user is asked to pay. The user is allowed to go back and edit the Ad details and uploaded images or proceed with the posting process.', 'AWPCP' ) );
		$this->add_setting( $key, 'allowhtmlinadtext', __( 'Allow HTML in Ad text', 'AWPCP' ), 'checkbox', 0, __( 'Allow HTML in ad text (Not recommended).', 'AWPCP' ) );
		$this->add_setting( $key, 'htmlstatustext', __( 'Display this text above ad detail text input box on ad post page', 'AWPCP' ), 'textarea', __( 'No HTML Allowed', 'AWPCP' ), '');
		$this->add_setting( $key, 'characters-allowed-in-title', __( 'Maximum Ad title length', 'AWPCP' ), 'textfield', 100, __( 'Number of characters allowed in Ad title. Please note this is the default value and can be overwritten in Fees and Subscription Plans.', 'AWPCP' ) );
		$this->add_setting( $key, 'maxcharactersallowed', __( 'Maximum Ad details length', 'AWPCP' ), 'textfield', 750, __( 'Number of characters allowed in Ad details. Please note this is the default value and can be overwritten in Fees and Subscription Plans.', 'AWPCP' ) );
		$this->add_setting( $key, 'words-in-listing-excerpt', __( 'Number of words in Ad excerpt', 'AWPCP' ), 'textfield', 20, __( 'Number of words shown by the Ad excerpt placeholder.', 'AWPCP' ) );
		$this->add_setting( $key, 'hidelistingcontactname', __( 'Hide contact name to anonymous users?', 'AWPCP' ), 'checkbox', 0, __( 'Hide listing contact name to anonymous (non logged in) users.', 'AWPCP' ) );
		$this->add_setting( $key, 'displayadlayoutcode', __( 'Ad Listings page layout', 'AWPCP' ),
							'textarea', '
							<div class="$awpcpdisplayaditems $isfeaturedclass">
								<div style="width:$imgblockwidth; padding:5px; float:left; margin-right:20px;">
									$awpcp_image_name_srccode
								</div>
								<div style="width:50%; padding:5px; float:left;">
									<h4>$title_link</h4>
									$excerpt
								</div>
								<div style="padding:5px; float:left;">
									$awpcpadpostdate
									$awpcp_city_display
									$awpcp_state_display
									$awpcp_display_adviews
									$awpcp_display_price
									$awpcpextrafields
								</div>
								<span class="fixfloat"></span>
							</div>
							<div class="fixfloat"></div>', __( 'Modify as needed to control layout of ad listings page. Maintain code formatted as \$somecodetitle. Changing the code keys will prevent the elements they represent from displaying.', 'AWPCP' ) );
		$this->add_setting( $key, 'awpcpshowtheadlayout', __( 'Single Ad page layout', 'AWPCP' ),
							'textarea', '
							<div id="showawpcpadpage">
								<div class="awpcp-title">$ad_title</div><br/>
								<div class="showawpcpadpage">
									$featureimg
									<div class="awpcp-subtitle">' . __( "Contact Information","AWPCP" ). '</div>
									<a href="$codecontact">' . __("Contact","AWPCP") . ' $adcontact_name</a>
									$adcontactphone
									$location
									$awpcpvisitwebsite
								</div>
								$aditemprice
								$awpcpextrafields
								<div class="fixfloat"></div>
								$showadsense1
								<div class="showawpcpadpage">
									<div class="awpcp-subtitle">' . __( "More Information", "AWPCP" ) . '</div>
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
							</div>', __( 'Modify as needed to control layout of single ad view page. Maintain code formatted as \$somecodetitle. Changing the code keys will prevent the elements they represent from displaying.', 'AWPCP' ) );

        $this->add_setting(
            $key,
            'allow-wordpress-shortcodes-in-single-template',
            __( 'Allow WordPress Shortcodes in Single Ad page layout' ),
            'checkbox',
            0,
            __( 'Shortcodes executed this way will be executed as if they were entered in the content of the WordPress page showing the listing (normally the Show Ad page, but in general any page that has the AWPCPSHOWAD shortcode).', 'AWPCP' )
        );

		$radio_options = array(1 => __( 'Date (newest first)', 'AWPCP' ),
							   9 => __( 'Date (oldest first)', 'AWPCP' ),
							   2 => __( 'Title (ascending)', 'AWPCP' ),
							   10 => __( 'Title (descending)', 'AWPCP' ),
							   3 => __( 'Paid status and date (paid first, then most recent)', 'AWPCP' ),
							   4 => __( 'Paid status and title (paid first, then by title)', 'AWPCP' ),
							   5 => __( 'Views (most viewed first, then by title)', 'AWPCP' ),
							   6 => __( 'Views (most viewed first, then by date)', 'AWPCP' ),
							   11 => __( 'Views (least viewed first, then by title)', 'AWPCP' ),
							   12 => __( 'Views (least viewed first, then by date)', 'AWPCP' ),
							   7 => __( 'Price (high to low, then by date)', 'AWPCP' ),
							   8 => __( 'Price (low to high, then by date)', 'AWPCP' ),
							);

		$this->add_setting( $key, 'groupbrowseadsby', __( 'Order Ad Listings by', 'AWPCP' ), 'select', 1, '', array('options' => $radio_options));
		$this->add_setting( $key, 'search-results-order', __( 'Order Ad Listings in Search results by', 'AWPCP' ), 'select', 1, '', array('options' => $radio_options));
		// $this->add_setting($key, 'groupsearchresultsby', 'Group Ad Listings search results by', 'radio', 1, '', array('options' => $radio_options));
		$this->add_setting( $key, 'adresultsperpage', __( 'Default number of Ads per page', 'AWPCP' ), 'textfield', 10, '');

		$pagination_options = array( 5, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 500 );
		$this->add_setting( $key, 'pagination-options', __( 'Pagination Options', 'AWPCP' ), 'choice', $pagination_options, '', array( 'choices' => array_combine( $pagination_options, $pagination_options ) ) );

		$this->add_setting( $key, 'buildsearchdropdownlists', __( 'Limits search to available locations.', 'AWPCP' ), 'checkbox', 0, __( 'The search form can attempt to build drop down country, state, city and county lists if data is available in the system. Note that with the regions module installed the value for this option is overridden.', 'AWPCP' ) );
		$this->add_setting( $key, 'showadcount', __( 'Show Ad count in categories', 'AWPCP' ), 'checkbox', 1, __( 'Show how many ads a category contains.', 'AWPCP' ) );
		$this->add_setting( $key, 'hide-empty-categories', __( 'Hide empty categories?', 'AWPCP' ), 'checkbox', 0, __( "If checked, categories with 0 listings in it won't be shown.", 'AWPCP' ) );

        $this->add_setting(
            $key,
            'displayadviews',
            __( 'Show Ad views', 'AWPCP' ),
            'checkbox',
            1,
            __( 'Show the number of times the ad has been viewed (simple count made by AWPCP &endash; warning, may not be accurate!)', 'AWPCP' )
        );

		$this->add_setting( $key, 'hyperlinkurlsinadtext', __( 'Make URLs in ad text clickable', 'AWPCP' ), 'checkbox', 0, '' );
		$this->add_setting( $key, 'visitwebsitelinknofollow', __( 'Add no follow to links in Ads', 'AWPCP' ), 'checkbox', 1, '' );

		// Section: Ad/Listings - Menu Items

		$key = $this->add_section( $group, __( 'Menu Items', 'AWPCP' ), 'menu-items', 60, array( $this, 'section' ) );

		$this->add_setting( $key, 'show-menu-item-place-ad', __( 'Show Place Ad menu item', 'AWPCP' ), 'checkbox', 1, '' );
		$this->add_setting( $key, 'show-menu-item-edit-ad', __( 'Show Edit Ad menu item', 'AWPCP' ), 'checkbox', 1, '' );
		$this->add_setting( $key, 'show-menu-item-browse-ads', __( 'Show Browse Ads menu item', 'AWPCP' ), 'checkbox', 1, '' );
		$this->add_setting( $key, 'show-menu-item-search-ads', __( 'Show Search Ads menu item', 'AWPCP' ), 'checkbox', 1, '' );

		// Group: Payment Settings

		$group = $this->add_group( __( 'Payment', 'AWPCP') , 'payment-settings', 40 );

		// Section: Payment Settings - PayPal

		$key = $this->add_section( $group, __( 'PayPal Settings', 'AWPCP' ), 'paypal', 20, array( $this, 'section' ) );

		$this->add_setting($key, 'activatepaypal', __( 'Activate PayPal?', 'AWPCP' ), 'checkbox', 1, __( 'Activate PayPal?', 'AWPCP' ) );

		$this->add_setting(
			$key,
			'paypalemail',
			__( 'PayPal receiver email', 'AWPCP' ),
			'textfield',
			'',
			__( 'Email address for PayPal payments (if running in pay mode and if PayPal is activated).', 'AWPCP' )
		);

		$this->add_validation_rule( $key, 'paypalemail', 'required', array( 'depends' => 'activatepaypal' ) );
		$this->add_validation_rule( $key, 'paypalemail', 'email', true, __( 'Please enter a valid email address.', 'AWPCP' ) );
		$this->add_behavior( $key, 'paypalemail', 'enabledIf', 'activatepaypal' );

		$this->add_setting(
			$key,
			'paypalcurrencycode',
			__( 'PayPal currency code', 'AWPCP' ),
			'textfield',
			'USD',
			__( 'The currency in which you would like to receive your PayPal payments', 'AWPCP' )
		);

		$supported_currencies = awpcp_paypal_supported_currencies();
		$message = __( 'The PayPal Currency Code must be one of <currency-codes>.', 'AWPCP' );
		$message = str_replace( '<currency-codes>', implode( ', ', $supported_currencies ), $message );

		$this->add_validation_rule( $key, 'paypalcurrencycode', 'required', array( 'depends' => 'activatepaypal' ) );
		$this->add_validation_rule( $key, 'paypalcurrencycode', 'oneof', array( 'param' => $supported_currencies ), $message );
		$this->add_behavior( $key, 'paypalcurrencycode', 'enabledIf', 'activatepaypal' );

		// Section: Payment Settings - 2Checkout

		$key = $this->add_section($group, __('2Checkout Settings', 'AWPCP'), '2checkout', 30, array($this, 'section'));

		$this->add_setting( $key, 'activate2checkout', __( 'Activate 2Checkout', 'AWPCP' ), 'checkbox', 1, __( 'Activate 2Checkout?', 'AWPCP' ) );

		$this->add_setting( $key, '2checkout', __( '2Checkout account', 'AWPCP' ), 'textfield', '', __( 'Account for 2Checkout payments.', 'AWPCP' ) );

		$this->add_validation_rule( $key, '2checkout', 'required', array( 'depends' => 'activate2checkout' ) );
		$this->add_behavior( $key, '2checkout', 'enabledIf', 'activate2checkout' );

		$this->add_setting( $key, '2checkoutcurrencycode', __( '2Checkout Currency Code', 'AWPCP' ), 'textfield', 'USD', __( 'The currency in which you would like to receive your 2Checkout payments', 'AWPCP' ) );

		$this->add_validation_rule( $key, '2checkoutcurrencycode', 'required', array( 'depends' => 'activate2checkout' ) );
		$this->add_behavior( $key, '2checkoutcurrencycode', 'enabledIf', 'activate2checkout' );

		// Group: AdSense

		$group = $this->add_group( __( 'AdSense', 'AWPCP' ), 'adsense-settings', 60 );

		// Section: AdSense Settings

		$key = $this->add_section( $group, __( 'AdSense Settings', 'AWPCP' ), 'default', 10, array( $this, 'section' ) );

		$options = array(
			1 => __( 'Above Ad text.', 'AWPCP' ),
			2 => __( 'Under Ad text.', 'AWPCP' ),
			3 => __( 'Below Ad images.', 'AWPCP' ),
		);

		$this->add_setting( $key, 'useadsense', __( 'Activate AdSense', 'AWPCP'), 'checkbox', 1, '');
		$this->add_setting( $key, 'adsense', __( 'AdSense code', 'AWPCP' ), 'textarea', __( 'AdSense code', 'AWPCP' ), __( 'Your AdSense code (Best if 468x60 text or banner.)', 'AWPCP' ) );
		$this->add_setting( $key, 'adsenseposition', __( 'Show AdSense at position', 'AWPCP' ), 'radio', 2, '', array( 'options' => $options ) );

		// Group: Registration

		$group = $this->add_group( __( 'Registration', 'AWPCP' ), 'registration-settings', 80);

		// Group: Email

		$group = $this->add_group('Email', 'email-settings', 90);

		// Section: General Email Settings

		$key = $this->add_section($group, __('General Email Settings', 'AWPCP'), 'default', 20, array($this, 'section'));

		$this->add_setting( $key, 'admin-recipient-email', __( 'TO email address for outgoing emails', 'AWPCP' ), 'textfield', '', __( 'Emails are sent to your WordPress admin email. If you prefere to receive emails in a different address, please enter it here.', 'AWPCP' ) );
		$this->add_setting( $key, 'awpcpadminemail', __( 'FROM email address for outgoing emails', 'AWPCP' ), 'textfield', '', __( 'Emails go out using your WordPress admin email. If you prefer to use a different email enter it here.', 'AWPCP' ) );
		$this->add_setting( $key, 'usesenderemailinsteadofadmin', __( 'Use sender email for reply messages', 'AWPCP' ), 'checkbox', 0, __( 'Check this to use the name and email of the sender in the FROM field when someone replies to an ad. When unchecked the messages go out with the website name and WP admin email address in the from field. Some servers will not process outgoing emails that have an email address from gmail, yahoo, hotmail and other free email services in the FROM field. Some servers will also not process emails that have an email address that is different from the email address associated with your hosting account in the FROM field. If you are with such a webhost you need to leave this option unchecked and make sure your WordPress admin email address is tied to your hosting account.', 'AWPCP' ) );
		$this->add_setting( $key, 'include-ad-access-key', __( 'Include Ad access key in email messages', 'AWPCP' ), 'checkbox', 1, __( "Include Ad access key in email notifications. You may want to uncheck this option if you are using the Ad Management panel, but is not necessary.", 'AWPCP' ) );

		// Section: Ad Posted Message

		$key = $this->add_section($group, __('Ad Posted Message', 'AWPCP'), 'ad-posted-message', 10, array($this, 'section'));

		$this->add_setting( $key, 'listingaddedsubject', __( 'Subject for Ad posted notification email', 'AWPCP' ), 'textfield', __( 'Your Classified Ad listing has been submitted', 'AWPCP' ), __( 'Subject line for email sent out when someone posts an Ad', 'AWPCP' ) );
		$this->add_setting( $key, 'listingaddedbody', __( 'Body for Ad posted notification email', 'AWPCP' ), 'textarea', __( 'Thank you for submitting your Classified Ad. The details of your ad are shown below.', 'AWPCP' ), __( 'Message body text for email sent out when someone posts an Ad', 'AWPCP' ) );

		// Section: Reply to Ad Message

		$key = $this->add_section($group, __('Reply to Ad Message', 'AWPCP'), 'reply-to-ad-message', 10, array($this, 'section'));

		$this->add_setting( $key, 'contactformsubjectline', __( 'Subject for Reply to Ad email', 'AWPCP' ), 'textfield', __( 'Response to your AWPCP Demo Ad', 'AWPCP' ), __( 'Subject line for email sent out when someone replies to Ad', 'AWPCP' ) );
		$this->add_setting( $key, 'contactformbodymessage', __( 'Body for Reply to Ad email', 'AWPCP' ), 'textarea', __( 'Someone has responded to your AWPCP Demo Ad', 'AWPCP' ), __( 'Message body text for email sent out when someone replies to Ad', 'AWPCP' ) );
		$this->add_setting( $key, 'notify-admin-about-contact-message', __( 'Notify admin about contact message', 'AWPCP' ), 'checkbox', 1, __( 'An email will be sent to the administrator every time a visitor sends a message to one of the Ad posters through the Reply to Ad page.', 'AWPCP' ) );

		// Section: Request Ad Message

		$key = $this->add_section($group, __('Resend Access Key Message', 'AWPCP'), 'request-ad-message', 10, array($this, 'section'));

		$this->add_setting( $key, 'resendakeyformsubjectline', __( 'Subject for Request Ad Access Key email', 'AWPCP' ), 'textfield', __( "The Classified Ad's ad access key you requested", 'AWPCP' ), __( 'Subject line for email sent out when someone requests their ad access key resent', 'AWPCP' ) );
		$this->add_setting( $key, 'resendakeyformbodymessage', __( 'Body for Request Ad Access Key email', 'AWPCP' ), 'textarea', __( "You asked to have your Classified Ad's access key resent. Below are all the Ad access keys in the system that are tied to the email address you provided", 'AWPCP' ), __('Message body text for email sent out when someone requests their ad access key resent', 'AWPCP' ) );

		// Section: Incomplete Payment Message

		$key = $this->add_section($group, __('Incomplete Payment Message', 'AWPCP'), 'incomplete-payment-message', 10, array($this, 'section'));

		$this->add_setting( $key, 'paymentabortedsubjectline', __( 'Subject for Incomplete Payment email', 'AWPCP' ), 'textfield', __( 'There was a problem processing your payment', 'AWPCP' ), __( 'Subject line for email sent out when the payment processing does not complete', 'AWPCP' ) );
		$this->add_setting( $key, 'paymentabortedbodymessage', __( 'Body for Incomplete Payment email', 'AWPCP' ), 'textarea', __( 'There was a problem encountered during your attempt to submit payment. If funds were removed from the account you tried to use to make a payment please contact the website admin or the payment website customer service for assistance.', 'AWPCP' ), __( 'Message body text for email sent out when the payment processing does not complete', 'AWPCP' ) );

		// Section: Renew Ad Message

		$key = $this->add_section($group, __('Renew Ad Message', 'AWPCP'), 'renew-ad-message', 10, array($this, 'section'));

		$this->add_setting( $key, 'renew-ad-email-subject', __( 'Subject for Renew Ad email', 'AWPCP' ), 'textfield', __( 'Your classifieds listing Ad will expire in %d days.', 'AWPCP' ), __( 'Subject line for email sent out when an Ad is about to expire.', 'AWPCP' ) );
		$this->add_setting( $key, 'renew-ad-email-body', __( 'Body for Renew Ad email', 'AWPCP' ), 'textarea', __( 'This is an automated notification that your Classified Ad will expire in %d days.', 'AWPCP' ), __( 'Message body text for email sent out when an Ad is about to expire. Use %d as placeholder for the number of days before the Ad expires.', 'AWPCP' ) );

		// Section: Ad Renewed Message

		$key = $this->add_section($group, __('Ad Renewed Message', 'AWPCP'), 'ad-renewed-message', 10, array($this, 'section'));

		$this->add_setting( $key, 'ad-renewed-email-subject', __( 'Subject for Ad Renewed email', 'AWPCP' ), 'textfield', __( 'Your classifieds listing "%s" has been successfully renewed.', 'AWPCP' ), __( 'Subject line for email sent out when an Ad is successfully renewed.', 'AWPCP' ) );
		$this->add_setting( $key, 'ad-renewed-email-body', __( 'Body for Renew Ad email', 'AWPCP' ), 'textarea', __( 'Your classifieds listing Ad has been successfully renewed. More information below:', 'AWPCP' ), __( 'Message body text for email sent out when an Ad is successfully renewed. ', 'AWPCP' ) );

		// Section: Ad Expired Message

		$key = $this->add_section($group, __('Ad Expired Message', 'AWPCP'), 'ad-expired-message', 10, array($this, 'section'));

		$this->add_setting( $key, 'adexpiredsubjectline', __( 'Subject for Ad Expired email', 'AWPCP' ), 'textfield', __( 'Your classifieds listing at %s has expired', 'AWPCP' ), __( 'Subject line for email sent out when an ad has auto-expired', 'AWPCP' ) );
		$this->add_setting( $key, 'adexpiredbodymessage', __( 'Body for Ad Expired email', 'AWPCP' ), 'textarea', __( 'This is an automated notification that your Classified Ad has expired.', 'AWPCP' ), __( 'Message body text for email sent out when an ad has auto-expired', 'AWPCP' ) );

		// Section: Advanced Email Configuration

		$key = $this->add_section( $group, __( 'Advanced Email Configuration', 'AWPCP' ), 'advanced', 30, array( $this, 'section' ) );

		$this->add_setting( $key, 'usesmtp', __( 'Enable external SMTP server', 'AWPCP' ), 'checkbox', 0, __( 'Enabled external SMTP server (if emails not processing normally).', 'AWPCP' ) );
		$this->add_setting( $key, 'smtphost', __( 'SMTP host', 'AWPCP' ), 'textfield', 'mail.example.com', __( 'SMTP host (if emails not processing normally).', 'AWPCP' ) );
		$this->add_setting( $key, 'smtpport', __( 'SMTP port', 'AWPCP' ), 'textfield', '25', __( 'SMTP port (if emails not processing normally).', 'AWPCP' ) );
		$this->add_setting( $key, 'smtpusername', __( 'SMTP username', 'AWPCP' ), 'textfield', 'smtp_username', __( 'SMTP username (if emails not processing normally).', 'AWPCP' ) );
		$this->add_setting( $key, 'smtppassword', __( 'SMTP password', 'AWPCP' ), 'password', '', __( 'SMTP password (if emails not processing normally).', 'AWPCP' ) );

		// Group: Facebook

		$group = $this->add_group('Facebook', 'facebook-settings', 100);

		$key = $this->add_section( $group, __( 'General Settings', 'AWPCP' ), 'general', 10, array( $this, 'section' ) );

		$this->add_setting( $key, 'sends-listings-to-facebook-automatically', __( 'Send Ads to Facebook Automatically', 'AWPCP' ), 'checkbox', 1, __( 'If checked, Ads will be posted to Facebook shortly after they are posted, enabled or edited, whichever occurs first. Ads will be posted only once. Please note that disabled Ads cannot be posted to Facebook.', 'AWPCP' ) );


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
		uasort( $this->groups, create_function( '$a, $b', 'return $a->priority - $b->priority;') );
		foreach ($this->groups as $group) {
			uasort( $group->sections, create_function( '$a, $b', 'return $a->priority - $b->priority;') );
			foreach ($group->sections as $section) {
				add_settings_section($section->slug, $section->name, $section->callback, $group->slug);
				foreach ($section->settings as $setting) {
					$callback = array($this, $setting->type);
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

	public function add_section($group, $name, $slug, $priority, $callback) {
		$section = new stdClass();
		$section->name = $name;
		$section->slug = $slug;
		$section->priority = $priority;
		$section->callback = $callback;
		$section->settings = array();

		$this->groups[$group]->sections[$slug] = $section;

		return "$group:$slug";
	}

	public function add_setting($key, $name, $label, $type, $default, $helptext, $args=array()) {
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

        $setting_label = str_replace( '<module-name>', $module_name, __( '<module-name> License Key', 'AWPCP' ) );
        $this->add_setting( $section, "$module_slug-license", $setting_label, 'license', '', '', compact( 'module_name', 'module_slug' ) );
	}

	private function enable_licenses_settings_section() {
		$group_slug = 'licenses-settings';
		$section_slug = 'premium-modules';

		if ( ! isset( $this->groups[ $group_slug ] ) ) {
			$this->add_group( __( 'Licenses', 'AWPCP' ), $group_slug, 100000 );
			$this->add_section( $group_slug, 'Premium Modules', $section_slug, 10, array( $this, 'section' ) );
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
			$value = strip_slashes_recursive($value);
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
		// Check Akismet if they enabled/configured it:
		$setting = 'useakismet';
		if (isset($options[$setting])) {
			$wpcom_api_key = get_option('wordpress_api_key');
			if ($options[$setting] == 1 && !function_exists('akismet_init')) {
				awpcp_flash( __( 'Akismet SPAM control cannot be enabled because Akismet plugin is not installed or activated.', 'AWPCP' ), 'error' );
				$options[$setting] = 0;
			} else if ($options[$setting] == 1 && empty($wpcom_api_key)) {
				awpcp_flash( __( 'Akismet SPAM control cannot be enabled because Akismet is not properly configured.', 'AWPCP' ), 'error' );
				$options[$setting] = 0;
			}
		}

		// Verify reCAPTCHA is properly configured
		if ( isset( $options['captcha-enabled'] ) && $options['captcha-provider'] === 'recaptcha' ) {
			if ( empty( $options[ 'recaptcha-public-key' ] ) || empty( $options[ 'recaptcha-private-key' ] ) ) {
				$options['captcha-provider'] = 'math';
			}

			if ( empty( $options[ 'recaptcha-public-key' ] ) && empty( $options[ 'recaptcha-private-key' ] )  ) {
				awpcp_flash( __( "reCAPTCHA can't be used because the public key and private key settings are required for reCAPTCHA to work properly.", 'AWPCP' ), 'error' );
			} else if ( empty( $options[ 'recaptcha-public-key' ] ) ) {
				awpcp_flash( __( "reCAPTCHA can't be used because the public key setting is required for reCAPTCHA to work properly.", 'AWPCP' ), 'error' );
			} else if ( empty( $options[ 'recaptcha-private-key' ] ) ){
				awpcp_flash( __( "reCAPTCHA can't be used because the private key setting is required for reCAPTCHA to work properly.", 'AWPCP' ), 'error' );
			}
		}

		// Enabling User Ad Management Panel will automatically enable
		// require Registration, if it isn’t enabled. Disabling this feature
		// will not disable Require Registration.
		$setting = 'enable-user-panel';
		if (isset($options[$setting]) && $options[$setting] == 1 && !get_awpcp_option('requireuserregistration')) {
			awpcp_flash(__('Require Registration setting was enabled automatically because you activated the User Ad Management panel.', 'AWPCP'));
			$options['requireuserregistration'] = 1;
		}

		return $options;
	}

	/**
	 * Registration Settings checks
	 */
	public function validate_registration_settings($options, $group) {
		// if Require Registration is disabled, User Ad Management Panel should be
		// disabled as well.
		$setting = 'requireuserregistration';
		if (isset($options[$setting]) && $options[$setting] == 0 && get_awpcp_option('enable-user-panel')) {
			awpcp_flash(__('User Ad Management panel was automatically deactivated because you disabled Require Registration setting.', 'AWPCP'));
			$options['enable-user-panel'] = 0;
		}

		if (isset($options[$setting]) && $options[$setting] == 0 && get_awpcp_option('enable-credit-system')) {
			awpcp_flash(__('Credit System was automatically disabled because you disabled Require Registration setting.', 'AWPCP'));
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
			$message = __( 'There is a problem with the PayPal Currency Code you have entered. It does not match any of the codes in our list of curencies supported by PayPal.', 'AWPCP' );
			$message.= '<br/><br/><strong>' . __( 'The available currency codes are', 'AWPCP' ) . '</strong>:<br/>';
			$message.= join(' | ', $currency_codes);
			awpcp_flash($message);

			$options[$setting] = 'USD';
		}

		$setting = 'enable-credit-system';
		if (isset($options[$setting]) && $options[$setting] == 1 && !get_awpcp_option('requireuserregistration')) {
			awpcp_flash(__('Require Registration setting was enabled automatically because you activated the Credit System.', 'AWPCP'));
			$options['requireuserregistration'] = 1;
		}

		if (isset($options[$setting]) && $options[$setting] == 1 && !get_awpcp_option('freepay')) {
			awpcp_flash(__('Charge Listing Fee setting was enabled automatically because you activated the Credit System.', 'AWPCP'));
			$options['freepay'] = 1;
		}

		$setting = 'freepay';
		if (isset($options[$setting]) && $options[$setting] == 0 && get_awpcp_option('enable-credit-system')) {
			awpcp_flash(__('Credit System was disabled automatically because you disabled Charge Listing Fee.', 'AWPCP'));
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


	/* Auxiliar methods to render settings forms */

	public function textfield($args, $type='text') {
		$setting = $args['setting'];

		$value = esc_html(stripslashes($this->get_option($setting->name)));

		$html = '<input id="'. $setting->name . '" class="regular-text" ';
		$html.= 'value="' . $value . '" type="' . $type . '" ';
		$html.= 'name="awpcp-options[' . $setting->name . ']" ';

		if ( ! empty( $setting->args['behavior'] ) ) {
			$html.= 'awpcp-setting="' . esc_attr( json_encode( $setting->args['behavior'] ) ) . '" />';
		} else {
			$html.= '/>';
		}

		$html.= strlen($setting->helptext) > 45 ? '<br/>' : '';
		$html.= '<span class="description">' . $setting->helptext . '</span>';

		echo $html;
	}

	public function password($args) {
		return $this->textfield($args, 'password');
	}

	public function checkbox($args) {
		$setting = $args['setting'];

		$value = intval($this->get_option($setting->name));

		$html = '<input type="hidden" value="0" name="awpcp-options['. $setting->name .']" />';
		$html.= '<input id="'. $setting->name . '" value="1" ';
		$html.= 'type="checkbox" name="awpcp-options[' . $setting->name . ']" ';
		$html.= $value ? 'checked="checked" />' : ' />';
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
		$options = $args['options'];

		$current = esc_html(stripslashes($this->get_option($setting->name)));

		$html = '';
		foreach ($options as $value => $label) {
			$id = "{$setting->name}-$value";
			$label = ' <label for="' . $id . '">' . $label . '</label>';

			$html.= '<input id="' . $id . '"type="radio" value="' . $value . '" ';
			$html.= 'name="awpcp-options['. $setting->name .']" ';
			if ($value == $current) {
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
				echo '<input class="button-secondary" type="submit" name="awpcp-deactivate-' . $module_slug . '-license" value="' . __( 'Deactivate License', 'AWPCP' ) . '"/>';
				echo '<br>Status: <span class="awpcp-license-status awpcp-license-valid">' . __( 'active', 'AWPCP' ) . '</span>.';
			} else if ( $this->licenses_manager->is_license_inactive( $module_name, $module_slug ) ) {
				echo '<input class="button-secondary" type="submit" name="awpcp-activate-' . $module_slug . '-license" value="' . __( 'Activate License', 'AWPCP' ) . '"/>';
				echo '<br>Status: <span class="awpcp-license-status awpcp-license-inactive">' . __( 'inactive', 'AWPCP' ) . '</span>.';
			} else if ( $this->licenses_manager->is_license_expired( $module_name, $module_slug ) ) {
				echo '<input class="button-secondary" type="submit" name="awpcp-check-' . $module_slug . '-license" value="' . __( 'Check License', 'AWPCP' ) . '"/>';
				echo '<br>Status: <span class="awpcp-license-status awpcp-license-expired">' . __( 'expired', 'AWPCP' ) . '</span>.';
			} else {
				echo '<br>Status: <span class="awpcp-license-status awpcp-license-invalid">' . __( 'invalid', 'AWPCP' ) . '</span>. Please contact customer support.';
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

	public function section_date_time_format($args) {
		$link = '<a href="http://codex.wordpress.org/Formatting_Date_and_Time">%s</a>.';
		echo sprintf( $link, __( 'Documentation on date and time formatting', 'AWPCP' ) );
	}
}
