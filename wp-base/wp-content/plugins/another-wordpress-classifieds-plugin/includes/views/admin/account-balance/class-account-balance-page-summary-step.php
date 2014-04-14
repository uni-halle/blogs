<?php

class AWPCP_AccountBalancePageSummaryStep {

    private $payments;

    public function __construct( $payments ) {
        $this->payments = $payments;
    }

    public function get( $controller ) {
        $params = array(
            'payments' => $this->payments,
            'messages' => $controller->messages,
            'url' => $controller->url( array( 'step' => 'select-credit-plan' ) ),
        );

        $template = AWPCP_DIR . '/admin/templates/page-account-balance-summary-step.tpl.php';

        $controller->render( $template, $params );
        $controller->skip_next_step();
    }

    public function post( $controller ) {
        return $this->get( $controller );
    }
}
