<?php

class AWPCP_PrepareTransactionForPaymentStepDecorator extends AWPCP_StepDecorator {

    private $checkout_step;
    private $payment_completed_step;

    public function __construct( $decorated, $payments, $payment_completed_step, $checkout_step ) {
        parent::__construct( $decorated );
        $this->payments = $payments;

        $this->payment_completed_step = $payment_completed_step;
        $this->checkout_step = $checkout_step;
    }

    public function after_post( $controller ) {
        $this->controller = $controller;
        $this->transaction = $this->controller->get_transaction();
        $this->prepare_transaction_for_payment();
    }

    private function prepare_transaction_for_payment() {
        $errors = array();

        if ( $this->transaction->payment_is_not_required() ) {
            $this->payments->set_transaction_status_to_payment_completed( $this->transaction, $errors );

            if ( $this->transaction->is_payment_completed() ) {
                $this->controller->set_next_step( $this->payment_completed_step );
            }
        } else {
            $this->payments->set_transaction_status_to_ready_to_checkout( $this->transaction, $errors );

            if ( $this->transaction->is_ready_to_checkout() ) {
                $this->controller->set_next_step( $this->checkout_step );
            }
        }

        if ( ! $this->transaction->is_payment_completed() && ! $this->transaction->is_ready_to_checkout() ) {
            throw new AWPCP_Exception( implode( ' ', $errors ) );
        }
    }
}
