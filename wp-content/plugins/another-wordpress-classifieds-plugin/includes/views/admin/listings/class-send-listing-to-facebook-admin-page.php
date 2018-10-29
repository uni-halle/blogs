<?php

function awpcp_send_listing_to_facebook_admin_page() {
    return new AWPCP_SendListingToFacebookAdminPage(
        awpcp_listings_collection(),
        awpcp_facebook_integration(),
        awpcp_send_to_facebook_helper(),
        awpcp_request()
    );
}

class AWPCP_SendListingToFacebookAdminPage extends AWPCP_ListingActionAdminPage {

    private $facebook_integration;
    private $facebook_helper;

    public $successful = array( 'page' => 0, 'group' => 0 );
    public $failed = array( 'page' => 0, 'group' => 0 );
    public $errors = array();

    public function __construct( $listings, $facebook_integration, $facebook_helper, $request ) {
        parent::__construct( $listings, $request );

        $this->facebook_integration = $facebook_integration;
        $this->facebook_helper      = $facebook_helper;
    }

    public function dispatch() {
        $destinations = array();

        if ( $this->facebook_integration->is_facebook_page_integration_configured() ) {
            $destinations['page'] = __( 'Facebook Page', 'another-wordpress-classifieds-plugin' );
        }

        if ( $this->facebook_integration->is_facebook_group_integration_configured() ) {
            $destinations['group'] = __( 'Facebook Group', 'another-wordpress-classifieds-plugin' );
        }

        if ( empty( $destinations ) ) {
            $this->errors[] = __( "AWPCP could not post to Facebook because you haven't selected a Page or a Group.", 'another-wordpress-classifieds-plugin' );
        } else {
            foreach ( $this->get_selected_listings() as $listing ) {
                $this->try_to_send_listing_to_facebook( $listing, $destinations );
            }
        }

        $this->show_results();
    }

    private function try_to_send_listing_to_facebook( $listing, $destinations ) {
        if ( $listing->disabled ) {
            $message = __( "The Ad %s was not sent to Facebook because is currently disabled. If you share it, Facebook servers and users won't be able to access it.", 'another-wordpress-classifieds-plugin' );
            $this->errors[] = sprintf( $message, '<strong>' . $listing->get_title() . '</strong>' );
            return;
        }

        foreach ( $destinations as $destination => $label ) {
            try {
                call_user_func( array( $this, 'send_listing_to_facebook_' . $destination ), $listing );
            } catch ( AWPCP_Exception $exception ) {
                $message = _x( 'There was an error trying to send the listing %s to a %s.', '... <listing-title> to a <Facebook Group/Page>', 'another-wordpress-classifieds-plugin' );
                $message = sprintf( $message, '<strong>' . $listing->get_title() . '</strong>', $label );

                $this->errors[] = $message . ' ' . $exception->format_errors();
                $this->failed[ $destination ] = $this->failed[ $destination ] + 1;
            }
        }
    }

    public function send_listing_to_facebook_page( $listing ) {
        if ( $this->facebook_helper->send_listing_to_facebook_page( $listing ) ) {
            $this->successful['page'] = $this->successful['page'] + 1;
        }
    }

    public function send_listing_to_facebook_group( $listing ) {
        if ( $this->facebook_helper->send_listing_to_facebook_group( $listing ) ) {
            $this->successful['group'] = $this->successful['group'] + 1;
        }
    }

    private function show_results() {
        $listings_processed = array_sum( $this->successful );
        $listings_failed = array_sum( $this->failed );

        if ( ( $listings_processed + $listings_failed ) == 0 ) {
            awpcp_flash( __( 'No Ads were selected', 'another-wordpress-classifieds-plugin' ), 'error' );
        } else {
            $this->show_send_to_facebook_page_results();
            $this->show_send_to_facebook_group_results();
        }

        if ( $listings_processed == 0 && $listings_failed > 0 && ! empty( $this->errors ) ) {
            $link = '<a href="' . admin_url( 'admin.php?page=awpcp-admin-settings&g=facebook-settings' ) . '">';
            $message = __( 'There were errors trying to Send Ads to Facebook, perhaps your credentials are invalid or have expired. Please check your <a>settings</a>. If your token expired, please try to get a new access token from Facebook using the link in step 2 of the settings.', 'another-wordpress-classifieds-plugin' );
            $this->errors[] = str_replace( '<a>', $link, $message );
        }

        foreach ( $this->errors as $error ) {
            awpcp_flash( $error, 'error' );
        }
    }

    private function show_send_to_facebook_page_results() {
        $success_message = _n( '%d Ad was sent to a Facebook Page', '%d Ads were sent to a Facebook Page', $this->successful['page'], 'another-wordpress-classifieds-plugin' );
        $success_message = sprintf( $success_message, $this->successful['page'] );
        $error_message = sprintf( __('there was an error trying to send %d Ads to a Facebook Page', 'another-wordpress-classifieds-plugin'), $this->failed['page'] );

        $this->show_bulk_operation_result_message( $this->successful['page'], $this->failed['page'], $success_message, $error_message );
    }

    private function show_send_to_facebook_group_results() {
        $success_message = _n( '%d Ad was sent to a Facebook Group', '%d Ads were sent to a Facebook Group', $this->successful['group'], 'another-wordpress-classifieds-plugin' );
        $success_message = sprintf( $success_message, $this->successful['group'] );
        $error_message = sprintf( __('there was an error trying to send %d Ads to a Facebook Group', 'another-wordpress-classifieds-plugin'), $this->failed['group'] );

        $this->show_bulk_operation_result_message( $this->successful['group'], $this->failed['group'], $success_message, $error_message );
    }
}
