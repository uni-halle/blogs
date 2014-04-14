<?php

class AWPCP_VerifyTransactionExistsStepDecorator extends AWPCP_StepDecorator {

    public function before_get( $controller ) {
        $this->verify_transaction_exists( $controller );
    }

    public function before_post( $controller ) {
        $this->verify_transaction_exists( $controller );
    }

    private function verify_transaction_exists( $controller ) {
        $transaction = $controller->get_transaction();

        if ( is_null( $transaction ) ) {
            $message = __( 'There was an error processing your Payment Request. Please try again or contact an Administrator.', 'AWPCP' );
            throw new AWPCP_Exception( $message );
        }
    }
}
