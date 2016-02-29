<?php

function awpcp_profile_builder_login_form_implementation() {
    return new AWPCP_Profile_Builder_Login_Form_Implementation();
}

class AWPCP_Profile_Builder_Login_Form_Implementation {

    public function render( $redirect_url, $message = null ) {
        $registration_url = wppb_custom_redirect_url( 'register' );
        $lost_password_url = wppb_custom_redirect_url( 'lostpassword' );

        $attributes = array();

        if ( ! empty( $registration_url ) ) {
            $attributes[] = 'register_url="' . $registration_url . '"';
        }

        if ( ! empty( $lost_password_url ) ) {
            $attributes[] = 'lostpassword_url="' . $lost_password_url . '"';
        }

        if ( ! empty( $redirect_url ) ) {
            $attributes[] = 'redirect_url="' . $redirect_url . '"';
        }

        $rendered_message = $message ? awpcp_print_message( $message ) : '';
        $rendered_attributes = implode( ' ', $attributes );

        return do_shortcode( sprintf( '%s [wppb-login %s]', $rendered_message, $rendered_attributes ) );
    }
}
