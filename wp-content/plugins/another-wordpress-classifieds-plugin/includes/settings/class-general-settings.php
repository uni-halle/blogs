<?php

function awpcp_general_settings() {
    return new AWPCP_GeneralSettings( awpcp_roles_and_capabilities() );
}

class AWPCP_GeneralSettings {

    private $roles;

    public function __construct( $roles ) {
        $this->roles = $roles;
    }

    public function register_settings( $settings ) {
        $group = $settings->add_group( __( 'General', 'AWPCP' ), 'general-settings', 5 );

        // Section: General - Ad Management Panel

        $key = $settings->add_section( $group, __( 'User Ad Management Panel', 'AWPCP' ), 'user-panel', 5, array( $settings, 'section' ) );

        $help_text = __( 'You must have registered users to use this setting. Turning it on will automatically enable "Require Registration" for AWPCP. Make sure you site allows users to register under <wp-settings-link>Settings->General</a>.', 'AWPCP' );
        $help_text = str_replace( '<wp-settings-link>', sprintf( '<a href="%s">', admin_url( 'options-general.php' ) ), $help_text );
        $settings->add_setting( $key, 'enable-user-panel', __( 'Enable User Ad Management Panel', 'AWPCP' ), 'checkbox', 0, $help_text );

        // Section: General - Default

        $key = $settings->add_section( $group, __( 'General Settings', 'AWPCP' ), 'default', 9, array( $settings, 'section' ) );

        $settings->add_setting( $key, 'activatelanguages', __( 'Turn on transalation file (POT)', 'AWPCP' ), 'checkbox', 0, __( "Enable translations. WordPress will look for an AWPCP-&lt;language&gt;.mo file in AWPCP's languages/ directory of the main plugin and premium modules. Example filenames are: AWPCP-en_EN.mo, AWPCP-es_ES.mo. You can generate .mo files using POEdit and the AWPCP.pot or AWPCP-en_EN.po files included with the plugin.", 'AWPCP' ) );
        $settings->add_setting( $key, 'main_page_display', __( 'Show Ad listings on main page', 'AWPCP' ), 'checkbox', 0, __( 'If unchecked only categories will be displayed', 'AWPCP' ) );
        $settings->add_setting( $key, 'view-categories-columns', __( 'Category columns in View Categories page', 'AWPCP' ), 'select', 2, '', array('options' => array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5)));
        $settings->add_setting( $key, 'collapse-categories-columns', __( 'Collapse Categories', 'AWPCP' ), 'checkbox', 0, __( 'If checked the list of sub-categories will be collapsed by default. Users would have to click the down arrow icon to expand the list and see the sub-categories.', 'AWPCP' ) );
        $settings->add_setting( $key, 'uiwelcome', __( 'Welcome message in Classified page', 'AWPCP' ), 'textarea', __( 'Looking for a job? Trying to find a date? Looking for an apartment? Browse our classifieds. Have a job to advertise? An apartment to rent? Post a Classified Ad.', 'AWPCP' ), __( 'The welcome text for your classified page on the user side', 'AWPCP' ) );

        $options = array('admin' => __( 'Administrator', 'AWPCP' ), 'admin,editor' => __( 'Administrator & Editor', 'AWPCP' ) );
        $settings->add_setting( $key, 'awpcpadminaccesslevel', __( 'Who can access AWPCP Admin Dashboard', 'AWPCP' ), 'radio', 'admin', __( 'Role of WordPress users who can have admin access to Classifieds.', 'AWPCP' ), array( 'options' => $options ) );
        $settings->add_setting( $key, 'awpcppagefilterswitch', __( 'Enable page filter', 'AWPCP' ), 'checkbox', 1, __( 'Uncheck this if you need to turn off the AWPCP page filter that prevents AWPCP classifieds children pages from showing up in your wp pages menu (You might need to do this if for example the AWPCP page filter is messing up your page menu. It means you will have to manually exclude the AWPCP children pages from showing in your page list. Some of the pages really should not be visible to your users by default).', 'AWPCP') );

        // Section: General - Date & Time Format

        $label = _x( 'Date & Time Format', 'settings', 'AWPCP' );

        $key = $settings->add_section( $group, $label, 'date-time-format', 10, array( $settings, 'section_date_time_format' ) );

