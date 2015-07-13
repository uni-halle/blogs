<?php

function awpcp_form_fields_settings() {
    return new AWPCP_FormFieldsSettings();
}

class AWPCP_FormFieldsSettings {

    public function register_settings( $settings ) {
        $group = $settings->add_group( __( 'Form', 'AWPCP' ), 'form-field-settings', 70 );

        $key = $settings->add_section( $group, __( 'Form Steps', 'AWPCP' ), 'form-steps', 3, array( $settings, 'section' ) );

        $settings->add_setting(
            $key,
            'show-create-listing-form-steps',
            __( 'Show Form Steps', 'AWPCP' ),
            'checkbox',
            1,
            __( 'If checked, when a user is creating a new listing, a list of steps will be shown at the top of the forms.', 'AWPCP' )
        );

        // Section: User Field

        // TODO: Is this the right place to put this setting?
        $key = $settings->add_section( $group, __( 'User Field', 'AWPCP' ), 'user', 5, array( $settings, 'section' ) );
        $options = array( 'dropdown' => __( 'Dropdown', 'AWPCP' ), 'autocomplete' => __( 'Autocomplete', 'AWPCP' ) );
        $settings->add_setting( $key, 'user-field-widget', __( 'HTML Widget for User field', 'AWPCP' ), 'radio', 'dropdown', __( 'The user field can be represented with an HTML dropdown or a text field with autocomplete capabilities. Using the dropdown is faster if you have a small number of users. If your website has a lot of registered users, however, the dropdown may take too long to render and using the autocomplete version may be a better idea.', 'AWPCP' ), array( 'options' => $options ) );
        $settings->add_setting( $key, 'displaypostedbyfield', __( 'Show User Field on Search', 'AWPCP' ), 'checkbox', 1, __( 'Show as "Posted By" in search form?', 'AWPCP' ) );

        $key = $settings->add_section( $group, __( 'Contact Fields', 'AWPCP' ), 'contact', 10, array( $settings, 'section' ) );

        $settings->add_setting(
            $key,
            'make-contact-fields-writable-for-logged-in-users',
            __( 'Allow logged in users to overwrite Contact Name and Contact Email', 'AWPCP' ),
            'checkbox',
            false,
            __( "Normally registered users who are not administrators are not allowed to change the email address or contact name. The fields are rendered as read-only and pre-filled with the information from each user's profile. If this setting is enabled, logged in users will be allowed to overwrite those fields.", 'AWPCP' )
        );

        // Section: Phone Field

        $key = $settings->add_section($group, __('Phone Field', 'AWPCP'), 'phone', 15, array($settings, 'section'));

        $settings->add_setting( $key, 'displayphonefield', __( 'Show Phone field', 'AWPCP' ), 'checkbox', 1, __( 'Show phone field?', 'AWPCP' ) );
        $settings->add_setting( $key, 'displayphonefieldreqop', __( 'Require Phone', 'AWPCP' ), 'checkbox', 0, __( 'Require phone on Place Ad and Edit Ad forms?', 'AWPCP' ) );

        $settings->add_setting(
            $key,
            'displayphonefieldpriv',
            __( 'Show Phone Field only to registered users', 'AWPCP' ),
            'checkbox',
            0,
            __( 'This setting restricts viewing of this field so that only registered users that are logged in can see it.', 'AWPCP' )
        );

        // Section: Website Field

        $key = $settings->add_section($group, __('Website Field', 'AWPCP'), 'website', 15, array($settings, 'section'));
        $settings->add_setting( $key, 'displaywebsitefield', __( 'Show Website field', 'AWPCP' ), 'checkbox', 1, __( 'Show website field?', 'AWPCP' ) );
        $settings->add_setting( $key, 'displaywebsitefieldreqop', __( 'Require Website', 'AWPCP' ), 'checkbox', 0, __( 'Require website on Place Ad and Edit Ad forms?', 'AWPCP' ) );

        $settings->add_setting(
            $key,
            'displaywebsitefieldreqpriv',
            __( 'Show Website Field only to registered users', 'AWPCP' ),
            'checkbox',
            0,
            __( 'This setting restricts viewing of this field so that only registered users that are logged in can see it.', 'AWPCP' )
        );

        // Section: Price Field

        $key = $settings->add_section($group, __('Price Field', 'AWPCP'), 'price', 15, array($settings, 'section'));
        $settings->add_setting( $key, 'displaypricefield', __( 'Show Price field', 'AWPCP' ), 'checkbox', 1, __( 'Show price field?', 'AWPCP' ) );
        $settings->add_setting( $key, 'displaypricefieldreqop', __( 'Require Price', 'AWPCP' ), 'checkbox', 0, __( 'Require price on Place Ad and Edit Ad forms?', 'AWPCP' ) );

        $settings->add_setting(
            $key,
            'price-field-is-restricted',
            __( 'Show Price Field only to registered users', 'AWPCP' ),
            'checkbox',
            0,
            __( 'This setting restricts viewing of this field so that only registered users that are logged in can see it.', 'AWPCP' )
        );

        $settings->add_setting( $key, 'hide-price-field-if-empty', __( 'Hide price field if empty or zero', 'AWPCP' ), 'checkbox', 0, __( 'If checked all price placeholders will be replaced with an empty string when the price of the Ad is zero or was not set.', 'AWPCP' ) );

        // Section: Country Field

        $key = $settings->add_section($group, __('Country Field', 'AWPCP'), 'country', 20, array($settings, 'section'));
        $settings->add_setting($key, 'displaycountryfield', __( 'Show Country field', 'AWPCP' ), 'checkbox', 1, __( 'Show country field?', 'AWPCP' ) );
        $settings->add_setting($key, 'displaycountryfieldreqop', __( 'Require Country', 'AWPCP' ), 'checkbox', 0, __( 'Require country on Place Ad and Edit Ad forms?', 'AWPCP' ) );

        // Section: State Field

        $key = $settings->add_section($group, __('State Field', 'AWPCP'), 'state', 25, array($settings, 'section'));
        $settings->add_setting( $key, 'displaystatefield', __( 'Show State field', 'AWPCP' ), 'checkbox', 1, __( 'Show state field?', 'AWPCP' ) );
        $settings->add_setting( $key, 'displaystatefieldreqop', __( 'Require State', 'AWPCP' ), 'checkbox', 0, __( 'Require state on Place Ad and Edit Ad forms?', 'AWPCP' ) );

        // Section: County Field

        $key = $settings->add_section($group, __('County Field', 'AWPCP'), 'county', 30, array($settings, 'section'));
        $settings->add_setting($key, 'displaycountyvillagefield', __( 'Show County/Village/other', 'AWPCP' ), 'checkbox', 0, __( 'Show County/village/other?', 'AWPCP' ) );
        $settings->add_setting($key, 'displaycountyvillagefieldreqop', __( 'Require County/Village/other', 'AWPCP' ), 'checkbox', 0, __( 'Require county/village/other on Place Ad and Edit Ad forms?', 'AWPCP' ) );

        // Section: City Field

        $key = $settings->add_section($group, __('City Field', 'AWPCP'), 'city', 35, array($settings, 'section'));
        $settings->add_setting($key, 'displaycityfield', __( 'Show City field', 'AWPCP' ), 'checkbox', 1, __( 'Show city field?', 'AWPCP' ) );
        $settings->add_setting($key, 'show-city-field-before-county-field', __( 'Show City field before County field', 'AWPCP' ), 'checkbox', 1, __( 'If checked the city field will be shown before the county field. This setting may be overwritten if Region Control module is installed.', 'AWPCP' ) );
        $settings->add_setting($key, 'displaycityfieldreqop', __( 'Require City', 'AWPCP' ), 'checkbox', 0, __( 'Require city on Place Ad and Edit Ad forms?', 'AWPCP' ) );
    }

    public function settings_header() {
        $section_url = awpcp_get_admin_form_fields_url();
        $section_link = sprintf( '<a href="%s">%s</a>', $section_url, __( 'Form Fields', 'AWPCP' ) );

        $message = __( 'Go to the <form-fields-section> admin section to change the order in which the fields mentioned below are shown to users in the Ad Details form.', 'AWPCP' );
        $message = str_replace( '<form-fields-section>', $section_link, $message );

        echo awpcp_print_message( $message );
    }
}
