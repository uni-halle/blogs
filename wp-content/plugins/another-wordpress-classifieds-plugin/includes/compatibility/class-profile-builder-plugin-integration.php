<?php

function awpcp_profile_builder_plugin_integration() {
    return new AWPCP_Profile_Builder_Plugin_Integration();
}

class AWPCP_Profile_Builder_Plugin_Integration {

    public function get_login_form_implementation( $implementation ) {
        if ( function_exists( 'wppb_custom_redirect_url' ) ) {
            return new AWPCP_Profile_Builder_Login_Form_Implementation();
        }

        return $implementation;
    }
}
