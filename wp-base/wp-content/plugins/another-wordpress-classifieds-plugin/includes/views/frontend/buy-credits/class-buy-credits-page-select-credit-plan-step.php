<?php

class AWPCP_BuyCreditsPageSelectCreditPlanStep {

    private $payments;

    public function __construct( $payments ) {
        $this->payments = $payments;
    }

    public function get( $controller ) {
        $params = array(
            'payments' => $this->payments,
            'transaction' => $controller->get_transaction( false ),
            'messages' => $controller->messages,
            'errors' => $controller->errors,
        );

        $template = AWPCP_DIR . '/frontend/templates/page-buy-credits-select-credit-plan-step.tpl.php';

        $controller->render( $template, $params );
        $controller->skip_next_step();
    }

    public function post( $controller ) {
        // pass
    }
}
