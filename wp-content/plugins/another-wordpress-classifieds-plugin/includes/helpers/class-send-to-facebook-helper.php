<?php

function awpcp_send_to_facebook_helper() {
    return new AWPCP_SendToFacebookHelper( AWPCP_Facebook::instance(), awpcp_listings_metadata(), awpcp_media_api() );
}

class AWPCP_SendToFacebookHelper {

    private $facebook_config;
    private $listings_metadata;
    private $media;

    public function __construct( $facebook_config, $listings_metadata, $media ) {
        $this->facebook_config = $facebook_config;
        $this->listings_metadata = $listings_metadata;
        $this->media = $media;
    }

    public function send_listing_to_facebook_page( $listing ) {
        $this->facebook_config->set_access_token( 'page_token' );

        if ( $this->listings_metadata->get( $listing->ad_id, 'sent-to-facebook' ) ) {
            throw new AWPCP_Exception( __( 'The Ad was already sent to Facebook Page.', 'AWPCP' ) );
        }

        if ( $listing->disabled ) {
            throw new AWPCP_Exception( __( "The Ad is currently disabled. If you share it, Facebook servers and users won't be able to access it.", 'AWPCP' ) );
        }

        $this->do_facebook_request( $listing,
                                    '/' . $this->facebook_config->get( 'page_id' ) . '/feed',
                                    'POST' );

        $this->listings_metadata->set( $listing->ad_id, 'sent-to-facebook', true );
    }

    private function do_facebook_request( $listing, $path, $method ) {
        $primary_image = $this->media->get_ad_primary_image( $listing );
        $primary_image_thumbnail_url = $primary_image ? $primary_image->get_url( 'primary' ) : '';

        $params = array( 'link' => url_showad( $listing->ad_id ),
                         'name' => $listing->get_title(),
                         'picture' =>  $primary_image_thumbnail_url );

        try {
            $response = $this->facebook_config->api_request( $path, $method, $params );
        } catch ( Exception $e ) {
            $message = __( "There was an error trying to contact Facebook servers: %s.", 'AWPCP' );
            $message = sprintf( $message, $e->getMessage() );
            throw new AWPCP_Exception( $message );
        }

        if ( ! $response || ! isset( $response->id ) ) {
            $message = __( 'Facebook API returned the following errors: %s.', 'AWPCP' );
            $message = sprintf( $message, $this->facebook_config->get_last_error()->message );
            throw new AWPCP_Exception( $message );
        }
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
        $this->facebook_config->set_access_token( 'user_token' );

        if ( $this->listings_metadata->get( $listing->ad_id, 'sent-to-facebook-group' ) ) {
            throw new AWPCP_Exception( __( 'The Ad was already sent to Facebook Group.', 'AWPCP' ) );
        }

        if ( $listing->disabled ) {
            throw new AWPCP_Exception( __( "The Ad is currently disabled. If you share it, Facebook servers and users won't be able to access it.", 'AWPCP' ) );
        }

        $this->do_facebook_request( $listing,
                                    '/' . $this->facebook_config->get( 'group_id' ) . '/feed',
                                    'POST' );

        $this->listings_metadata->set( $listing->ad_id, 'sent-to-facebook-group', true );
    }
}
