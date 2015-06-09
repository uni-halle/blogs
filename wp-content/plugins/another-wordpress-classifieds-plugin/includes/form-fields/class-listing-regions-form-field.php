<?php

function awpcp_listing_regions_form_field( $slug ) {
    return new AWPCP_ListingRegionsFormField( $slug, awpcp_payments_api(), awpcp()->settings );
}

/**
 * TODO: what if that field shouldn't be shown?
 */
class AWPCP_ListingRegionsFormField extends AWPCP_FormField {

    private $payments;
    private $settings;

    public function __construct( $slug, $payments, $settings ) {
        parent::__construct( $slug );
        $this->payments = $payments;
        $this->settings = $settings;
    }

    /**
     * Not used, but implementation is required by AWPCP_FormField.
     */
    public function get_name() {
        return 'Regions';
    }

    protected function is_read_only() {
        if ( awpcp_current_user_is_moderator() ) {
            return false;
        }

        if ( $this->settings->get_option( 'allow-regions-modification' ) ) {
            return false;
        }

        // ugly hack to figure out if we are editing or creating a list...
        if ( $transaction = $this->payments->get_transaction() ) {
            return false;
        }

        return true;
    }

    public function render( $value, $errors, $listing, $context ) {
        $options = array(
            'showTextField' => true,
            'maxRegions' => $this->get_allowed_regions_for_listing( $listing ),
            'disabled' => $this->is_read_only(),
        );

        $region_selector = awpcp_multiple_region_selector( $value, $options );

        return $region_selector->render( 'details', array(), $errors );
    }

    private function get_allowed_regions_for_listing( $listing ) {
        if ( is_a( $listing, 'AWPCP_Ad' ) ) {
            $payment_term = $listing->get_payment_term();
        } else if ( $transaction = $this->payments->get_transaction() ) {
            $payment_term = $this->payments->get_transaction_payment_term( $transaction );
        }

        if ( ! is_null( $payment_term ) ) {
            $allowed_regions = $payment_term->get_regions_allowed();
        } else {
            $allowed_regions = 0;
        }

        return $allowed_regions;
    }
}
