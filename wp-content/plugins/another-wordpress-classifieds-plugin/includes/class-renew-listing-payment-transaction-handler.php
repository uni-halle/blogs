<?php

function awpcp_renew_listing_payment_transaction_handler() {
    return new AWPCP_Renew_Listing_Payment_Transaction_Handler( awpcp_listings_collection(), awpcp_listings_api() );
}

class AWPCP_Renew_Listing_Payment_Transaction_Handler {

    private $listings;
    private $listings_logic;

    public function __construct( $listings, $listings_logic ) {
        $this->listings = $listings;
        $this->listings_logic = $listings_logic;
    }

    public function process_payment_transaction( $transaction ) {
        if ( $transaction->is_payment_completed() ) {
            $this->process_transaction( $transaction );
        }
    }

    private function process_transaction( $transaction ) {
        if ( strcmp( $transaction->get( 'context' ), 'renew-ad' ) !== 0 ) {
            return;
        }

        $listing_id = $transaction->get( 'ad-id' );

        if ( ! $listing_id ) {
            return;
        }

        if ( ! $transaction->was_payment_successful() ) {
            return;
        }

        if ( $transaction->get( 'listing-renewed-on' ) ) {
            return;
        }

        $listing = $this->listings->find_by_id( $listing_id );

        if ( ! $listing ) {
            return;
        }

        $payment_term = $listing->get_payment_term();

        if ( AWPCP_FeeType::TYPE != $payment_term->type ) {
            return;
        }

        $listing->renew();
        $listing->save();

        $transaction->set( 'listing-renewed-on', current_time( 'mysql' ) );
        $transaction->save();

        awpcp_send_ad_renewed_email( $listing );

        // MOVE inside Ad::renew() ?
        do_action( 'awpcp-renew-ad', $listing->ad_id, $transaction );
    }
}
