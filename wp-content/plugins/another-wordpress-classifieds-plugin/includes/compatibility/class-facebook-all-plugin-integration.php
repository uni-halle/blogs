<?php

function awpcp_facebook_all_plugin_integration() {
    return new AWPCP_Facebook_All_Plugin_Integration( awpcp_request() );
}

class AWPCP_Facebook_All_Plugin_Integration {

    private $request;

    public function __construct( $request ) {
        $this->request = $request;
    }

    public function maybe_remove_userlogin_handler() {
        if ( ! function_exists( 'facebookall_make_userlogin' ) ) {
            return;
        }

        if ( $this->request->get( 'page' ) != 'awpcp-admin-settings' ) {
            return;
        }

        if ( $this->request->get( 'g' ) != 'facebook-settings' ) {
            return;
        }

        if ( $this->request->get( 'obtain_user_token' ) != 1 ) {
            return;
        }

        remove_action( 'init', 'facebookall_make_userlogin', 9 );
    }
}
