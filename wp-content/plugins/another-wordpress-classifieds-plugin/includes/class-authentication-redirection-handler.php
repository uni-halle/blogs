<?php

function awpcp_authentication_redirection_handler() {
    return new AWPCP_Authentication_Redirection_Handler(
        awpcp_query(), awpcp()->settings
    );
}

class AWPCP_Authentication_Redirection_Handler {

    private $query;
    private $settings;

    public function __construct( $query, $settings ) {
        $this->query = $query;
        $this->settings = $settings;
    }

    public function maybe_redirect() {
        $login_url = $this->settings->get_option( 'login-url' );

        if ( empty( $login_url ) || is_user_logged_in() ) {
            return;
        }

        if ( $this->query->is_post_listings_page() ) {
            $page_requires_authentication = $this->post_listing_page_requires_authentication();
        } else if ( $this->query->is_reply_to_listing_page() ) {
            $page_requires_authentication = $this->reply_to_listing_page_requires_autentication();
        } else {
            $page_requires_authentication = false;
        }

        if ( apply_filters( 'awpcp-page-requires-authentication', $page_requires_authentication ) ) {
            return $this->redirect_to_login_page( $login_url );
        }
    }

    private function post_listing_page_requires_authentication() {
        return $this->settings->get_option( 'requireuserregistration' );
    }

    private function reply_to_listing_page_requires_autentication() {
        return $this->settings->get_option( 'reply-to-ad-requires-registration' );
    }

    private function redirect_to_login_page( $login_url ) {
        wp_redirect( add_query_arg( 'redirect_to', urlencode( awpcp_current_url() ), $login_url ) );
        exit();
    }
}
