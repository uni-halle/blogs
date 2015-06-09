<?php

class AWPCP_SecureURLRedirectionHandler {

    public function dispatch() {
        if (  ! is_ssl() && get_awpcp_option( 'force-secure-urls' ) ) {
            $this->force_secure_urls();
        }
    }

    private function force_secure_urls() {
        $force_secure_url = false;

        $pages = array( 'place-ad-page-name', 'subscriptions-page-name', 'renew-ad-page-name' );

        foreach ( $pages as $page ) {
            $page_id = awpcp_get_page_id_by_ref( $page );
            if ( $page_id && is_page( $page_id ) ) {
                $force_secure_url = true;
                break;
            }
        }

        if ( ! $force_secure_url ) {
            global $post;

            $shortcodes = array( 'AWPCPPLACEAD', 'AWPCP-BUY-SUBSCRIPTION', 'AWPCP-RENEW-AD', 'AWPCPBUYCREDITS' );
            $regexp = '/\[' . join( '\]|\[', $shortcodes ) . '\]/';

            if ( preg_match( $regexp, $post->post_content ) ) {
                $force_secure_url = true;
            }
        }

        if ( $force_secure_url && wp_redirect( set_url_scheme( awpcp_current_url(), 'https' ) ) ) {
            exit();
        }
    }
}
