<?php

class AWPCP_BuyCreditsPagePaymentCompletedStep {

    private $payments;

    public function __construct( $payments ) {
        $this->payments = $payments;
    }

    public function get( $controller ) {
        $params = array(
            'payments' => $this->payments,
            'transaction' => $controller->get_transaction(),
            'messages' => $controller->messages,
            'url' => $controller->url(),
            'hidden' => array(
                'step' => 'final',
            ),
        );

        $template = AWPCP_DIR . '/frontend/templates/page-buy-credits-payment-completed-step.tpl.php';

        $controller->render( $template, $params );
        $controller->skip_next_step();
    }

    public function post() {
        //pass
    }
}
