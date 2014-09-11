<?php

/**
 * TODO: Use in BuyCreditsPage.
 * TODO: test.
 * @since 3.2.1
 */
class AWPCP_PaymentStepDecorator extends AWPCP_StepDecorator {

    private $controller;

    public function __construct( $decorated ) {
        parent::__construct( $decorated );
    }

    public function before_get( $controller ) {
        $this->controller = $controller;
        $this->validate_payment_transaction();
    }

    public function before_post( $controller ) {
        $this->controller = $controller;
        $this->validate_payment_transaction();
    }

    private function validate_payment_transaction() {
        $this->verify_payment_transaction_has_a_valid_context();
        $this->verify_payment_was_succesfull();
        $this->force_payment_completed_step_if_necessary();
        $this->force_final_step_if_necessary();
    }

    private function verify_payment_transaction_has_a_valid_context() {
        $transaction = $this->controller->get_transaction();

        if ( ! is_null( $transaction ) && $transaction->get( 'context' ) != $this->controller->get_transaction_context() ) {
            $page_name = $this->controller->title;
            $page_url = $this->controller->url( array( 'page', $this->controller->page ) );

            $message = __( 'You are trying to buy credits using a transaction created for a different purpose. Pelase go back to the <a href="%s">%s</a> page.<br>If you think this is an error please contact the administrator and provide the following transaction ID: %s', 'AWPCP' );
            $message = sprintf( $message, $page_url, $page_name, $transaction->id );

            throw new AWPCP_Exception( $message );
        }
    }

    /**
     * TODO: maybe skip this verification on the payment_completed step. The messages look better there.
     */
    private function verify_payment_was_succesfull() {
        $transaction = $this->controller->get_transaction();

        if ( ! is_null( $transaction ) && $transaction->is_payment_completed() ) {
            if ( ! $transaction->was_payment_successful() ) {
                $message = __( 'The payment associated with the current transaction failed (see reasons below).', 'AWPCP' );

                throw new AWPCP_Exception( $message, awpcp_flatten_array( $transaction->errors ) );
            }
        }
    }

    private function force_payment_completed_step_if_necessary() {
        $transaction = $this->controller->get_transaction();

        if ( ! is_null( $transaction ) && $transaction->is_payment_completed() ) {
            if ( $transaction->was_payment_successful() && $this->current_step_is_not_allowed() ) {
                $this->controller->redirect( 'payment-completed' );
            }
        }
    }

    private function current_step_is_not_allowed() {
        // TODO: get order and checkout step names as parameters
        return $this->controller->is_current_step( 'order' ) || $this->controller->is_current_step( 'checkout' );
    }

    private function force_final_step_if_necessary() {
        $transaction = $this->controller->get_transaction();

        // TODO: get final step name as parameter
        if ( ! is_null( $transaction ) && $transaction->is_completed() ) {
            return $this->controller->redirect( 'final' );
        }
    }
}
