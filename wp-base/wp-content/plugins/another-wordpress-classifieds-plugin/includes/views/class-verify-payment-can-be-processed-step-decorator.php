<?php

class AWPCP_VerifyPaymentCanBeProcessedStepDecorator extends AWPCP_StepDecorator {

    public function before_get( $controller ) {
        $this->verify_payment_can_be_processed( $controller );
    }

    public function before_post( $controller ) {
        $this->verify_payment_can_be_processed( $controller );
    }

    private function verify_payment_can_be_processed( $controller ) {
        $transaction = $controller->get_transaction();

        if ( ! $this->payment_can_be_processed( $transaction ) ) {
            $message = __( "We can't process payments for this Payment Transaction at this time. Please contact the website administrator and provide the following transaction ID: %s", 'AWPCP');
            $message = sprintf( $message, $transaction->id );
            throw new AWPCP_Exception( $message );
        }
    }

    private function payment_can_be_processed( $transaction ) {
        return $transaction->is_doing_checkout() || $transaction->is_processing_payment();
    }
}
