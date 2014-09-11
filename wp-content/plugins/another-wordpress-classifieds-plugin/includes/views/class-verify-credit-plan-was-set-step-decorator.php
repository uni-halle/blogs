<?php

class AWPCP_VerifyCreditPlanWasSetStepDecorator extends AWPCP_StepDecorator {

    private $payments;
    private $transaction;

    public function __construct( $decorated, $payments ) {
        parent::__construct( $decorated );
        $this->payments = $payments;
    }

    protected function before_post( $controller ) {
        $this->transaction = $controller->get_transaction();
        $this->verify_credit_plan_was_set();
    }

    private function verify_credit_plan_was_set() {
        $credit_plan = $this->payments->get_transaction_credit_plan( $this->transaction );
        if ( is_null( $credit_plan ) ) {
            $message = __( 'No credit plan was set. You should choose one of the available Credit Plans', 'AWPCP' );
            throw new AWPCP_Exception( $message );
        }
    }
}
