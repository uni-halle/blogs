<?php

/**
 * @since 3.6
 */
function awpcp_query() {
    return new AWPCP_Query();
}

/**
 * @since 3.6
 */
class AWPCP_Query {

    public function is_post_listings_page() {
        return $this->is_page_that_has_shortcode( 'AWPCPPLACEAD' );
    }

    public function is_edit_listing_page() {
        return $this->is_page_that_has_shortcode( 'AWPCPEDITAD' );
    }

    public function is_single_listing_page() {
        return apply_filters( 'awpcp-is-single-listing-page', $this->is_page_that_has_shortcode( 'AWPCPSHOWAD' ) );
    }

    public function is_reply_to_listing_page() {
        return $this->is_page_that_has_shortcode( 'AWPCPREPLYTOAD' );
    }

    public function is_browse_listings_page() {
        return $this->is_page_that_has_shortcode( 'AWPCPBROWSEADS' );
    }

    public function is_page_that_has_shortcode( $shortcode ) {
        global $wp_the_query;

        if ( ! $wp_the_query || ! $wp_the_query->is_page() ) {
            return false;
        }

        $page = $wp_the_query->get_queried_object();

        if ( ! $page || ! has_shortcode( $page->post_content, $shortcode ) ) {
            return false;
        }

        return true;
    }

    public function is_browse_categories_page() {
        return $this->is_browse_listings_page();
    }

    public function is_page_that_accepts_payments() {
        $shortcodes_that_accept_payments = array( 'AWPCPPLACEAD', 'AWPCP-BUY-SUBSCRIPTION', 'AWPCP-RENEW-AD', 'AWPCPBUYCREDITS' );

        foreach ( $shortcodes_that_accept_payments as $shortcode ) {
            if ( $this->is_page_that_has_shortcode( $shortcode ) ) {
                return true;
            }
        }

        return false;
    }
}
