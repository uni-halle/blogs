<?php

class AWPCP_SetCreditPlanStepDecorator extends AWPCP_StepDecorator {

    private $payments;

    public function __construct( $decorated, $payments ) {
        parent::__construct( $decorated );
        $this->payments = $payments;
    }

    public function before_post( $controller ) {
        $this->transaction = $controller->get_transaction();
        $this->set_transaction_credit_plan();
    }

    private function set_transaction_credit_plan() {
        $this->transaction->remove_all_items();
        $this->payments->set_transaction_credit_plan( $this->transaction );
    }
}
