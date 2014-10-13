<?php

function awpcp_renew_listings_admin_page() {
    return new AWPCP_RenewListingsAdminPage( awpcp_listings_collection(), awpcp_payments_api(), awpcp_request() );
}

class AWPCP_RenewListingsAdminPage extends AWPCP_ListingActionAdminPage {

    private $payments;

    public $successful = 0;
    public $failed = 0;
    public $errors = array();

    public function __construct( $listings, $payments, $request ) {
        parent::__construct( $listings, $request );

        $this->payments = $payments;
    }

    public function dispatch() {
        foreach ( $this->get_selected_listings() as $listing ) {
            $this->try_to_renew_listing( $listing );
        }

        $this->show_results();
    }

    private function try_to_renew_listing( $listing ) {
        try {
            $this->renew_listing( $listing );
        } catch ( AWPCP_Exception $e ) {
            $message = __( 'There was an error trying to renew Ad %s.', 'AWPCP' );
            $message = sprintf( $message, '<strong>' . $listing->get_title() . '</strong>' );

            $this->errors[] = $message . ' ' . $e->format_errors();
            $this->failed = $this->failed + 1;
        }
    }

    private function renew_listing( $listing ) {
        if ( ! $listing->has_expired() && ! $listing->is_about_to_expire() ) {
            throw new AWPCP_Exception( __( "The Ad hasn't expired yet and is not about to expire.", 'AWPCP' ) );
        }

        $term = $this->payments->get_ad_payment_term( $listing );

        if ( ! is_object( $term ) ) {
            throw new AWPCP_Exception( __( "We couldn't find a valid payment term associated with this Ad.", 'AWPCP' ) );
        }

        if ( ! $term->ad_can_be_renewed( $listing ) ) {
            throw new AWPCP_Exception( $term->ad_cannot_be_renewed_error( $listing ) );
        }

        $listing->renew();
        $listing->save();

        awpcp_send_ad_renewed_email( $listing );
        $this->successful = $this->successful + 1;

        // MOVE inside Ad::renew() ?
        do_action( 'awpcp-renew-ad', $listing->ad_id, null );
    }

    private function show_results() {
        if ( $this->successful == 0 && $this->failed == 0 ) {
            awpcp_flash( __( 'No Ads were selected', 'AWPCP' ), 'error' );
        } else {
            $success_message = _n( '%d Ad was renewed', '%d Ads were renewed', $this->successful, 'AWPCP' );
            $success_message = sprintf( $success_message, $this->successful );
            $error_message = sprintf( __('there was an error trying to renew %d Ads', 'AWPCP'), $this->failed );

            $this->show_bulk_operation_result_message( $this->successful, $this->failed, $success_message, $error_message );
        }

        foreach ( $this->errors as $error ) {
            awpcp_flash( $error, 'error' );
        }
    }
}
