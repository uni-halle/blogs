<?php

function awpcp_send_listing_to_facebook_helper() {
    return new AWPCP_SendListingToFacebookHelper( AWPCP_Facebook::instance(), awpcp_send_to_facebook_helper(), awpcp_listings_collection(), awpcp_listings_metadata(), awpcp()->settings );
}

class AWPCP_SendListingToFacebookHelper {

    private $facebook_config;
    private $facebook_helper;
    private $listings_collection;
    private $listings_metadata;
    private $settings;

    public function __construct( $facebook_config, $facebook_helper, $listings_collection, $listings_metadata, $settings ) {
        $this->facebook_config = $facebook_config;
        $this->facebook_helper = $facebook_helper;
        $this->listings_collection = $listings_collection;
        $this->listings_metadata = $listings_metadata;
        $this->settings = $settings;
    }

    public function schedule_listing_if_necessary( $listing ) {
        if ( ! $this->settings->get_option( 'sends-listings-to-facebook-automatically', true ) ) {
            return;
        }

        if ( $listing->disabled ) {
            return;
        }

        $is_fb_page_configured = $this->facebook_config->is_page_set();
        $already_sent_to_a_fb_page = $this->listings_metadata->get( $listing->ad_id, 'sent-to-facebook' );

        if ( $is_fb_page_configured && ! $already_sent_to_a_fb_page ) {
            $this->schedule_send_to_facebook_action( $listing );
            return;
        }

        $is_fb_group_configured = $this->facebook_config->is_group_set();
        $already_sent_to_a_fb_group = $this->listings_metadata->get( $listing->ad_id, 'sent-to-facebook-group' );

        if ( $is_fb_group_configured && ! $already_sent_to_a_fb_group ) {
            $this->schedule_send_to_facebook_action( $listing );
            return;
        }
    }

    private function schedule_send_to_facebook_action( $listing ) {
        wp_schedule_single_event( time() + 10, 'awpcp-send-listing-to-facebook', array( $listing->ad_id, current_time( 'timestamp' ) ) );
    }

    public function send_listing_to_facebook( $listing_id ) {
        try {
            $listing = $this->listings_collection->get( $listing_id );
        } catch ( AWPCP_Exception $e ) {
            return;
        }

        try {
            $this->facebook_helper->send_listing_to_facebook_page( $listing );
        } catch ( AWPCP_Exception $e ) {
            // pass
        }

        try {
            $this->facebook_helper->send_listing_to_facebook_group( $listing );
        } catch ( AWPCP_Exception $e ) {
            // pass
        }

        $this->schedule_listing_if_necessary( $listing );
    }
}