        $datetime = current_time('timestamp');
        $options = array(
            'american' => sprintf( '<strong>%s</strong>: %s', __( 'American', 'AWPCP' ), awpcp_datetime( 'm/d/Y h:i:s', $datetime ) ),
            'european' => sprintf( '<strong>%s</strong>: %s', __( 'European', 'AWPCP' ), awpcp_datetime( 'd/m/Y H:i:s', $datetime ) ),
            'custom' => __( 'Your own.', 'AWPCP' ),
        );

        $settings->add_setting( $key, 'x-date-time-format', __( 'Date Time Format', 'AWPCP' ), 'radio', 'american', '', array( 'options' => $options ) );
        $settings->add_setting( $key, 'date-format', _x( 'Date Format', 'settings', 'AWPCP' ), 'textfield', 'm/d/Y', '' );
        $settings->add_setting( $key, 'time-format', _x( 'Time Format', 'settings', 'AWPCP' ), 'textfield', 'h:i:s', '' );
        $example = sprintf( '<strong>%s</strong>: <span example>%s</span>', _x( 'Example output', 'settings', 'AWPCP' ), awpcp_datetime( 'awpcp' ) );
        $description = _x( 'Full date/time output with any strings you wish to add. <date> and <time> are placeholders for date and time strings using the formats specified in the Date Format and Time Format settings above.', 'settings', 'AWPCP' );
        $settings->add_setting( $key, 'date-time-format', _x( 'Full Display String', 'settings', 'AWPCP' ), 'textfield', '<date> at <time>', esc_html( $description ) . '<br/>' . $example );

        // Section: General - Currency Format

        $key = $settings->add_section($group, __('Currency Format', 'AWPCP'), 'currency-format', 10, array( $settings, 'section' ) );

        $settings->add_setting(
            $key,
            'currency-code',
            __( 'Currency code', 'AWPCP' ),
            'textfield',
            $settings->get_option( 'displaycurrencycode', 'USD' ),
            __( "Prices in listings pages and payment pages will be displayed using this currency. The currency symbol will be generated based on this code, but if the plugin doesn't know the symbol for your currency, it will use an uppercase version of the code itself.", 'AWPCP' )
        );

        $settings->add_setting(
            $key,
            'currency-symbol',
            __( 'Currency symbol', 'AWPCP' ),
            'textfield',
            '',
            __( "Use this setting to overwrite the currency symbol shown in listings pages. If empty, the plugin will attempt to show one of the standard symbols for the selected currency code, but if the plugin doesn't know the symbol for your currency, it will use an uppercase version of the currency code.", 'AWPCP' )
        );

        $settings->add_setting($key, 'thousands-separator', __('Thousands separator', 'AWPCP'), 'textfield', _x(',', 'This translation is deprecated. Please go to the Settings section to change the thousands separator.', 'AWPCP'), '');
        $settings->add_setting($key, 'decimal-separator', __('Separator for the decimal point', 'AWPCP'), 'textfield', _x('.', 'This translation is deprecated. Please go to the Settings section to change the decimal separator.', 'AWPCP'), '');
        $settings->add_setting($key, 'show-decimals', __('Show decimals in price', 'AWPCP'), 'checkbox', 1, _x('Uncheck to show prices without decimals. The value will be rounded.', 'settings', 'AWPCP'));

        $settings->add_setting(
            $key,
            'show-currency-symbol',
            __( 'Show currency symbol', 'AWPCP' ),
            'radio',
            'show-currency-symbol-on-left',
            __( 'The currency symbol can be configured by changing the currency code in the settings above.', 'AWPCP' ),
            array(
                'options' => array(
                    'show-currency-symbol-on-left' => __( 'Show currency symbol on left', 'AWPCP' ),
                    'show-currency-symbol-on-right' => __( 'Show currency symbol on right', 'AWPCP' ),
                    'do-not-show-currency-symbol' => __( "Don't show currency symbol", 'AWPCP' ),
                ),
            )
        );

        $settings->add_setting(
            $key,
            'include-space-between-currency-symbol-and-amount',
            __( 'Include a space between the currency symbol and the amount', 'AWPCP' ),
            'checkbox',
            1,
            ''
        );

        // Section: General - Terms of Service

        $key = $settings->add_section( $group, __( 'Terms of Service', 'AWPCP' ), 'terms-of-service', 11, array( $settings, 'section' ) );

