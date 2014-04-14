<?php

class AWPCP_Settings_API {

	private static $instance = null;

	public $option = 'awpcp-options';
	public $options = array();
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
		$options = get_option($this->option);
		$this->options = is_array($options) ? $options : array();
	}

	public function register_settings() {

		register_setting($this->option, $this->option, array($this, 'validate'));

		// Group: Private

		$group = $this->add_group( __( 'Private Settings', 'AWPCP' ), 'private-settings', 0 );


		// Group: General

		$group = $this->add_group( __( 'General', 'AWPCP' ), 'general-settings', 5 );

		// Section: General - Ad Management Panel

		$key = $this->add_section( $group, __( 'User Ad Management Panel', 'AWPCP' ), 'user-panel', 5, array( $this, 'section' ) );

		$help_text = __( 'You must have registered users to use this setting. Turning it on will automatically enable "Require Registration" for AWPCP. Make sure you site allows users to register under <wp-settings-link>Settings->General</a>.' );
		$help_text = str_replace( '<wp-settings-link>', sprintf( '<a href="%s">', admin_url( 'options-general.php' ) ), $help_text );
		$this->add_setting( $key, 'enable-user-panel', __( 'Enable User Ad Management Panel', 'AWPCP' ), 'checkbox', 0, $help_text );

		// Section: General - Default

		$key = $this->add_section( $group, __( 'General Settings', 'AWPCP' ), 'default', 9, array( $this, 'section' ) );

		$this->add_setting( $key, 'activatelanguages', __( 'Turn on transalation file (POT)', 'AWPCP' ), 'checkbox', 0, __( "Enable translations. WordPress will look for an AWPCP-&lt;language&gt;.mo file in AWPCP's languages/ directory of the main plugin and premium modules. Example filenames are: AWPCP-en_EN.mo, AWPCP-es_ES.mo. You can generate .mo files using POEdit and the AWPCP.pot or AWPCP-en_EN.po files included with the plugin.", 'AWPCP' ) );
		$this->add_setting( $key, 'main_page_display', __( 'Show Ad listings on main page', 'AWPCP' ), 'checkbox', 0, __( 'If unchecked only categories will be displayed', 'AWPCP' ) );
		$this->add_setting( $key, 'view-categories-columns', __( 'Category columns in View Categories page', 'AWPCP' ), 'select', 2, '', array('options' => array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5)));
		$this->add_setting( $key, 'collapse-categories-columns', __( 'Collapse Categories', 'AWPCP' ), 'checkbox', 0, __( 'If checked the list of sub-categories will be collapsed by default. Users would have to click the down arrow icon to expand the list and see the sub-categories.', 'AWPCP' ) );
		$this->add_setting( $key, 'uiwelcome', __( 'Welcome message in Classified page', 'AWPCP' ), 'textarea', __( 'Looking for a job? Trying to find a date? Looking for an apartment? Browse our classifieds. Have a job to advertise? An apartment to rent? Post a Classified Ad.', 'AWPCP' ), __( 'The welcome text for your classified page on the user side', 'AWPCP' ) );

        $options = array('admin' => __( 'Administrator', 'AWPCP' ), 'admin,editor' => __( 'Administrator & Editor', 'AWPCP' ) );
        $this->add_setting( $key, 'awpcpadminaccesslevel', __( 'Who can access AWPCP Admin Dashboard', 'AWPCP' ), 'radio', 'admin', __( 'Role of WordPress users who can have admin access to Classifieds.', 'AWPCP' ), array( 'options' => $options ) );
		$this->add_setting( $key, 'awpcppagefilterswitch', __( 'Enable page filter', 'AWPCP' ), 'checkbox', 1, __( 'Uncheck this if you need to turn off the AWPCP page filter that prevents AWPCP classifieds children pages from showing up in your wp pages menu (You might need to do this if for example the AWPCP page filter is messing up your page menu. It means you will have to manually exclude the AWPCP children pages from showing in your page list. Some of the pages really should not be visible to your users by default).', 'AWPCP') );
		$this->add_setting( $key, 'showlatestawpcpnews', __( 'Show latest AWPCP news', 'AWPCP' ), 'checkbox', 1, __( 'Show latest news from www.awpcp.com RSS feed.', 'AWPCP' ) );

		// Section: General - Date & Time Format

		$label = _x( 'Date & Time Format', 'settings', 'AWPCP' );

		$key = $this->add_section($group, $label, 'date-time-format', 10, array($this, 'section_date_time_format'));

		$datetime = current_time('timestamp');
		$options = array(
			'american' => sprintf( '<strong>%s</strong>: %s', __( 'American', 'AWPCP' ), awpcp_datetime( 'm/d/Y h:i:s', $datetime ) ),
			'european' => sprintf( '<strong>%s</strong>: %s', __( 'European', 'AWPCP' ), awpcp_datetime( 'd/m/Y H:i:s', $datetime ) ),
			'custom' => __( 'Your own.', 'AWPCP' ),
 		);

		$this->add_setting( $key, 'x-date-time-format', __( 'Date Time Format', 'AWPCP' ), 'radio', 'american', '', array( 'options' => $options ) );
		$this->add_setting( $key, 'date-format', _x( 'Date Format', 'settings', 'AWPCP' ), 'textfield', 'm/d/Y', '' );
		$this->add_setting( $key, 'time-format', _x( 'Time Format', 'settings', 'AWPCP' ), 'textfield', 'h:i:s', '' );
		$example = sprintf( '<strong>%s</strong>: <span example>%s</span>', _x( 'Example output', 'settings', 'AWPCP' ), awpcp_datetime( 'awpcp' ) );
		$description = _x( 'Full date/time output with any strings you wish to add. <date> and <time> are placeholders for date and time strings using the formats specified in the Date Format and Time Format settings above.', 'settings', 'AWPCP' );
		$this->add_setting( $key, 'date-time-format', _x( 'Full Display String', 'settings', 'AWPCP' ), 'textfield', '<date> at <time>', esc_html( $description ) . '<br/>' . $example );

		// Section: General - Currency Format

		$key = $this->add_section($group, __('Currency Format', 'AWPCP'), 'currency-format', 10, array($this, 'section'));

		$this->add_setting($key, 'thousands-separator', __('Thousands separator', 'AWPCP'), 'textfield', _x(',', 'This translation is deprecated. Please go to the Settings section to change the thousands separator.', 'AWPCP'), '');
		$this->add_setting($key, 'decimal-separator', __('Separator for the decimal point', 'AWPCP'), 'textfield', _x('.', 'This translation is deprecated. Please go to the Settings section to change the decimal separator.', 'AWPCP'), '');
		$this->add_setting($key, 'show-decimals', __('Show decimals in price', 'AWPCP'), 'checkbox', 1, _x('Uncheck to show prices without decimals. The value will be rounded.', 'settings', 'AWPCP'));

		// Section: General - Terms of Service

		$key = $this->add_section( $group, __( 'Terms of Service', 'AWPCP' ), 'terms-of-service', 11, array( $this, 'section' ) );

		$this->add_setting( $key, 'requiredtos', __( 'Display and require Terms of Service', 'AWPCP' ), 'checkbox', 1, __( 'Display and require Terms of Service', 'AWPCP' ) );
		$this->add_setting( $key, 'tos', __( 'Terms of Service', 'AWPCP' ), 'textarea', __( 'Terms of service go here...', 'AWPCP' ), __( 'Terms of Service for posting Ads. Put in text or an URL starting with http. If you use an URL, the text box will be replaced by a link to the appropriate Terms of Service page', 'AWPCP' ) );

		// Section: General - Anti-SPAM

		$key = $this->add_section($group, __( 'Anti-SPAM', 'AWPCP' ), 'anti-spam', 10, array($this, 'section'));

		$options = array(
			'recaptcha' => __( 'reCAPTCHA (recommended)', 'AWPCP' ),
			'math' => __( 'Math', 'AWPCP' ),
		);

		$this->add_setting( $key, 'useakismet', __( 'Use Akismet', 'AWPCP' ), 'checkbox', 1, __( 'Use Akismet for Posting Ads/Contact Responses (strong anti-spam).', 'AWPCP' ) );
		$this->add_setting( $key, 'captcha-enabled', __( 'Enable CAPTCHA', 'AWPCP' ), 'checkbox', $this->get_option( 'contactformcheckhuman', 1 ), __( 'A CAPTCHA is a program to ensure only humans are posting Ads to your website. Using a CAPTCHA will reduce the SPAM and prevent bots from posting on your website. If checked, an additional form field will be added to the Place Ad and Reply to Ad forms.', 'AWPCP' ) );
		$this->add_setting( $key, 'captcha-provider', __( 'Type of CAPTCHA', 'AWPCP' ), 'select', 'math', __( 'reCAPTCHA: Uses distorted images that only humans should be able to read (recommended).', 'AWPCP' ) . '<br/>' . __( 'Math: Asks user to solve a simple arithmetic operation.', 'AWPCP' ), array( 'options' => $options ) );

		$this->add_setting( $key, 'math-captcha-max-number', __( 'Max number used in Math CAPTCHA', 'AWPCP' ), 'textfield', $this->get_option( 'contactformcheckhumanhighnumval', 10 ), __( 'Highest number used in aithmetic operation.', 'AWPCP') );

        $link = sprintf( '<a href="%1$s">%1$s</a>', 'https://www.google.com/recaptcha/admin/create' );
		$help_text = sprintf( __( 'You can get an API key from %s.', 'AWPCP' ), $link );
		$this->add_setting( $key, 'recaptcha-public-key', __( 'reCAPTCHA Public Key', 'AWPCP' ), 'textfield', '', $help_text );
		$this->add_setting( $key, 'recaptcha-private-key', __( 'reCAPTCHA Private Key', 'AWPCP' ), 'textfield', '',$help_text );

		// Section: General - Window Title

		$key = $this->add_section($group, 'Window Title', 'window-title', 10, array($this, 'section'));

		$this->add_setting( $key, 'awpcptitleseparator', __( 'Window title separator', 'AWPCP' ), 'textfield', '-', __( 'The character to use to separate ad details used in browser page title. Example: | / -', 'AWPCP' ) );
		$this->add_setting( $key, 'showcityinpagetitle', __( 'Show city in window title', 'AWPCP' ), 'checkbox', 1, __( 'Show city in browser page title when viewing individual Ad', 'AWPCP' ) );
		$this->add_setting( $key, 'showstateinpagetitle', __( 'Show state in window title', 'AWPCP' ), 'checkbox', 1, __('Show state in browser page title when viewing individual Ad', 'AWPCP' ) );
		$this->add_setting( $key, 'showcountryinpagetitle', __( 'Show country in window title', 'AWPCP' ), 'checkbox', 1, __( 'Show country in browser page title when viewing individual Ad', 'AWPCP' ) );
		$this->add_setting( $key, 'showcountyvillageinpagetitle', __( 'Show county/village/other in window title', 'AWPCP' ), 'checkbox', 1, __( 'Show county/village/other setting in browser page title when viewing individual Ad', 'AWPCP' ) );
		$this->add_setting( $key, 'showcategoryinpagetitle', __( 'Show category in title', 'AWPCP' ), 'checkbox', 1, __( 'Show category in browser page title when viewing individual Ad', 'AWPCP' ) );

		// Section: SEO Settings

		$key = $this->add_section($group, __('SEO Settings', 'AWPCP'), 'seo-settings', 10, array($this, 'section'));

		$this->add_setting( $key, 'seofriendlyurls', __( 'Turn on Search Engine Friendly URLs', 'AWPCP' ), 'checkbox', 0, __( 'Turn on Search Engine Friendly URLs? (SEO Mode)', 'AWPCP' ) );


		// Group: Classified Pages

		$group = $this->add_group(__('Classifieds Pages', 'AWPCP'), 'pages-settings', 20);

		// Section: Classifieds Pages - Default

		$key = $this->add_section($group, __('Classifieds Pages', 'AWPCP'), 'default', 10, array($this, 'section'));

		$this->add_setting( $key, 'main-page-name', __( 'AWPCP Main page', 'AWPCP' ), 'textfield', 'AWPCP', __( 'Name for Classifieds page.', 'AWPCP' ) );
		$this->add_setting( $key, 'show-ads-page-name', __( 'Show Ad page', 'AWPCP' ), 'textfield', 'Show Ad', __( 'Name for Show Ads page.', 'AWPCP' ) );
		$this->add_setting( $key, 'place-ad-page-name', __( 'Place Ad page', 'AWPCP' ), 'textfield', 'Place Ad', __( 'Name for Place Ads page.' ) );
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

		// Section: Ad/Listings - Notifications

		$key = $this->add_section($group, __('User Notifications', 'AWPCP'), 'user-notifications', 10, array($this, 'section'));

		$this->add_setting( $key, 'send-user-ad-posted-notification', __( 'Ad Posted', 'AWPCP' ), 'checkbox', 1, __( 'An email will be sent when an Ad is posted.', 'AWPCP' ) );
		$this->add_setting( $key, 'send-ad-enabled-email', __( 'Ad Enabled', 'AWPCP' ), 'checkbox', 1, __( 'Notify Ad owner when the Ad is enabled.', 'AWPCP' ) );
		$this->add_setting( $key, 'sent-ad-renew-email', __( 'Ad Renew', 'AWPCP' ), 'checkbox', 1, __( 'An email will be sent to remind the user to Renew the Ad when the Ad is about to expire.', 'AWPCP' ) );
		$this->add_setting( $key, 'ad-renew-email-threshold', __( 'Ad Renew email threshold', 'AWPCP' ), 'textfield', 5, __( 'The email is sent the specified number of days before the Ad expires.', 'AWPCP' ) );
		$this->add_setting( $key, 'notifyofadexpiring', __( 'Ad Expired', 'AWPCP' ), 'checkbox', 1, __( 'An email will be sent when the Ad expires.', 'AWPCP' ) );

		$key = $this->add_section($group, __('Admin Notifications', 'AWPCP'), 'admin-notifications', 10, array($this, 'section'));

		$this->add_setting( $key, 'notifyofadposted', __( 'Ad Posted', 'AWPCP' ), 'checkbox', 1, __( 'An email will be sent when an Ad is posted.', 'AWPCP' ) );
		$this->add_setting( $key, 'notifyofadexpired', __( 'Ad Expired', 'AWPCP' ), 'checkbox', 1, __( 'An email will be sent when the Ad expires.', 'AWPCP' ) );

		// Section: Ad/Listings - Moderation

		$key = $this->add_section($group, __('Moderation', 'AWPCP'), 'moderation', 10, array($this, 'section'));

		$this->add_setting( $key, 'onlyadmincanplaceads', __( 'Only admin can post Ads', 'AWPCP' ), 'checkbox', 0, __( 'If checked only administrator users will be allowed to post Ads.', 'AWPCP' ) );
		$this->add_setting( $key, 'adapprove', __( 'Disable Ad until admin approves', 'AWPCP' ), 'checkbox', 0, __( 'New Ads will be in a disabled status, not visible to visitors, until the administrator approves them.', 'AWPCP' ) );
		$this->add_setting( $key, 'disablependingads', __( 'Enable paid ads that are pending payment.', 'AWPCP' ), 'checkbox', 1, __( 'Enable paid ads that are pending payment.', 'AWPCP' ) );
		$this->add_setting( $key, 'enable-email-verification', __( 'Enable email verification for new Ads', 'AWPCP' ), 'checkbox', 0, __( 'If checked, all new Ads will remain disabled until the user clicks a verification link sent to the email address used to post the Ad.', 'AWPCP' ) );
		$this->add_setting( $key, 'email-verification-first-threshold', __( 'Number of days before the verification email is sent again', 'AWPCP' ), 'textfield', 5, '' );
		$this->add_setting( $key, 'email-verification-second-threshold', __( 'Number of days before Ads that remain in a unverified status will be deleted', 'AWPCP' ), 'textfield', 30, '' );
		$this->add_setting( $key, 'notice_awaiting_approval_ad', __( 'Awaiting approval message', 'AWPCP' ), 'textarea', __( 'All ads must first be approved by the administrator before they are activated in the system. As soon as an admin has approved your ad it will become visible in the system. Thank you for your business.', 'AWPCP' ), __( 'Text for message to notify user that ad is awaiting approval', 'AWPCP') );

		$this->add_setting( $key, 'ad-poster-email-address-whitelist', __( 'Allowed domains in Ad poster email', 'AWPCP' ), 'textarea', '', __( 'Only email addresses with a domain in the list above will be allowed. *.foo.com will match a.foo.com, b.foo.com, etc. but foo.com will match foo.com only. Please type a domain per line.', 'AWPCP' ) );

		$this->add_setting( $key, 'noadsinparentcat', __( 'Prevent ads from being posted to top level categories?', 'AWPCP' ), 'checkbox', 0, '' );
		$this->add_setting( $key, 'use-multiple-category-dropdowns', __( 'Use multiple dropdowns to choose categories', 'AWPCP' ), 'checkbox', 0, __( 'If checked, a dropdown with top level categories will be shown. When the user chooses a category, a new dropdown will apper showing the sub-categories of the selected category, if any. Useful if your website supports a high number of categories.', 'AWPCP' ) );

		$this->add_setting( $key, 'addurationfreemode', __( 'Free Ads expiration threshold', 'AWPCP' ), 'textfield', 0, __( 'Expire free ads after how many days? (0 for no expiration).', 'AWPCP' ) );
		$this->add_setting( $key, 'autoexpiredisabledelete', __( 'Disable expired ads instead of deleting them?', 'AWPCP' ), 'checkbox', 0, __( 'Check to disable.', 'AWPCP' ) );

		$key = $this->add_section( $group, __( 'Regions Settings', 'AWPCP' ), 'regions-settings', 10, array( $this, 'section' ) );

		$this->add_setting( $key, 'allow-regions-modification', __( 'Allow Regions modification', 'AWPCP' ), 'checkbox', 1, __( 'If enabled, users will be allowed to change the region information associated with their Ads.', 'AWPCP' ) );
		$this->add_setting( $key, 'allow-user-to-search-in-multiple-regions', __( 'Allow users to search Ads in multiple regions', 'AWPCP' ), 'checkbox', 0, __( 'If enabled, users will be allowed to search Ads in multiple regions.', 'AWPCP' ) );

		// Section: Ad/Listings - Layout and Presentation

		$key = $this->add_section($group, __('Layout and Presentation', 'AWPCP'), 'layout', 10, array($this, 'section'));

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
							</div>', __( 'Modify as needed to control layout of single ad view page. Maintain code formatted as \$somecodetitle. Changing the code keys will prevent the elements they represent from displaying.', 'AWPCP' ) );

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
		$this->add_setting( $key, 'buildsearchdropdownlists', __( 'Limits search to available locations.', 'AWPCP' ), 'checkbox', 0, __( 'The search form can attempt to build drop down country, state, city and county lists if data is available in the system. Note that with the regions module installed the value for this option is overridden.', 'AWPCP' ) );
		$this->add_setting( $key, 'showadcount', __( 'Show Ad count in categories', 'AWPCP' ), 'checkbox', 1, __( 'Show how many ads a category contains.', 'AWPCP' ) );
		$this->add_setting( $key, 'hide-empty-categories', __( 'Hide empty categories?', 'AWPCP' ), 'checkbox', 0, __( "If checked, categories with 0 listings in it won't be shown.", 'AWPCP' ) );
		$this->add_setting( $key, 'displayadviews', __( 'Show Ad views', 'AWPCP' ), 'checkbox', 1, __( 'Show Ad views' ) );
		$this->add_setting( $key, 'hyperlinkurlsinadtext', __( 'Make URLs in ad text clickable', 'AWPCP' ), 'checkbox', 0, '' );
		$this->add_setting( $key, 'visitwebsitelinknofollow', __( 'Add no follow to links in Ads', 'AWPCP' ), 'checkbox', 1, '' );

		// Section: Ad/Listings - Menu Items

		$key = $this->add_section($group, __('Menu Items', 'AWPCP'), 'menu-items', 20, array($this, 'section'));

		$this->add_setting( $key, 'show-menu-item-place-ad', __( 'Show Place Ad menu item', 'AWPCP' ), 'checkbox', 1, '' );
		$this->add_setting( $key, 'show-menu-item-edit-ad', __( 'Show Edit Ad menu item', 'AWPCP' ), 'checkbox', 1, '' );
		$this->add_setting( $key, 'show-menu-item-browse-ads', __( 'Show Browse Ads menu item', 'AWPCP' ), 'checkbox', 1, '' );
		$this->add_setting( $key, 'show-menu-item-search-ads', __( 'Show Search Ads menu item', 'AWPCP' ), 'checkbox', 1, '' );

		// Group: Payment Settings

		$group = $this->add_group( __( 'Payment', 'AWPCP') , 'payment-settings', 40 );

		// Section: Payment Settings - Default

		$key = $this->add_section($group, __('Payment Settings', 'AWPCP'), 'default', 10, array($this, 'section'));

		$order_options = array(
			1 => __( 'Name', 'AWPCP' ),
			2 => __( 'Price', 'AWPCP' ),
			3 => __( 'Images Allowed', 'AWPCP' ),
			5 => __( 'Duration', 'AWPCP' ),
		);

		$direction_options = array(
			'ASC' => __( 'Ascending', 'AWPCP' ),
			'DESC' => __( 'Descending', 'AWPCP' ),
		);

		$this->add_setting( $key, 'freepay', __( 'Charge Listing Fee?', 'AWPCP' ), 'checkbox', 0, __( 'Charge Listing Fee? (Pay Mode)', 'AWPCP' ) );
		$this->add_setting( $key, 'fee-order', __( 'Fee Plan sort order', 'AWPCP' ), 'select', 1, __( 'The order used to sort Fees in the payment screens.', 'AWPCP' ), array( 'options' => $order_options ) );
		$this->add_setting( $key, 'fee-order-direction', __( 'Fee Plan sort direction', 'AWPCP' ), 'select', 'ASC', __( 'The direction used to sort Fees in the payment screens.', 'AWPCP' ), array( 'options' => $direction_options ) );
		$this->add_setting($key, 'pay-before-place-ad', _x('Pay before entering Ad details', 'settings', 'AWPCP'), 'checkbox', 1, _x('Check to ask for payment before entering Ad details. Uncheck if you want users to pay for Ads at the end of the process, after images have been uploaded.', 'settings', 'AWPCP'));
		$this->add_setting( $key, 'displaycurrencycode', __( 'Currency used in payment pages', 'AWPCP' ), 'textfield', 'USD', __( 'The display currency for your payment pages', 'AWPCP' ) );
		$this->add_setting( $key, 'paylivetestmode', __( 'Put payment gateways in test mode?', 'AWPCP' ), 'checkbox', 0, '');
		$this->add_setting( $key, 'force-secure-urls', __( 'Force secure URLs on payment pages', 'AWPCP' ), 'checkbox', 0, __( 'If checked all classifieds pages that involve payments will be accessed through a secure (HTTPS) URL.', 'AWPCP' ) );

		// Section: Payment Settings - PayPal

		$key = $this->add_section($group, __('PayPal Settings', 'AWPCP'), 'paypal', 20, array($this, 'section'));
		$this->add_setting($key, 'activatepaypal', __( 'Activate PayPal?', 'AWPCP' ), 'checkbox', 1, __( 'Activate PayPal?', 'AWPCP' ) );
		$this->add_setting($key, 'paypalemail', __( 'PayPal receiver email', 'AWPCP' ), 'textfield', 'xxx@xxxxxx.xxx', __( 'Email address for PayPal payments (if running in pay mode and if PayPal is activated).', 'AWPCP' ) );
		$this->add_setting($key, 'paypalcurrencycode', __( 'PayPal currency code', 'AWPCP' ), 'textfield', 'USD', __( 'The currency in which you would like to receive your PayPal payments', 'AWPCP' ) );
		// $this->add_setting($key, 'paypalpaymentsrecurring', 'Use PayPal recurring payments?', 'checkbox', 0, Use recurring payments PayPal (this feature is not fully automated or fully integrated. For more reliable results do not use recurring).');

		// Section: Payment Settings - 2Checkout

		$key = $this->add_section($group, __('2Checkout Settings', 'AWPCP'), '2checkout', 30, array($this, 'section'));

		$this->add_setting( $key, 'activate2checkout', __( 'Activate 2Checkout', 'AWPCP' ), 'checkbox', 1, __( 'Activate 2Checkout?', 'AWPCP' ) );
		$this->add_setting( $key, '2checkout', __( '2Checkout account', 'AWPCP' ), 'textfield', 'xxxxxxx', __( 'Account for 2Checkout payments (if running in pay mode and if 2Checkout is activated)', 'AWPCP' ) );
		// $this->add_setting($key, 'twocheckoutpaymentsrecurring', 'Use 2Checkout recurring payments?', 'checkbox', 0, 'Use recurring payments 2Checkout (this feature is not fully automated or fully integrated. For more reliable results do not use recurring).');

		// Group: Image

		$group = $this->add_group( __( 'Image', 'AWPCP' ), 'image-settings', 50);

		// Section: Image Settings - Default

		$key = $this->add_section($group, __('Image Settings', 'AWPCP'), 'default', 10, array($this, 'section'));

		$this->add_setting( $key, 'imagesallowdisallow', __( 'Allow images in Ads?', 'AWPCP' ), 'checkbox', 1, __( 'Allow images in ads? (affects both free and pay mode)', 'AWPCP' ) );
		$this->add_setting( $key, 'imagesapprove', __( 'Hide images until admin approves them', 'AWPCP' ), 'checkbox', 0, '');
		$this->add_setting( $key, 'awpcp_thickbox_disabled', __( 'Disable AWPCP Lightbox feature', 'AWPCP' ), 'checkbox', 0, __( 'Turn off the lightbox/thickbox element used by AWPCP. Some themes cannot handle it and a conflict results.', 'AWPCP' ) );
		$this->add_setting( $key, 'show-click-to-enlarge-link', __( 'Show click to enlarge link?', 'AWPCP' ), 'checkbox', 1, '' );
		$this->add_setting( $key, 'imagesallowedfree', __( 'Number of images allowed in Free mode', 'AWPCP' ), 'textfield', 4, __( 'Number of Image Uploads Allowed (Free Mode)', 'AWPCP' ) );

		// Section: Image Settings - File Settings

		$key = $this->add_section($group, __('Image File Settings', 'AWPCP'), 'image-file', 10, array($this, 'section'));

		$this->add_setting( $key, 'uploadfoldername', __( 'Uploads folder name', 'AWPCP' ), 'textfield', 'uploads', __( 'Upload folder name. (Folder must exist and be located in your wp-content directory)', 'AWPCP' ) );

		$options = array( '0755' => '0755', '0777' => '0777' );
		$this->add_setting( $key, 'upload-directory-permissions', __( 'File permissions for uploads directory', 'AWPCP' ), 'select', '0755', __( 'File permissions applied to the uploads directory and sub-directories so that the plugin is allowed to write to those directories.', 'AWPCP' ), array( 'options' => $options ) );

		$this->add_setting( $key, 'maximagesize', __( 'Maximum file size per image', 'AWPCP' ), 'textfield', '1048576', __( 'Maximum file size, in bytes, for files user can upload to system. 1 MB = 1048576 bytes. You can google "x MB to bytes" to get an accurate convertion.', 'AWPCP' ) );
		$this->add_setting( $key, 'minimagesize', __( 'Minimum file size per image', 'AWPCP' ), 'textfield', '300', __( 'Minimum file size, in bytes, for files user can upload to system. 1 MB = 1048576 bytes. You can google "x MB to bytes" to get an accurate convertion.', 'AWPCP' ) );
		$this->add_setting( $key, 'imgminwidth', __( 'Minimum image width', 'AWPCP' ), 'textfield', '640', __( 'Minimum width for images.', 'AWPCP' ) );
		$this->add_setting( $key, 'imgminheight', __( 'Minimum image height', 'AWPCP' ), 'textfield', '480', __( 'Minimum height for images.', 'AWPCP' ) );
		$this->add_setting( $key, 'imgmaxwidth', __( 'Maximum image width', 'AWPCP' ), 'textfield', '640', __( 'Maximum width for images. Images wider than this are automatically resized upon upload.', 'AWPCP' ) );
		$this->add_setting( $key, 'imgmaxheight', __( 'Maximum image height', 'AWPCP' ), 'textfield', '480', __( 'Maximum height for images. Images taller than this are automatically resized upon upload.', 'AWPCP' ) );

		// Section: Image Settings - Primary Images

		$key = $this->add_section($group, __('Primary Image Settings', 'AWPCP'), 'primary-image', 10, array($this, 'section'));

		$this->add_setting( $key, 'displayadthumbwidth', __( 'Thumbnail width (Ad Listings page)', 'AWPCP' ), 'textfield', '80', __( 'Width of the thumbnail for the primary image shown in Ad Listings view.', 'AWPCP' ) );
		$this->add_setting( $key, 'primary-image-thumbnail-width', __( 'Thumbnail width (Primary Image)', 'AWPCP' ), 'textfield', '200', __( 'Width of the thumbnail for the primary image shown in Single Ad view.', 'AWPCP' ) );
		$this->add_setting( $key, 'primary-image-thumbnail-height', __( 'Thumbnail height (Primary Image)', 'AWPCP' ), 'textfield', '200', __( 'Height of the thumbnail for the primary image shown in Single Ad view.', 'AWPCP' ) );
		$this->add_setting( $key, 'crop-primary-image-thumbnails', __( 'Crop primary image thumbnails?', 'AWPCP' ), 'checkbox', 1, _x('If you decide to crop thumbnails, images will match exactly the dimensions in the settings above but part of the image may be cropped out. If you decide to resize, image thumbnails will be resized to match the specified width and their height will be adjusted proportionally; depending on the uploaded images, thumbnails may have different heights.', 'settings', 'AWPCP' ) );
		// Section: Image Settings - Thumbnails

		$key = $this->add_section( $group, __( 'Thumbnail Settings', 'AWPCP' ), 'thumbnails', 10, array( $this, 'section' ) );

		$options = array(0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4);
		$this->add_setting( $key, 'display-thumbnails-in-columns', __( 'Number of columns of thumbnails to show in Show Ad page.', 'AWPCP' ), 'select', 0, __( 'Zero means there will be as many thumbnails as possible per row.', 'AWPCP' ), array( 'options' => $options ) );
		$this->add_setting( $key, 'imgthumbwidth', __( 'Thumbnail width', 'AWPCP' ), 'textfield', '125', __( 'Width of the thumbnail images.', 'AWPCP' ) );
		$this->add_setting( $key, 'imgthumbheight', __( 'Thumbnail height', 'AWPCP' ), 'textfield', '125', __( 'Height of the thumbnail images.', 'AWPCP' ) );
		$this->add_setting( $key, 'crop-thumbnails', __( 'Crop thumbnail images?', 'AWPCP' ), 'checkbox', 1, _x( 'If you decide to crop thumbnails, images will match exactly the dimensions in the settings above but part of the image may be cropped out. If you decide to resize, image thumbnails will be resized to match the specified width and their height will be adjusted proportionally; depending on the uploaded images, thumbnails may have different heights.', 'settings', 'AWPCP' ) );


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


		// Group: Form Field

		$group = $this->add_group( __( 'Form', 'AWPCP' ), 'form-field-settings', 70);

		// Section: Posted By Field

		$key = $this->add_section($group, __('Posted By Field', 'AWPCP'), 'posted-by', 10, array($this, 'section'));
		$this->add_setting($key, 'displaypostedbyfield', __( 'Show Posted By field', 'AWPCP' ), 'checkbox', 1, __( 'Show Posted By field?', 'AWPCP' ) );

		// Section: Phone Field

		$key = $this->add_section($group, __('Phone Field', 'AWPCP'), 'phone', 10, array($this, 'section'));

		$this->add_setting( $key, 'displayphonefield', __( 'Show Phone field', 'AWPCP' ), 'checkbox', 1, __( 'Show phone field?', 'AWPCP' ) );
		$this->add_setting( $key, 'displayphonefieldreqop', __( 'Require Phone', 'AWPCP' ), 'checkbox', 0, __( 'Require phone?', 'AWPCP' ) );
		$this->add_setting( $key, 'displayphonefieldpriv', __( 'Make Phone private?', 'AWPCP' ), 'checkbox', 0, __( 'Make Phone private (only visible to logged in users).', 'AWPCP' ) );

		// Section: Website Field

		$key = $this->add_section($group, __('Website Field', 'AWPCP'), 'website', 10, array($this, 'section'));
		$this->add_setting( $key, 'displaywebsitefield', __( 'Show Website field', 'AWPCP' ), 'checkbox', 1, __( 'Show website field?', 'AWPCP' ) );
		$this->add_setting( $key, 'displaywebsitefieldreqop', __( 'Require Website', 'AWPCP' ), 'checkbox', 0, __( 'Require website?', 'AWPCP' ) );
		$this->add_setting( $key, 'displaywebsitefieldreqpriv', __( 'Make Website private?', 'AWPCP' ), 'checkbox', 0, __( 'Make Website private (only visible to logged in users).', 'AWPCP' ) );

		// Section: Price Field

		$key = $this->add_section($group, __('Price Field', 'AWPCP'), 'price', 10, array($this, 'section'));
		$this->add_setting( $key, 'displaypricefield', __( 'Show Price field', 'AWPCP' ), 'checkbox', 1, __( 'Show price field?', 'AWPCP' ) );
		$this->add_setting( $key, 'displaypricefieldreqop', __( 'Require Price', 'AWPCP' ), 'checkbox', 0, __( 'Require price?', 'AWPCP' ) );

		// Section: Country Field

		$key = $this->add_section($group, __('Country Field', 'AWPCP'), 'country', 10, array($this, 'section'));
		$this->add_setting($key, 'displaycountryfield', __( 'Show Country field', 'AWPCP' ), 'checkbox', 1, __( 'Show country field?', 'AWPCP' ) );
		$this->add_setting($key, 'displaycountryfieldreqop', __( 'Require Country', 'AWPCP' ), 'checkbox', 0, __( 'Require country?', 'AWPCP' ) );

		// Section: State Field

		$key = $this->add_section($group, __('State Field', 'AWPCP'), 'state', 10, array($this, 'section'));
		$this->add_setting( $key, 'displaystatefield', __( 'Show State field', 'AWPCP' ), 'checkbox', 1, __( 'Show State field?', 'AWPCP' ) );
		$this->add_setting( $key, 'displaystatefieldreqop', __( 'Require State', 'AWPCP' ), 'checkbox', 0, __( 'Require state?', 'AWPCP' ) );

		// Section: County Field

		$key = $this->add_section($group, __('County Field', 'AWPCP'), 'county', 10, array($this, 'section'));
		$this->add_setting($key, 'displaycountyvillagefield', __( 'Show County/Village/other', 'AWPCP' ), 'checkbox', 0, __( 'Show County/village/other?', 'AWPCP' ) );
		$this->add_setting($key, 'displaycountyvillagefieldreqop', __( 'Require County/Village/other', 'AWPCP' ), 'checkbox', 0, __( 'Require county/village/other?', 'AWPCP' ) );

		// Section: City Field

		$key = $this->add_section($group, __('City Field', 'AWPCP'), 'city', 10, array($this, 'section'));
		$this->add_setting($key, 'displaycityfield', __( 'Show City field', 'AWPCP' ), 'checkbox', 1, __( 'Show city field?', 'AWPCP' ) );
		$this->add_setting($key, 'show-city-field-before-county-field', __( 'Show City field before County field', 'AWPCP' ), 'checkbox', 1, __( 'If checked the City field will be shown before the County field. This setting may be overwritten if Region Control module is installed.', 'AWPCP' ) );
		$this->add_setting($key, 'displaycityfieldreqop', __( 'Require City', 'AWPCP' ), 'checkbox', 0, __( 'Require city?', 'AWPCP' ) );


		// Group: User Registration

		$group = $this->add_group( __( 'Registration', 'AWPCP' ), 'registration-settings', 80);

		// Section: User Registration

		$key = $this->add_section($group, __('Registration Settings', 'AWPCP'), 'default', 10, array($this, 'section'));

		$this->add_setting( $key, 'requireuserregistration', __( 'Require user registration', 'AWPCP' ), 'checkbox', 0, __( 'Require user registration?', 'AWPCP' ) );
		$this->add_setting( $key, 'reply-to-ad-requires-registration', __( 'Reply to Ad requires user registration', 'AWPCP' ), 'checkbox', 0, __( 'Require user registration for replying to an Ad?', 'AWPCP' ) );
		// $this->add_setting( $key, 'postloginformto', __( 'Post login form to', 'AWPCP' ), 'textfield', '', __( 'Post login form to this URL. Value should be the full URL to the wordpress login script (e.g. http://www.awpcp.com/wp-login.php).', 'AWPCP' ) . '<br/>' . __( '**Only needed if registration is required and your login url is mod-rewritten.', 'AWPCP' ) );
		$this->add_setting( $key, 'registrationurl', __( 'Custom Registration Page URL', 'AWPCP' ), 'textfield', '', __( 'Location of registration page. Value should be the full URL to the wordpress registration page (e.g. http://www.awpcp.com/wp-login.php?action=register).', 'AWPCP' ) . '<br/>' . __( '**Only change this setting when using membership plugin with custom login pages or similar scenarios.', 'AWPCP' ) );


		// Group: Email

		$group = $this->add_group('Email', 'email-settings', 90);

		// Section: General Email Settings

		$key = $this->add_section($group, __('General Email Settings', 'AWPCP'), 'default', 10, array($this, 'section'));

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

		$key = $this->add_section( $group, __( 'Advanced Email Configuration', 'AWPCP' ), 'advanced', 10, array( $this, 'section' ) );

		$this->add_setting( $key, 'usesmtp', __( 'Enable external SMTP server', 'AWPCP' ), 'checkbox', 0, __( 'Enabled external SMTP server (if emails not processing normally).', 'AWPCP' ) );
		$this->add_setting( $key, 'smtphost', __( 'SMTP host', 'AWPCP' ), 'textfield', 'mail.example.com', __( 'SMTP host (if emails not processing normally).', 'AWPCP' ) );
		$this->add_setting( $key, 'smtpport', __( 'SMTP port', 'AWPCP' ), 'textfield', '25', __( 'SMTP port (if emails not processing normally).', 'AWPCP' ) );
		$this->add_setting( $key, 'smtpusername', __( 'SMTP username', 'AWPCP' ), 'textfield', 'smtp_username', __( 'SMTP username (if emails not processing normally).', 'AWPCP' ) );
		$this->add_setting( $key, 'smtppassword', __( 'SMTP password', 'AWPCP' ), 'password', '', __( 'SMTP password (if emails not processing normally).', 'AWPCP' ) );

		// Group: Email

		$group = $this->add_group('Facebook', 'facebook-settings', 100);

		// save settings to database
		$this->skip = true;
		update_option($this->option, $this->options);
		$this->skip = false;
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
		update_option($this->option, $this->options);
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
			$setting->args = $args;

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
			update_option($this->option, $this->options);
			return true;
		}
		return false;
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
				awpcp_flash( __( "You cannot enable Akismet SPAM control because you do not have Akismet installed/activated","AWPCP"), 'error' );
				$options[$setting] = 0;
			} else if ($options[$setting] == 1 && empty($wpcom_api_key)) {
				awpcp_flash( __( "You cannot enable Akismet SPAM control because you have not configured Akismet properly", "AWPCP" ), 'error' );
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
		$currency_codes = array('AUD','BRL','CAD','CZK','DKK','EUR',
								'HKD','HUF','ILS','JPY','MYR','MXN',
								'NOK','NZD','PHP','PLN','GBP','SGD',
								'SEK','CHF','TWD','THB','USD');

		$setting = 'paypalcurrencycode';
		if (isset($options[$setting]) &&
			!in_array($options[$setting], $currency_codes)) {

			$message = __("There is a problem with the currency code you have entered. It does not match any of the codes in the list of available currencies provided by PayPal.","AWPCP");
			$message.= "<br/>" . __("The available currency codes are","AWPCP");
			$message.= ":<br/>" . join(' | ', $currency_codes);
			awpcp_flash($message);

			$options[$setting] = 'USD';
		}

		$setting = 'displaycurrencycode';
		if (isset($options[$setting]) &&
			!in_array($options[$setting], $currency_codes)) {

			$message = __("There is a problem with the currency code you have entered. It does not match any of the codes in the list of available currencies provided by PayPal.","AWPCP");
			$message.= "<br/>" . __("The available currency codes are","AWPCP");
			$message.= ":<br/>" . join(' | ', $currency_codes);
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

		$pages = awpcp_pages();
		$pageids = $wpdb->get_results('SELECT page, id FROM ' . AWPCP_TABLE_PAGES, OBJECT_K);

		foreach ($pages as $key => $data) {
			$id = intval($pageids[$key]->id);

			if ($id <= 0 || is_null(get_post($id))) {
				continue;
			}

			$title = add_slashes_recursive($options[$key]);
			$page = array(
				'ID' => $id,
				'post_title' => $title,
				'post_name' => sanitize_title($options[$key]));

			wp_update_post($page);
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
		$html.= 'name="awpcp-options[' . $setting->name . ']" />';
		$html.= strlen($setting->helptext) > 60 ? '<br/>' : '';
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

		$html = '<select name="awpcp-options['. $setting->name .']">';
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

		$field_type = $args['multiple'] ? 'checkbox' : 'radio';
		$selected = $this->get_option( $setting->name );

		$html = array();

		foreach ( $args['choices'] as $value => $label ) {
			$id = "{$setting->name}-$value";
			$name = 'awpcp-options[' . $setting->name . '][]';
			$checked = in_array( $value, $selected ) ? 'checked="checked"' : '';

			$html_field = '<input id="%s" type="%s" name="%s" value="%s" %s />';
			$html_field = sprintf( $html_field, $id, $field_type, $name, $value, $checked );
			$html_label = '<label for="' . $id . '">' . $label . '</label><br/>';

			$html[] = $html_field . '&nbsp;' . $html_label;
		}

		$html[] = '<span class="description">' . $setting->helptext . '</span>';

		echo join( '', $html );
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
