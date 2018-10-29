<?php

function awpcp_send_to_facebook_helper() {
    return new AWPCP_SendToFacebookHelper(
        AWPCP_Facebook::instance(),
        awpcp_facebook_integration(),
        awpcp_listings_collection(),
        awpcp_listings_metadata(),
        awpcp()->settings
    );
}

class AWPCP_SendToFacebookHelper {

    /**
     * @var Facebook
     */
    private $facebook;

    /**
     * @var FacebookIntegration
     */
    private $facebook_integration;

    /**
     * @var ListingsCollection
     */
    private $listings;

    private $listings_metadata;

    /**
     * @var Settings
     */
    private $settings;

    public function __construct( $facebook, $facebook_integration, $listings, $listings_metadata, $settings ) {
        $this->facebook             = $facebook;
        $this->facebook_integration = $facebook_integration;
        $this->listings             = $listings;
        $this->listings_metadata    = $listings_metadata;
        $this->settings             = $settings;
    }

    public function send_listing_to_facebook( $listing_id ) {
        try {
            $listing = $this->listings->get( $listing_id );
        } catch ( AWPCP_Exception $e ) {
            return;
        }

        try {
            $this->send_listing_to_facebook_page( $listing );
        } catch ( AWPCP_Exception $e ) {
            // pass
        }

        try {
            $this->send_listing_to_facebook_group( $listing );
        } catch ( AWPCP_Exception $e ) {
            // pass
        }

        $this->facebook_integration->maybe_schedelue_send_to_facebook_action( $listing );
    }

    public function send_listing_to_facebook_page( $listing ) {
        if ( $this->listings_metadata->get( $listing->ad_id, 'sent-to-facebook' ) ) {
            throw new AWPCP_Exception( __( 'The ad was already sent to a Facebook Page.', 'another-wordpress-classifieds-plugin' ) );
        }

        if ( $listing->disabled ) {
            throw new AWPCP_Exception( __( "The ad is currently disabled. If you share it, Facebook servers and users won't be able to access it.", 'another-wordpress-classifieds-plugin' ) );
        }

        $integration_method = $this->settings->get_option( 'facebook-integration-method' );
        $listing_sent       = false;

        if ( 'facebook-api' === $integration_method ) {
            $listing_sent = $this->send_listing_to_facebook_page_using_facebook_api( $listing );
        }

        if ( 'webhooks' === $integration_method ) {
            $listing_sent = $this->send_listing_to_facebook_page_using_webhook( $listing );
        }

        if ( $listing_sent ) {
            $this->listings_metadata->set( $listing->ad_id, 'sent-to-facebook', true );
        }

        return $listing_sent;
    }

    /**
     * @since 3.8.6
     */
    private function send_listing_to_facebook_page_using_facebook_api( $listing ) {
        $this->facebook->set_access_token( 'page_token' );

        if ( ! $this->facebook->is_page_set() ) {
            throw new AWPCP_Exception( 'There is no Facebook Page selected.' );
        }

        $this->do_facebook_request(
            $listing,
            '/' . $this->settings->get_option( 'facebook-page' ) . '/feed',
            'POST'
        );

        return true;
    }

    private function do_facebook_request( $listing, $path, $method ) {
        $params = array(
            'link' => url_showad( $listing->ad_id ),
        );

        try {
            $response = $this->facebook->api_request( $path, $method, $params );
        } catch ( Exception $e ) {
            $message = __( "There was an error trying to contact Facebook servers: %s.", 'another-wordpress-classifieds-plugin' );
            $message = sprintf( $message, $e->getMessage() );
            throw new AWPCP_Exception( $message );
        }
        if ( ! $response || ! isset( $response->id ) ) {
            $message = __( 'Facebook API returned the following errors: %s.', 'another-wordpress-classifieds-plugin' );
            $message = sprintf( $message, $this->facebook->get_last_error()->message );
            throw new AWPCP_Exception( $message );
        }
    }

    /**
     * @since 3.8.6
     */
    private function send_listing_to_facebook_page_using_webhook( $listing ) {
        $webhooks = $this->get_webohooks_for_facebook_page_integration( $listing );

        if ( empty( $webhooks ) ) {
            throw new AWPCP_Exception( 'There is no webhook configured to send ads to a Facebook Page.' );
        }

        return $this->process_webhooks( $webhooks );
    }

