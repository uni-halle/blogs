<?php

class AWPCP_SetTransactionStatusToCompletedStepDecorator extends AWPCP_StepDecorator {

    private $payments;
    private $transaction;

    public function __construct( $decorated, $payments ) {
        parent::__construct( $decorated );
        $this->payments = $payments;
    }

    public function before_get( $controller ) {
        $this->set_transaction_status_to_completed_if_necessary( $controller );
    }

    public function before_post( $controller ) {
        $this->set_transaction_status_to_completed_if_necessary( $controller );
    }

    private function set_transaction_status_to_completed_if_necessary( $controller ) {
        $this->transaction = $controller->get_transaction();

        if ( ! $this->transaction->is_completed() ) {
            $this->set_transaction_status_to_completed();
        }
    }

    private function set_transaction_status_to_completed() {
        $this->payments->set_transaction_status_to_completed( $this->transaction );

        if ( ! $this->transaction->is_completed() ) {
            debugp( 'Ouch! Completed Step Decorator.' );
            throw new AWPCP_Exception( implode( ' ', $errors ) );
        }
    }
}
