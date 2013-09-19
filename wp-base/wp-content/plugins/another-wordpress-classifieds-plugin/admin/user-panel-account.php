<?php

class AWPCP_UserAccount extends AWPCP_AdminPage {

    private $messages = array();

    public function __construct() {
        $title = __('Account Balance', 'AWPCP');
        parent::__construct('awpcp-user-account', $title, $title);
        $this->sidebar = false;
    }

    public function get_current_action($default='summary') {
        if (!$this->action)
            $this->action = awpcp_request_param('step', $default);
        return $this->action;
    }

    public function get_transaction() {
        $id = awpcp_request_param('awpcp-txn');

        if (!isset($this->transaction))
            $this->transaction = AWPCP_Payment_Transaction::find_or_create($id);

        if (!is_null($this->transaction) && $this->transaction->is_new()) {
            $this->transaction->user_id = wp_get_current_user()->ID;
            $this->transaction->set('context', 'add-credit');
            $this->transaction->set('redirect', $this->url());
            $this->transaction->set('redirect-data', array('action' => 'payment-completed'));
        }

        return $this->transaction;
    }

    private function transaction_error() {
        return __('There was an error processing your Payment Request. Please try again or contact an Administrator.', 'AWPCP');
    }

    public function dispatch() {
        echo $this->_dispatch();
    }

    public function _dispatch() {
        $transaction = $this->get_transaction();

        if (!is_null($transaction) && $transaction->get('context') != 'add-credit') {
            $page_name = $this->title;
            $page_url = add_query_arg('page', $this->slug, admin_url('profile.php'));
            $message = __('You are trying to post an Ad using a transaction created for a different purpose. Pelase go back to the <a href="%s">%s</a> page.<br>If you think this is an error please contact the administrator and provide the following transaction ID: %s', 'AWPCP');
            $message = sprintf($message, $page_url, $page_name, $transaction->id);
            return $this->render('content', awpcp_print_error($message));
        }

        $action = $this->get_current_action();

        if (!is_null($transaction) && $transaction->is_payment_completed()) {
            if ( ! $transaction->was_payment_successful() ) {
                $message = __('The payment associated with this transaction failed (see reasons below).', 'AWPCP');
                $message = awpcp_print_message($message);
                $message = $message . awpcp_payments_api()->render_transaction_errors($transaction);
                return $this->render('content', $message);
            }

            if (in_array($action, array('order', 'checkout'))) {
                $action = 'payment-completed';
            }
        }

        if (!is_null($transaction) && $transaction->is_completed()) {
            $action = 'finish';
        }

        switch ($action) {
            case 'order':
                return $this->order_step();
            case 'checkout':
                return $this->checkout_step();
            case 'payment-completed':
                return $this->payment_completed_step();
            case 'finish':
                return $this->finish_step();
            case 'summary':
            default:
                return $this->summary();
        }
    }

    public function summary() {
        $payments = awpcp_payments_api();

        $action = remove_query_arg(array('step', 'awpcp-txn'), $this->url());

        $params = array(
            'action' => $action,
            'payments' => $payments,
            'messages' => $this->messages
        );

        $template = AWPCP_DIR . '/admin/templates/user-panel-account-summary.tpl.php';
        return $this->render($template, $params);
    }

    public function order_step() {
        $payments = awpcp_payments_api();
        $transaction = $this->get_transaction();

        $transaction_errors = array();
        $form_errors = array();

        $attempts = awpcp_post_param('attempts', 0);

        if ($transaction->is_new())
            $payments->set_transaction_status_to_open($transaction, $transaction_errors);

        $transaction->remove_all_items();
        $payments->set_transaction_credit_plan($transaction);

        $credit_plan = $payments->get_transaction_credit_plan($transaction);
        if (is_null($credit_plan)) {
            $form_errors['credit_plan'] = __('You should choose one of the available Credit Plans', 'AWPCP');
        } else {
            if (empty($transaction_errors) && $transaction->payment_is_not_required()) {
                $payments->set_transaction_status_to_payment_completed($transaction, $transaction_errors);

                if (empty($transaction_errors)) {
                    return $this->payment_completed_step();
                }
            }

            if (empty($transaction_errors)) {
                $payments->set_transaction_status_to_checkout($transaction, $transaction_errors);

                if (empty($transaction_errors)) {
                    return $this->checkout_step();
                }
            }
        }

        $params = array(
            'payments' => $payments,
            'transaction' => $transaction,
            'attempts' => $attempts,
            'messages' => $this->messages,
            'form_errors' => $attempts > 0 ? $form_errors : array(),
            'transaction_errors' => $transaction_errors,
        );

        $template = AWPCP_DIR . '/admin/templates/user-panel-account-order-step.tpl.php';

        return $this->render($template, $params);
    }

    public function checkout_step() {
        $transaction = $this->get_transaction();
        $payments = awpcp_payments_api();

        if (is_null($transaction)) {
            $message = $this->transaction_error();
            return $this->render('content', awpcp_print_error($message));
        }

        if (!$transaction->is_ready_to_checkout() && !$transaction->is_processing_payment()) {
            $message = __('We can\'t process payments for this Payment Transaction at this time. Please contact the website administrator and provide the following transaction ID: %s', 'AWPCP');
            $message = sprintf($message, $transaction->id);
            return $this->render('content', awpcp_print_error($message));
        }

        $payments->set_transaction_payment_method($transaction);

        $params = array(
            'payments' => $payments,
            'transaction' => $transaction,
            'messages' => $this->messages,
            'hidden' => array('step' => 'checkout')
        );

        $template = AWPCP_DIR . '/admin/templates/user-panel-account-checkout-step.tpl.php';

        return $this->render($template, $params);
    }

    public function payment_completed_step() {
        $transaction = $this->get_transaction();
        $payments = awpcp_payments_api();

        $params = array(
            'payments' => $payments,
            'transaction' => $transaction,
            'messages' => $this->messages,
            'url' => $this->url(),
            'hidden' => array('step' => 'finish')
        );

        $template = AWPCP_DIR . '/admin/templates/user-panel-account-payment-completed-step.tpl.php';

        return $this->render($template, $params);
    }

    public function finish_step() {
        $transaction = $this->get_transaction();

        if (is_null($transaction)) {
            $message = __('We were unable to find a Payment Transaction assigned to this operation.', 'AWPCP');
            return $this->render('content', awpcp_print_error($message));
        }

        $payments = awpcp_payments_api();
        $payments->set_transaction_status_to_completed($transaction, $errors);

        $this->messages[] = __('Congratulations. You have successfully added credit to your account.', 'AWPCP');

        return $this->summary();
    }
}