    /**
     * @since 3.8.6
     */
    private function get_webohooks_for_facebook_page_integration( $listing ) {
        $zapier_webhook      = $this->settings->get_option( 'zapier-webhook-for-facebook-page-integration' );
        $ifttt_webhook_base  = $this->settings->get_option( 'ifttt-webhook-base-url-for-facebook-page-integration' );
        $ifttt_webhook_event = $this->settings->get_option( 'ifttt-webhook-event-name-for-facebook-page-integration' );
        $ifttt_webhook       = $this->build_ifttt_webhook_url( $ifttt_webhook_base, $ifttt_webhook_event );
        $properties          = $this->get_listing_properties( $listing );
        $webhooks            = array();

        if ( $zapier_webhook ) {
            $webhooks['zapier'] = array(
                'url'  => $zapier_webhook,
                'data' => array(
                    'url'         => $properties['url'],
                    'title'       => $properties['title'],
                    'description' => $properties['description'],
                ),
            );
        }

        if ( $ifttt_webhook ) {
            $webhooks['ifttt'] = array(
                'url'  => $ifttt_webhook,
                'data' => array(
                    'value1' => $properties['url'],
                    'value2' => $properties['title'],
                    'value3' => $properties['description'],
                ),
            );
        }

        return $webhooks;
    }

    /**
     * @since 3.8.6
     */
    private function build_ifttt_webhook_url( $base_url, $event_name ) {
        if ( empty( $base_url ) || empty( $event_name ) ) {
            return false;
        }

        if ( ! preg_match( '/' . preg_quote( 'https://maker.ifttt.com/use/', '/' ) . '(\w+)/', $base_url, $matches ) ) {
            return false;
        }

        return "https://maker.ifttt.com/trigger/$event_name/with/key/{$matches[1]}";
    }

    /**
     * @since 3.8.6
     */
    private function get_listing_properties( $listing ) {
        $properties = awpcp_get_ad_share_info( $listing->ad_id );

        return array(
            'url'         => $properties['url'],
            'title'       => $properties['title'],
            'description' => htmlspecialchars( $properties['description'], ENT_QUOTES, get_bloginfo('charset') ),
        );
    }

    /**
     * @since 3.8.6
     */
    private function process_webhooks( $webhooks ) {
        $webhook_sent = false;

        foreach ( $webhooks as $webhook ) {
            $params = array(
                'headers' => array(
                    'content-type' => 'application/json; charset=' . get_bloginfo( 'charset' ),
                ),
                'body' => wp_json_encode( $webhook['data'] ),
            );

            $response = wp_remote_post( $webhook['url'], $params );

            if ( 200 === intval( wp_remote_retrieve_response_code( $response ) ) ) {
                $webhook_sent = true;
            }
        }

        return $webhook_sent;
    }

    /**
     * Users should choose Friends (or something more public), not Only Me, when the application
     * request the permission, to avoid error:
     *
     * OAuthException: (#200) Insufficient permission to post to target on behalf of the viewer.
     *
     * http://stackoverflow.com/a/19653226/201354
     */
    public function send_listing_to_facebook_group( $listing ) {
        if ( $this->listings_metadata->get( $listing->ad_id, 'sent-to-facebook-group' ) ) {
            throw new AWPCP_Exception( __( 'The ad was already sent to a Facebook Group.', 'another-wordpress-classifieds-plugin' ) );
        }

        if ( $listing->disabled ) {
            throw new AWPCP_Exception( __( "The ad is currently disabled. If you share it, Facebook servers and users won't be able to access it.", 'another-wordpress-classifieds-plugin' ) );
        }

        $integration_method = $this->settings->get_option( 'facebook-integration-method' );
        $listing_sent       = false;

        if ( 'facebook-api' === $integration_method ) {
            $listing_sent = $this->send_listing_to_facebook_group_using_facebook_api( $listing );
        }

        if ( $listing_sent ) {
            $this->listings_metadata->set( $listing->ad_id, 'sent-to-facebook-group', true );
        }

        return $listing_sent;
    }

    /**
     * @since 3.8.6
     */
    private function send_listing_to_facebook_group_using_facebook_api( $listing ) {
        $this->facebook->set_access_token( 'user_token' );

        if ( ! $this->facebook->is_group_set() ) {
            throw new AWPCP_Exception( 'There is no Facebook Group selected.' );
        }

        $this->do_facebook_request(
            $listing,
            '/' . $this->settings->get_option( 'facebook-group' ) . '/feed',
            'POST'
        );

        return true;
    }

    /**
     * Users should choose Friends (or something more public), not Only Me, when the application
     * request the permission, to avoid error:
     *
     * OAuthException: (#200) Insufficient permission to post to target on behalf of the viewer.
     *
     * http://stackoverflow.com/a/19653226/201354
     *
     * @since 3.8.6
     */
    private function send_listing_to_facebook_group_using_webhook( $listing ) {
        $webhooks = $this->get_webhooks_for_facebook_group_integration( $listing );

        if ( empty( $webhooks ) ) {
            throw new AWPCP_Exception( 'There is no webhook configured to send ads to a Facebook Group.' );
        }

        return $this->process_webhooks( $webhooks );
    }

    /**
     * @since 3.8.6
     */
    private function get_webhooks_for_facebook_group_integration( $listing ) {
        return [];
    }
}