        $settings->add_setting( $key, 'requiredtos', __( 'Display and require Terms of Service', 'AWPCP' ), 'checkbox', 1, __( 'Display and require Terms of Service', 'AWPCP' ) );
        $settings->add_setting( $key, 'tos', __( 'Terms of Service', 'AWPCP' ), 'textarea', __( 'Terms of service go here...', 'AWPCP' ), __( 'Terms of Service for posting Ads. Put in text or an URL starting with http. If you use an URL, the text box will be replaced by a link to the appropriate Terms of Service page', 'AWPCP' ) );

        // Section: General - Anti-SPAM

        $key = $settings->add_section($group, __( 'Anti-SPAM', 'AWPCP' ), 'anti-spam', 10, array( $settings, 'section' ) );

        $options = array(
            'recaptcha' => __( 'reCAPTCHA (recommended)', 'AWPCP' ),
            'math' => __( 'Math', 'AWPCP' ),
        );

        if ( ! $settings->option_exists( 'useakismet' ) ) {
            $is_akismet_installed = function_exists( 'akismet_init' );
            $is_akismet_key_set = strlen( get_option( 'wordpress_api_key' ) ) > 0;
            $use_akismet_default_value = $is_akismet_installed && $is_akismet_key_set;
        } else {
            $use_akismet_default_value = $settings->get_option( 'useakismet' );
        }

        $settings->add_setting(
            $key,
            'useakismet',
            __( 'Use Akismet', 'AWPCP' ),
            'checkbox',
            $use_akismet_default_value,
            __( 'Use Akismet for Posting Ads/Contact Responses (strong anti-spam).', 'AWPCP' )
        );

        $settings->add_setting( $key, 'captcha-enabled', __( 'Enable CAPTCHA', 'AWPCP' ), 'checkbox', $settings->get_option( 'contactformcheckhuman', 1 ), __( 'A CAPTCHA is a program to ensure only humans are posting Ads to your website. Using a CAPTCHA will reduce the SPAM and prevent bots from posting on your website. If checked, an additional form field will be added to the Place Ad and Reply to Ad forms.', 'AWPCP' ) );
        $settings->add_setting( $key, 'captcha-provider', __( 'Type of CAPTCHA', 'AWPCP' ), 'select', 'math', __( 'reCAPTCHA: Uses distorted images that only humans should be able to read (recommended).', 'AWPCP' ) . '<br/>' . __( 'Math: Asks user to solve a simple arithmetic operation.', 'AWPCP' ), array( 'options' => $options ) );

        $settings->add_setting( $key, 'math-captcha-max-number', __( 'Max number used in Math CAPTCHA', 'AWPCP' ), 'textfield', $settings->get_option( 'contactformcheckhumanhighnumval', 10 ), __( 'Highest number used in aithmetic operation.', 'AWPCP') );

        $link = sprintf( '<a href="%1$s">%1$s</a>', 'https://www.google.com/recaptcha/admin' );
        $help_text = sprintf( __( 'You can get an API key from %s.', 'AWPCP' ), $link );

        $settings->add_setting( $key, 'recaptcha-public-key', __( 'reCAPTCHA Site Key', 'AWPCP' ), 'textfield', '', $help_text );
        $settings->add_setting( $key, 'recaptcha-private-key', __( 'reCAPTCHA Secret Key', 'AWPCP' ), 'textfield', '',$help_text );

        // Section: SEO Settings

        $key = $settings->add_section($group, __('SEO Settings', 'AWPCP'), 'seo-settings', 10, array( $settings, 'section' ) );

        $settings->add_setting( $key, 'seofriendlyurls', __( 'Turn on Search Engine Friendly URLs', 'AWPCP' ), 'checkbox', 0, __( 'Turn on Search Engine Friendly URLs? (SEO Mode)', 'AWPCP' ) );
    }

    public function validate_group_settings( $options, $group ) {
        $current_roles = $this->roles->get_administrator_roles_names();
        $selected_roles = $this->roles->get_administrator_roles_names_from_string( $options['awpcpadminaccesslevel'] );

        $removed_roles = array_diff( $current_roles, $selected_roles );
        $new_roles = array_diff( $selected_roles, $current_roles );

        if ( ! empty( $removed_roles ) ) {
            array_walk( $removed_roles, array( $this->roles, 'remove_administrator_capabilities_from_role' ) );
        }

        if ( ! empty( $new_roles ) ) {
            array_walk( $new_roles, array( $this->roles, 'add_administrator_capabilities_to_role' ) );
        }

        return $options;
    }
}
