<?php

class AWPCP_BuyCreditsPageFinalStep {

    private $payments;

    public function __construct( $payments ) {
        $this->payments = $payments;
    }

    public function get( $controller ) {
        $controller->messages[] = __( 'Congratulations. You have successfully added credit to your account.', 'AWPCP' );

        $action_url = remove_query_arg( array( 'step', 'transaction_id' ), $controller->url() );
        $params = array(
            'payments' => $this->payments,
            'messages' => $controller->messages,
            'action_url' => $action_url,
        );

        $template = AWPCP_DIR . '/frontend/templates/page-buy-credits-final-step.tpl.php';

        $controller->render( $template, $params );
        $controller->skip_next_step();
    }

    public function post( $controller ) {
        $this->get( $controller );
    }
}
