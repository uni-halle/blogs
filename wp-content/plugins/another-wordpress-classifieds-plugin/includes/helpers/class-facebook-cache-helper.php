<?php

function awpcp_facebook_cache_helper() {
    return new AWPCP_FacebookCacheHelper(
        awpcp_facebook_integration(),
        awpcp_listings_collection(),
        awpcp()->settings
    );
}

class AWPCP_FacebookCacheHelper {

    /**
     * @var FacebookIntegration
     */
    private $facebook_integration;

    /**
     * @var ListingsCollection
     */
    private $ads;

    /**
     * @var Settings
     */
    private $settings;

    public function __construct( $facebook_integration, $ads, $settings ) {
        $this->facebook_integration = $facebook_integration;
        $this->ads                  = $ads;
        $this->settings             = $settings;
    }

    public function handle_clear_cache_event_hook( $ad_id ) {
        try {
            $ad = $this->ads->get( $ad_id );
        } catch ( AWPCP_Exception $e ) {
            return;
        }

        $this->clear_ad_cache( $ad );
    }

    private function clear_ad_cache( $ad ) {
        if ( $ad->disabled ) {
            return;
        }

        $user_token = $this->settings->get_option( 'facebook-user-access-token' );

        if ( ! $user_token ) {
            return;
        }

        $args = array(
            'timeout' => 30,
            'body' => array(
                'id' => url_showad( $ad->ad_id ),
                'scrape' => true,
                'access_token' => $user_token,
            ),
        );

        $response = wp_remote_post( 'https://graph.facebook.com/', $args  );

        if ( $this->is_successful_response( $response ) ) {
            do_action( 'awpcp-listing-facebook-cache-cleared', $ad );
        } else {
            $this->facebook_integration->schedule_clear_cache_action( $ad, 5 * MINUTE_IN_SECONDS );
        }
    }

    private function is_successful_response( $response ) {
        if ( is_wp_error( $response ) || ! is_array( $response ) ) {
            return false;
        } else if ( ! isset( $response['response']['code'] ) ) {
            return false;
        } else if ( $response['response']['code'] != 200 ) {
            return false;
        }

        $listing_info = json_decode( $response['body'] );

        if ( ! isset( $listing_info->type ) || $listing_info->type != 'article' ) {
            return false;
        } else if ( empty( $listing_info->title ) ) {
            return false;
        } else if ( ! isset( $listing_info->description ) ) {
            return false;
        }

        return true;
    }
}
