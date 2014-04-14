<?php

class AWPCP_SetPaymentMethodStepDecorator extends AWPCP_StepDecorator {

    private $payments;

    public function __construct( $decorated, $payments ) {
        parent::__construct( $decorated );
        $this->payments = $payments;
    }

    public function before_get( $controller ) {
        $this->set_transaction_payment_method( $controller );
    }

    public function before_post( $controller ) {
        $this->set_transaction_payment_method( $controller );
    }

    private function set_transaction_payment_method( $controller ) {
        $transaction = $controller->get_transaction();
        $this->payments->set_transaction_payment_method( $transaction );
    }
}
