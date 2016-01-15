<?php

function awpcp_secure_url_redirection_handler() {
    return new AWPCP_SecureURLRedirectionHandler( awpcp_query() );
}

class AWPCP_SecureURLRedirectionHandler {

    private $query;

    public function __construct( $query ) {
        $this->query = $query;
    }

    public function dispatch() {
        if (  is_ssl() || ! get_awpcp_option( 'force-secure-urls' ) ) {
            return;
        }

        if ( ! $this->query->is_page_that_accepts_payments() ) {
            return;
        }

        if ( wp_redirect( set_url_scheme( awpcp_current_url(), 'https' ) ) ) {
            exit();
        }
    }
}
