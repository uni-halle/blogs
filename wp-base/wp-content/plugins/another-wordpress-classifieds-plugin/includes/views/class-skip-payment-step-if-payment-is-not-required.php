<?php

/**
 * @since 3.2.1
 */
class AWPCP_SkipPaymentStepIfPaymentIsNotRequiredStepDecorator extends AWPCP_StepDecorator {

    private $payments;

    public function __construct( $decorated, $payments ) {
        parent::__construct( $decorated );
        $this->payments = $payments;
    }

    public function before_get( $controller ) {
        $this->skip_payment_step_if_payment_is_not_required( $controller );
    }

    public function before_post( $controller ) {
        $this->skip_payment_step_if_payment_is_not_required( $controller );
    }

    private function skip_payment_step_if_payment_is_not_required( $controller ) {
        $this->controller = $controller;
        $this->transaction = $controller->get_transaction();

        if ( $this->transaction->is_doing_checkout() && $this->transaction->payment_is_not_required() ) {
            $this->skip_payment_step();
        }
    }

    private function skip_payment_step() {
        $this->set_transaction_status_to_payment_completed();
        $this->controller->redirect( 'payment-completed' );
    }

    private function set_transaction_status_to_payment_completed() {
        $errors = array();

        $this->payments->set_transaction_status_to_payment_completed( $this->transaction, $errors );

        if ( ! $this->transaction->is_payment_completed() ) {
            throw new AWPCP_Exception( implode( ' ', $errors ) );
        }
    }
}
