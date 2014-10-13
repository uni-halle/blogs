<?php

function awpcp_fee_payment_terms_notices() {
    return new AWPCP_FeePaymentTermsNotices( awpcp()->settings, awpcp_payments_api() );
}

// AWPCP_MissingFeePaymentTermsNotification?
class AWPCP_FeePaymentTermsNotices {

    private $settings;
    private $payments;

    public function __construct( $settings, $payments ) {
        $this->settings = $settings;
        $this->payments = $payments;
    }

    public function dispatch() {
        if ( $this->settings->get_option( 'freepay' ) && $this->no_payment_terms_defined() ) {
            return $this->render_notice();
        }
    }

    private function no_payment_terms_defined() {
        return count( awpcp_flatten_array( $this->payments->get_payment_terms() ) ) === 0;
    }

    private function render_notice() {
        $message = __( "You have payments enabled, but there are no payment terms defined. Users won't be able to post Ads. Please <fee-section-link>add payment terms</a> or <payments-settings-link>configure the website as a free board</a>.", 'AWPCP' );
        $message = str_replace( '<fee-section-link>' , sprintf( '<a href="%s">', awpcp_get_admin_fees_url() ), $message );
        $message = str_replace( '<payments-settings-link>' , sprintf( '<a href="%s">', awpcp_get_admin_settings_url( 'payment-settings' ) ), $message );
        echo awpcp_print_error( $message );
    }
}
