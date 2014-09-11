<?php

/**
 * @since 3.0.2
 */
class AWPCP_BuyCreditsPage extends AWPCP_BasePage {

    public function __construct( $steps, $request ) {
        parent::__construct( $steps, $request );

        $this->page = 'awpcp-buy-credits';
        $this->title = __( 'Buy Credits', 'AWPCP' );
    }

    public function get_transaction( $create = true ) {
        if ( ! isset( $this->transaction ) ) {
            $id = $this->request->param( 'transaction_id' );

            // TODO: inject PaymentTransaction dependency
            if ( $create === true  ) {
                $this->transaction = AWPCP_Payment_Transaction::find_or_create( $id );
            } else {
                $this->transaction = AWPCP_Payment_Transaction::find_by_id( $id );
            }
        }

        if ( ! is_null( $this->transaction ) && $this->transaction->is_new() ) {
            $this->transaction->user_id = wp_get_current_user()->ID;
            $this->transaction->set( 'context', 'add-credit' );
            $this->transaction->set( 'redirect', $this->url() );
            $this->transaction->set( 'redirect-data', array( 'action' => 'payment-completed' ) );
        }

        return $this->transaction;
    }

    public function get_or_create_transaction() {
        return $this->get_transaction( true );
    }

    public function dispatch() {
        if ( $this->is_user_allowed_to_buy_credits() ) {
            $this->do_page();
        } else {
            $this->render_user_not_allowed_error();
        }

        return $this->output;
    }

    private function is_user_allowed_to_buy_credits() {
        return awpcp_current_user_is_admin() ? false : true;
    }

    protected function do_page_steps() {
        $this->validate_payment_transaction();
        parent::do_page_steps();
    }

    private function validate_payment_transaction() {
        $this->verify_payment_transaction_has_a_valid_context();
        $this->verify_payment_was_succesfull();
        $this->force_payment_completed_step_if_necessary();
        $this->force_final_step_if_necessary();
    }

    private function verify_payment_transaction_has_a_valid_context() {
        $transaction = $this->get_transaction();

        if ( ! is_null( $transaction ) && $transaction->get( 'context' ) != 'add-credit' ) {
            $page_name = $this->title;
            $page_url = $this->url( array( 'page', $this->page ) );

            $message = __( 'You are trying to buy credits using a transaction created for a different purpose. Pelase go back to the <a href="%s">%s</a> page.<br>If you think this is an error please contact the administrator and provide the following transaction ID: %s', 'AWPCP' );
            $message = sprintf( $message, $page_url, $page_name, $transaction->id );

            throw new AWPCP_Exception( $message );
        }
    }

    private function verify_payment_was_succesfull() {
        $transaction = $this->get_transaction();

        if ( ! is_null( $transaction ) && $transaction->is_payment_completed() ) {
            if ( ! $transaction->was_payment_successful() ) {
                $this->errors = array_merge( $this->errors, $transaction->errors );
                $message = __('The payment associated with this transaction failed (see reasons below).', 'AWPCP');

                throw new AWPCP_Exception( $message );
            }
        }
    }

    private function force_payment_completed_step_if_necessary() {
        $transaction = $this->get_transaction();

        $step_name = $this->get_current_step_name();
        $step_not_allowed = in_array( $step_name, array( 'select-credit-plan', 'checkout' ) );

        if ( ! is_null( $transaction ) && $transaction->is_payment_completed() ) {
            if ( $transaction->was_payment_successful() && $step_not_allowed ) {
                $this->set_current_step( 'payment-completed' );
            }
        }
    }

    private function force_final_step_if_necessary() {
        $transaction = $this->get_transaction();

        if ( ! is_null($transaction) && $transaction->is_completed() ) {
            $this->set_current_step( 'final' );
        }
    }

    protected function render_user_not_allowed_error() {
        $this->errors[] = __( 'You are not allowed to buy credits.', 'AWPCP' );
        $this->render_page_error();
    }
}

function awpcp_buy_credits_page() {
    $request = new AWPCP_Request();
    $steps = awpcp_buy_credit_page_steps( awpcp_payments_api() );

    return new AWPCP_BuyCreditsPage( $steps, $request );
}

function awpcp_buy_credit_page_steps( $payments ) {
    return array(
        'select-credit-plan' =>
            new AWPCP_SetTransactionStatusToOpenStepDecorator(
                new AWPCP_SetCreditPlanStepDecorator(
                    new AWPCP_VerifyCreditPlanWasSetStepDecorator(
                        new AWPCP_PrepareTransactionForPaymentStepDecorator(
                            new AWPCP_BuyCreditsPageSelectCreditPlanStep( $payments ),
                            $payments,
                            'payment-completed',
                            'checkout'
                        ),
                        $payments
                    ),
                    $payments
                ),
                $payments
            ),
        'checkout' =>
            new AWPCP_VerifyTransactionExistsStepDecorator(
                new AWPCP_SetTransactionStatusToCheckoutStepDecorator(
                    new AWPCP_VerifyPaymentCanBeProcessedStepDecorator(
                        new AWPCP_SetPaymentMethodStepDecorator(
                            new AWPCP_BuyCreditsPageCheckoutStep( $payments ),
                            $payments
                        )
                    ),
                    $payments
                )
            ),
        'payment-completed' => new AWPCP_BuyCreditsPagePaymentCompletedStep( $payments ),
        'final' =>
            new AWPCP_SetTransactionStatusToCompletedStepDecorator(
                new AWPCP_BuyCreditsPageFinalStep( $payments ),
                $payments
            ),
    );
}
