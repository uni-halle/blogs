<?php

class AWPCP_RegistrationSettings {

    public function register_settings( $settings ) {
        $key = $settings->add_section( 'registration-settings', __('Registration Settings', 'AWPCP'), 'default', 10, array( $settings, 'section' ) );

        $settings->add_setting( $key, 'requireuserregistration', __( 'Place Ad requires user registration', 'AWPCP' ), 'checkbox', 0, __( 'Only registered users will be allowed to post Ads.', 'AWPCP' ) );
        $settings->add_setting( $key, 'reply-to-ad-requires-registration', __( 'Reply to Ad requires user registration', 'AWPCP' ), 'checkbox', 0, __( 'Require user registration for replying to an Ad?', 'AWPCP' ) );
        $settings->add_setting( $key, 'registrationurl', __( 'Custom Registration Page URL', 'AWPCP' ), 'textfield', '', __( 'Location of registration page. Value should be the full URL to the WordPress registration page (e.g. http://www.awpcp.com/wp-login.php?action=register).', 'AWPCP' ) . '<br/>' . __( '**Only change this setting when using a membership plugin with custom login pages or similar scenarios.', 'AWPCP' ) );
    }
}
