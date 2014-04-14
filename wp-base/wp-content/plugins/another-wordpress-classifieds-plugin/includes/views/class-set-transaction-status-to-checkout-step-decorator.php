<?php

class AWPCP_SetTransactionStatusToCheckoutStepDecorator extends AWPCP_StepDecorator {

    private $payments;

    public function __construct( $decorated, $payments ) {
        parent::__construct( $decorated );
        $this->payments = $payments;
    }

    public function before_get( $controller ) {
        $this->handle( $controller );
    }

    public function before_post( $controller ) {
        $this->handle( $controller );
    }

    private function handle( $controller ) {
        $this->transaction = $controller->get_transaction();

        if ( $this->transaction->is_ready_to_checkout() ) {
            $this->set_transaction_status_to_checkout();
        }
    }

    private function set_transaction_status_to_checkout() {
        $errors = array();

        $this->payments->set_transaction_status_to_checkout( $this->transaction, $errors );

        if ( ! $this->transaction->is_doing_checkout() ) {
            throw new AWPCP_Exception( implode( ' ', $errors ) );
        }
    }
}
