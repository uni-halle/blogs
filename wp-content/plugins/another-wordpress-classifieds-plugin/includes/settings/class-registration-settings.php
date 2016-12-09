<?php

class AWPCP_RegistrationSettings {

    public function register_settings( $settings ) {
        $key = $settings->add_section( 'registration-settings', __('Registration Settings', 'another-wordpress-classifieds-plugin'), 'default', 10, array( $settings, 'section' ) );

        $settings->add_setting( $key, 'requireuserregistration', __( 'Place Ad requires user registration', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'Only registered users will be allowed to post Ads.', 'another-wordpress-classifieds-plugin' ) );
        $settings->add_setting( $key, 'reply-to-ad-requires-registration', __( 'Reply to Ad requires user registration', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'Require user registration for replying to an Ad?', 'another-wordpress-classifieds-plugin' ) );

        $settings->add_setting(
            $key,
            'login-url',
            __( 'Login URL', 'another-wordpress-classifieds-plugin' ),
            'textfield',
            '',
            __( 'Location of the login page. The value should be the full URL to the WordPress login page (e.g. http://www.awpcp.com/wp-login.php).', 'another-wordpress-classifieds-plugin' ) . '<br/><br/>' . __( 'IMPORTANT: Only change this setting when using a membership plugin with custom login pages or similar scenarios.', 'another-wordpress-classifieds-plugin' )
        );

        $settings->add_setting( $key, 'registrationurl', __( 'Custom Registration Page URL', 'another-wordpress-classifieds-plugin' ), 'textfield', '', __( 'Location of registration page. Value should be the full URL to the WordPress registration page (e.g. http://www.awpcp.com/wp-login.php?action=register).', 'another-wordpress-classifieds-plugin' ) . '<br/><br/>' . __( 'IMPORTANT: Only change this setting when using a membership plugin with custom login pages or similar scenarios.', 'another-wordpress-classifieds-plugin' ) );
    }
}
