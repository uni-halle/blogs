<?php

function awpcp_default_login_form_implementation() {
    return new AWPCP_Default_Login_Form_Implementation( awpcp()->settings );
}

class AWPCP_Default_Login_Form_Implementation {

    public function __construct( $settings ) {
        $this->settings = $settings;
    }

    public function render( $redirect, $message = null ) {
        $custom_registration_url = $this->settings->get_option( 'registrationurl' );

        if ( empty( $custom_registration_url ) ) {
            if ( function_exists( 'wp_registration_url' ) ) {
                $registration_url = wp_registration_url();
            } else {
                $registration_url = site_url( 'wp-login.php?action=register', 'login' );
            }
        } else {
            $registration_url = $custom_registration_url;
        }

        $show_register_link = !empty( $custom_registration_url ) || get_option( 'users_can_register' );

        $redirect_to = urlencode( add_query_arg( 'register', true, $redirect ) );
        $register_url = add_query_arg( array( 'redirect_to' => $redirect_to ), $registration_url );

        $redirect_to = urlencode( add_query_arg( 'reset', true, $redirect ) );
        $lost_password_url = add_query_arg( array( 'redirect_to' => $redirect_to ), wp_lostpassword_url() );

        ob_start();
            include( AWPCP_DIR . '/frontend/templates/login-form.tpl.php' );
            $form = ob_get_contents();
        ob_end_clean();

        return $form;
    }
}
