<?php

class AWPCP_SetTransactionStatusToOpenStepDecorator extends AWPCP_StepDecorator {

    private $payments;
    private $transaction;

    public function __construct( $decorated, $payments ) {
        parent::__construct( $decorated );
        $this->payments = $payments;
    }

    public function before_post( $controller ) {
        $this->transaction = $controller->get_or_create_transaction();

        if ( $this->transaction->is_new() ) {
            $this->set_transaction_status_to_open();
        }
    }

    private function set_transaction_status_to_open() {
        $errors = array();

        $this->payments->set_transaction_status_to_open( $this->transaction, $errors );

        if ( ! $this->transaction->is_open() ) {
            throw new AWPCP_Exception( implode( ' ', $errors ) );
        }
    }
}
