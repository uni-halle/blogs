<?php

function awpcp_wp_members_plugin_integration() {
    return new AWPCP_WP_Members_Plugin_Integration();
}

class AWPCP_WP_Members_Plugin_Integration {

    public function get_login_form_implementation( $implementation ) {
        if ( defined( 'WPMEM_VERSION' ) && version_compare( WPMEM_VERSION, '3.0', '>=' ) ) {
            return new AWPCP_WP_Members_Login_Form_Implementation();
        }

        return $implementation;
    }
}
