<?php

class AWPCP_BuyCreditsPageCheckoutStep {

    private $payments;

    public function __construct( $payments ) {
        $this->payments = $payments;
    }

    public function get( $controller ) {
        $params = array(
            'payments' => $this->payments,
            'transaction' => $controller->get_transaction(),
            'messages' => $controller->messages,
            'errors' => $controller->errors,
            'hidden' => array(
                'step' => 'checkout',
            ),
        );

        $template = AWPCP_DIR . '/frontend/templates/page-buy-credits-checkout-step.tpl.php';

        $controller->render( $template, $params );
    }

    public function post( $controller ) {
        // pass
    }
}
