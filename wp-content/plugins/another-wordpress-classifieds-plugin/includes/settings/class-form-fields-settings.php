<?php

function awpcp_form_fields_settings() {
    return new AWPCP_FormFieldsSettings();
}

class AWPCP_FormFieldsSettings {

    public function register_settings( $settings ) {
        $group = $settings->add_group( __( 'Form', 'another-wordpress-classifieds-plugin' ), 'form-field-settings', 70 );

        $key = $settings->add_section( $group, __( 'Form Steps', 'another-wordpress-classifieds-plugin' ), 'form-steps', 3, array( $settings, 'section' ) );

        $settings->add_setting(
            $key,
            'show-create-listing-form-steps',
            __( 'Show Form Steps', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            1,
            __( 'If checked, when a user is creating a new listing, a list of steps will be shown at the top of the forms.', 'another-wordpress-classifieds-plugin' )
        );

        // Section: User Field

        // TODO: Is this the right place to put this setting?
        $key = $settings->add_section( $group, __( 'User Field', 'another-wordpress-classifieds-plugin' ), 'user', 5, array( $settings, 'section' ) );
        $options = array( 'dropdown' => __( 'Dropdown', 'another-wordpress-classifieds-plugin' ), 'autocomplete' => __( 'Autocomplete', 'another-wordpress-classifieds-plugin' ) );
        $settings->add_setting( $key, 'user-field-widget', __( 'HTML Widget for User field', 'another-wordpress-classifieds-plugin' ), 'radio', 'dropdown', __( 'The user field can be represented with an HTML dropdown or a text field with autocomplete capabilities. Using the dropdown is faster if you have a small number of users. If your website has a lot of registered users, however, the dropdown may take too long to render and using the autocomplete version may be a better idea.', 'another-wordpress-classifieds-plugin' ), array( 'options' => $options ) );
        $settings->add_setting( $key, 'displaypostedbyfield', __( 'Show User Field on Search', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, __( 'Show as "Posted By" in search form?', 'another-wordpress-classifieds-plugin' ) );

        $settings->add_setting(
            $key,
            'overwrite-contact-information-on-user-change',
            __( 'Overwrite information in contact fields when a different listing owner is selected', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            1,
            __( 'If this setting is enabled, when an administrator is editing a listing and he changes the selected value in the User/Owner field, the information in the contact fields (Contact Name, Contact Email and Contact Phone Number) will be updated (overwriting the information already entered in those fields) using the information of the user just selected. The modifications will not be persisted until you click the Continue button.', 'another-wordpress-classifieds-plugin' )
        );

        $settings->add_setting(
            $key,
            'user-name-format',
            __( "User's name format", 'another-wordpress-classifieds-plugin' ),
            'select',
            'display_name',
            __( "The selected format will be used to show a user's name in dropdown fields, text fields and templates.", 'another-wordpress-classifieds-plugin' ),
            array(
                'options' => array(
                    'user_login' => esc_html( "<Username>" ),
                    'firstname_first' => esc_html( '<First Name> <Last Name>' ),
                    'lastname_first' => esc_html( '<Last Name> <First Name>' ),
                    'firstname' => esc_html( '<First Name>' ),
                    'lastname' => esc_html( '<Last Name>' ),
                    'display_name' => esc_html( '<Display Name>' ),
                ),
            )
        );

        $key = $settings->add_section( $group, __( 'Contact Fields', 'another-wordpress-classifieds-plugin' ), 'contact', 10, array( $settings, 'section' ) );

        $settings->add_setting(
            $key,
            'make-contact-fields-writable-for-logged-in-users',
            __( 'Allow logged in users to overwrite Contact Name and Contact Email', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            false,
            __( "Normally registered users who are not administrators are not allowed to change the email address or contact name. The fields are rendered as read-only and pre-filled with the information from each user's profile. If this setting is enabled, logged in users will be allowed to overwrite those fields.", 'another-wordpress-classifieds-plugin' )
        );

        // Section: Phone Field

        $key = $settings->add_section($group, __('Phone Field', 'another-wordpress-classifieds-plugin'), 'phone', 15, array($settings, 'section'));

        $settings->add_setting( $key, 'displayphonefield', __( 'Show Phone field', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, __( 'Show phone field?', 'another-wordpress-classifieds-plugin' ) );
        $settings->add_setting( $key, 'displayphonefieldreqop', __( 'Require Phone', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'Require phone on Place Ad and Edit Ad forms?', 'another-wordpress-classifieds-plugin' ) );

        $settings->add_setting(
            $key,
            'displayphonefieldpriv',
            __( 'Show Phone Field only to registered users', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            0,
            __( 'This setting restricts viewing of this field so that only registered users that are logged in can see it.', 'another-wordpress-classifieds-plugin' )
        );

        // Section: Website Field

        $key = $settings->add_section($group, __('Website Field', 'another-wordpress-classifieds-plugin'), 'website', 15, array($settings, 'section'));
        $settings->add_setting( $key, 'displaywebsitefield', __( 'Show Website field', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, __( 'Show website field?', 'another-wordpress-classifieds-plugin' ) );
        $settings->add_setting( $key, 'displaywebsitefieldreqop', __( 'Require Website', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'Require website on Place Ad and Edit Ad forms?', 'another-wordpress-classifieds-plugin' ) );

        $settings->add_setting(
            $key,
            'displaywebsitefieldreqpriv',
            __( 'Show Website Field only to registered users', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            0,
            __( 'This setting restricts viewing of this field so that only registered users that are logged in can see it.', 'another-wordpress-classifieds-plugin' )
        );

        // Section: Price Field

        $key = $settings->add_section($group, __('Price Field', 'another-wordpress-classifieds-plugin'), 'price', 15, array($settings, 'section'));
        $settings->add_setting( $key, 'displaypricefield', __( 'Show Price field', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, __( 'Show price field?', 'another-wordpress-classifieds-plugin' ) );
        $settings->add_setting( $key, 'displaypricefieldreqop', __( 'Require Price', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'Require price on Place Ad and Edit Ad forms?', 'another-wordpress-classifieds-plugin' ) );

        $settings->add_setting(
            $key,
            'price-field-is-restricted',
            __( 'Show Price Field only to registered users', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            0,
            __( 'This setting restricts viewing of this field so that only registered users that are logged in can see it.', 'another-wordpress-classifieds-plugin' )
        );

        $settings->add_setting( $key, 'hide-price-field-if-empty', __( 'Hide price field if empty or zero', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'If checked all price placeholders will be replaced with an empty string when the price of the Ad is zero or was not set.', 'another-wordpress-classifieds-plugin' ) );

        // Section: Country Field

        $key = $settings->add_section($group, __('Country Field', 'another-wordpress-classifieds-plugin'), 'country', 20, array($settings, 'section'));
        $settings->add_setting($key, 'displaycountryfield', __( 'Show Country field', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, __( 'Show country field?', 'another-wordpress-classifieds-plugin' ) );
        $settings->add_setting($key, 'displaycountryfieldreqop', __( 'Require Country', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'Require country on Place Ad and Edit Ad forms?', 'another-wordpress-classifieds-plugin' ) );

        // Section: State Field

        $key = $settings->add_section($group, __('State Field', 'another-wordpress-classifieds-plugin'), 'state', 25, array($settings, 'section'));
        $settings->add_setting( $key, 'displaystatefield', __( 'Show State field', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, __( 'Show state field?', 'another-wordpress-classifieds-plugin' ) );
        $settings->add_setting( $key, 'displaystatefieldreqop', __( 'Require State', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'Require state on Place Ad and Edit Ad forms?', 'another-wordpress-classifieds-plugin' ) );

        // Section: County Field

        $key = $settings->add_section($group, __('County Field', 'another-wordpress-classifieds-plugin'), 'county', 30, array($settings, 'section'));
        $settings->add_setting($key, 'displaycountyvillagefield', __( 'Show County/Village/other', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'Show County/village/other?', 'another-wordpress-classifieds-plugin' ) );
        $settings->add_setting($key, 'displaycountyvillagefieldreqop', __( 'Require County/Village/other', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'Require county/village/other on Place Ad and Edit Ad forms?', 'another-wordpress-classifieds-plugin' ) );

        // Section: City Field

        $key = $settings->add_section($group, __('City Field', 'another-wordpress-classifieds-plugin'), 'city', 35, array($settings, 'section'));
        $settings->add_setting($key, 'displaycityfield', __( 'Show City field', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, __( 'Show city field?', 'another-wordpress-classifieds-plugin' ) );
        $settings->add_setting($key, 'show-city-field-before-county-field', __( 'Show City field before County field', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, __( 'If checked the city field will be shown before the county field. This setting may be overwritten if Region Control module is installed.', 'another-wordpress-classifieds-plugin' ) );
        $settings->add_setting($key, 'displaycityfieldreqop', __( 'Require City', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'Require city on Place Ad and Edit Ad forms?', 'another-wordpress-classifieds-plugin' ) );
    }

    public function settings_header() {
        $section_url = awpcp_get_admin_form_fields_url();
        $section_link = sprintf( '<a href="%s">%s</a>', $section_url, __( 'Form Fields', 'another-wordpress-classifieds-plugin' ) );

        $message = __( 'Go to the <form-fields-section> admin section to change the order in which the fields mentioned below are shown to users in the Ad Details form.', 'another-wordpress-classifieds-plugin' );
        $message = str_replace( '<form-fields-section>', $section_link, $message );

        echo awpcp_print_message( $message );
    }
}
