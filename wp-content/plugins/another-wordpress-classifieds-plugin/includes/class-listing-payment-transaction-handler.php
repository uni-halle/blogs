<?php

function awpcp_listing_payment_transaction_handler() {
    return new AWPCP_ListingPaymentTransactionHandler( awpcp_listings_collection(), awpcp_listings_api() );
}

class AWPCP_ListingPaymentTransactionHandler {

    private $listings;
    private $listings_logic;

    public function __construct( $listings, $listings_logic ) {
        $this->listings = $listings;
        $this->listings_logic = $listings_logic;
    }

    public function transaction_status_updated( $transaction, $new_status ) {
        $this->process_payment_transaction( $transaction );
    }

    public function process_payment_transaction( $transaction ) {
        if ( $transaction->is_payment_completed() || $transaction->is_completed() ) {
            $this->process_completed_transaction( $transaction );
        }
    }

    public function process_completed_transaction( $transaction ) {
        if ( ! $transaction->get( 'ad-id' ) ) {
            return;
        }

        $listing = $this->listings->find_by_id( $transaction->get( 'ad-id' ) );
        $listing_has_accepted_payment_status = $this->listing_has_accepted_payment_status( $listing );
        $trigger_actions = $transaction->get( 'ad-consolidated-at' ) ? true : false;

        $this->update_listing_payment_information( $listing, $transaction );

        if ( $transaction->was_payment_successful() ) {
            if ( ! $listing_has_accepted_payment_status ) {
                $this->listings_logic->update_listing_verified_status( $listing, $transaction );
                $this->maybe_enable_listing( $listing, $transaction, $trigger_actions );
            }
        } else if ( $transaction->did_payment_failed() && $listing_has_accepted_payment_status ) {
            $listing->disable( $trigger_actions );
        }

        if ( ! $transaction->get( 'ad-consolidated-at' ) ) {
            $this->listings_logic->consolidate_new_ad( $listing, $transaction );
        }

        $listing->save();
    }

    private function listing_has_accepted_payment_status( $listing ) {
        // TODO: how to remove dependency on AWPCP_Payment_Transaction?
        if ( $listing->payment_status === AWPCP_Payment_Transaction::PAYMENT_STATUS_PENDING ) {
            return true;
        } else if ( $listing->payment_status === AWPCP_Payment_Transaction::PAYMENT_STATUS_COMPLETED ) {
            return true;
        } else if ( $listing->payment_status === AWPCP_Payment_Transaction::PAYMENT_STATUS_NOT_REQUIRED ) {
            return true;
        }
        return false;
    }

    private function update_listing_payment_information( $listing, $transaction ) {
        $listing->payment_status = $transaction->payment_status;
        $listing->payment_gateway = $transaction->payment_gateway;
        $listing->payer_email = $transaction->payer_email;
    }

    private function maybe_enable_listing( $listing, $transaction, $trigger_actions ) {
        if ( $listing->disabled && $this->should_enable_listing( $listing, $transaction ) ) {
            $should_approve_listing_images = get_awpcp_option( 'imagesapprove' ) ? false : true;
            $listing->enable( $should_approve_listing_images, $trigger_actions );
        }
    }

    private function should_enable_listing( $listing, $transaction ) {
        return awpcp_calculate_ad_disabled_state( null, $transaction ) ? false : true;
    }
}
