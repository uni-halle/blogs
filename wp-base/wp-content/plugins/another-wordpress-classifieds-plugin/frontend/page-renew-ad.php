<?php

require_once(AWPCP_DIR . '/frontend/page-place-ad.php');


/**
 * @since  2.1.4
 */
class AWPCP_RenewAdPage extends AWPCP_Place_Ad_Page {

    protected $context = 'renew-ad';

    public $messages = array();

    public function __construct($page='awpcp-renew-ad', $title=null) {
        parent::__construct($page, $title);
    }

    protected function get_panel_url() {
        if (awpcp_current_user_is_admin() || !get_awpcp_option('enable-user-panel'))
            return awpcp_get_admin_listings_url();
        return awpcp_get_user_panel_url();
    }

    public function get_ad() {
        if (!isset($this->ad))
            $this->ad = null;

        if (is_null($this->ad))
            $this->ad = AWPCP_Ad::find_by_id(awpcp_request_param(is_admin() ? 'id' : 'ad_id'));

        return $this->ad;
    }

    protected function _dispatch($default=null) {
        $ad = $this->get_ad();

        if (is_null($ad)) {
            $message = __("The specified Ad doesn't exist or you reached this page directly, without specifying the Ad ID.", 'AWPCP');
            return $this->render('content', awpcp_print_error($message));
        } else if (!$ad->is_about_to_expire() && !$ad->has_expired()) {
            $message = __("The specified Ad doesn't need to be renewed.", 'AWPCP');
            return $this->render('content', awpcp_print_error($message));
        }

        $transaction = $this->get_transaction();

        if (!is_null($transaction) && $transaction->get('context') != $this->context) {
            $page_name = awpcp_get_page_name('renew-ad-page-name');
            $page_url = awpcp_get_page_url('renew-ad-page-name');
            $message = __('You are trying to post an Ad using a transaction created for a different purpose. Pelase go back to the <a href="%s">%s</a> page.<br>If you think this is an error please contact the administrator and provide the following transaction ID: %s', 'AWPCP');
            $message = sprintf($message, $page_url, $page_name, $transaction->id);
            return $this->render('content', awpcp_print_error($message));
        }

        $action = $this->get_current_action($default);

        if (!is_null($transaction) && $transaction->is_payment_completed()) {
            if ( ! $transaction->was_payment_successful() ) {
                $message = __('You can\'t renew your Ad at this time because the payment associated with this transaction failed (see reasons below).', 'AWPCP');
                $message = awpcp_print_message($message);
                $message = $message . awpcp_payments_api()->render_transaction_errors($transaction);
                return $this->render('content', $message);
            }

            $forbidden = in_array($action, array('order', 'checkout'));
            if ($forbidden) {
                $action = 'payment-completed';
            }
        }

        if (!is_null($transaction) && $transaction->is_completed()) {
            $action = 'finish';
        }

        $implementation = $this->get_renew_ad_page_implementation($ad);

        if (is_null($implementation)) {
            $message = __("The Ad was posted under a Payment Term that no longer exists or is disabled. The Ad can't be renewed.", 'AWPCP');
            $content = '<p>' . $this->get_return_link($ad) . '</p>';
            return $this->render('content', awpcp_print_error($message) . $content);
        }

        switch ($action) {
            case 'order':
                return $implementation->order_step();
            case 'checkout':
                return $implementation->checkout_step();
            case 'payment-completed':
                return $implementation->payment_completed_step();
            case 'finish':
                return $implementation->finish_step();
            default:
                return $implementation->order_step();
        }
    }

    protected function get_renew_ad_page_implementation($ad) {
        $term = awpcp_payments_api()->get_ad_payment_term($ad);

        // the payment term doesn't exists or is not available
        if (is_null($term)) return null;

        // we handle the default implementation
        if ($term->type === AWPCP_FeeType::TYPE) {
            return new AWPCP_RenewAdPageImplementation($this);
        } else {
            return apply_filters('awpcp-get-renew-ad-page-implementation', null, $term->type, $this);
        }
    }

    public function get_return_link($ad) {
        if (is_admin()) {
            return sprintf('<a href="%1$s">%2$s</a>', $this->get_panel_url(), __('Return to Listings', 'AWPCP'));
        } else {
            return sprintf('<a href="%1$s">%2$s</a>', url_showad($ad->ad_id), __('You can see your Ad here', 'AWPCP'));
        }
    }

    public function render_finish_step($ad) {
        $return = $this->get_return_link($ad);

        $response = __("The Ad has been successfully renewed. New expiration date is %s. ", 'AWPCP');
        $response = sprintf("%s %s.", sprintf($response, $ad->get_end_date()), $return);

        $params = compact('response');
        $template = AWPCP_DIR . '/frontend/templates/page-renew-ad-finish-step.tpl.php';

        return $this->render($template, $params);
    }
}

class AWPCP_RenewAdPageImplementation {

    public $messages = array();

    public function __construct($page) {
        $this->page = $page;
    }

    protected function validate_order($data, &$errors=array()) {
        if (is_null($data['term'])) {
            $errors[] = __('You should choose one of the available Payment Terms.', 'AWPCP');
        } else {
            if ($data['term']->type != $data['fee']->type || $data['term']->id != $data['fee']->id) {
                $errors[] = __("You are trying to renew your Ad using a different Payment Term. That's not allowed.", 'AWPCP');
            }
        }
    }

    public function order_step() {
        $ad = $this->page->get_ad();
        $transaction = $this->page->get_transaction(true);

        $payments = awpcp_payments_api();
        $fee = $payments->get_ad_payment_term($ad);

        $form_errors = array();
        $transaction_errors = array();

        // verify pre-conditions

        if ($transaction->is_new()) {
            $payments->set_transaction_status_to_open($transaction, $transaction_errors);
        }

        // validate submitted data and prepare transaction

        $payment_terms = new AWPCP_PaymentTermsTable(array($fee->type => array($fee)), $transaction->get('payment-term'));

        if (awpcp_current_user_is_admin() || !$payments->payment_term_requires_payment($fee)) {
            $term = $fee;

            $transaction->set('payment-term-type', $term->type);
            $transaction->set('payment-term-id', $term->id);
            $transaction->set('ad-id', $ad->ad_id);

            $transaction->remove_all_items();
            $payment_terms->set_transaction_item($transaction, $term);

        } else {
            $term = $payments->get_transaction_payment_term($transaction);

            if (!empty($_POST)) {
                $term = $payment_terms->get_payment_term($payment_type, $selected);

                $this->validate_order(compact('term', 'fee'), $form_errors);

                if (empty($form_errors)) {
                    $transaction->set('payment-term', $selected);
                    $transaction->set('payment-term-type', $term->type);
                    $transaction->set('payment-term-id', $term->id);
                    $transaction->set('ad-id', $ad->ad_id);

                    $transaction->remove_all_items();
                    $payment_terms->set_transaction_item($transaction);

                    // process transaction to grab Credit Plan information
                    $payments->set_transaction_credit_plan($transaction);
                }
            }
        }

        // let other parts of the plugin know a transaction is being processed
        $payments->process_transaction($transaction);

        // if everything is fine move onto the next step
        if (!is_null($term)) {
            $payments->set_transaction_status_to_checkout($transaction, $transaction_errors);
            if (empty($transaction_errors)) {
                return $this->checkout_step();
            }
        }

        // otherwise display the order form to grab information and show any errors

        $messages = $this->messages;
        if (awpcp_current_user_is_admin()) {
            $messages[] = __("You are logged in as an administrator. Any payment steps will be skipped.", "AWPCP");
        }

        $params = array(
            'payments' => $payments,
            'transaction' => $transaction,
            'table' => $payment_terms,

            'messages' => $messages,
            'form_errors' => $form_errors,
            'transaction_errors' => $transaction_errors
        );

        $template = AWPCP_DIR . '/frontend/templates/page-renew-ad-order-step.tpl.php';

        return $this->page->render($template, $params);
    }

    public function checkout_step() {
        $transaction = $this->page->get_transaction(true);
        $payments = awpcp_payments_api();

        // verify transaction pre-conditions

        if (is_null($transaction)) {
            $message = $this->page->transaction_error();
            return $this->page->page->render('content', awpcp_print_error($message));
        }

        if ($transaction->is_payment_completed()) {
            return $this->payment_completed_step();
        }

        if ($transaction->payment_is_not_required()) {
            $errors = array();
            $payments->set_transaction_status_to_payment_completed($transaction, $errors);

            return $this->payment_completed_step();
        }

        if (!$transaction->is_ready_to_checkout() && !$transaction->is_processing_payment()) {
            $message = __('We can\'t process payments for this Payment Transaction at this time. Please contact the website administrator and provide the following transaction ID: %s', 'AWPCP');
            $message = sprintf($message, $transaction->id);
            return $this->page->render('content', awpcp_print_error($message));
        }

        // proceess transaction to grab Payment Method information

        $payments->set_transaction_payment_method($transaction);

        // show checkout page.

        // If a Payment Method was already selected, the Payments API already
        // processed the transaction and will (depending of the Payment Method):
        // show a checkout button, show a billing information form or
        // automatically redirect the user to the payment gateway.

        $params = array(
            'payments' => $payments,
            'transaction' => $transaction,
            'messages' => $this->messages,
            'hidden' => array('step' => 'checkout')
        );

        // here we reuse the Place Ad template, because is generic enough for our needs
        $template = AWPCP_DIR . '/frontend/templates/page-place-ad-checkout-step.tpl.php';

        return $this->page->render($template, $params);
    }

    public function payment_completed_step() {
        $transaction = $this->page->get_transaction();
        $payments = awpcp_payments_api();

        if ($transaction->payment_is_not_required()) {
            return $this->finish_step();
        }

        $params = array(
            'payments' => $payments,
            'transaction' => $transaction,
            'messages' => $this->messages,
            'url' => $this->url(),
            'hidden' => array('step' => 'finish')
        );

        // here we reuse the Place Ad template, because is generic enough for our needs
        $template = AWPCP_DIR . '/frontend/templates/page-place-ad-payment-completed-step.tpl.php';

        return $this->page->render($template, $params);
    }

    public function finish_step() {
        $transaction = $this->page->get_transaction();

        if (is_null($transaction)) {
            $message = $this->page->transaction_error();
            return $this->page->render('content', awpcp_print_error($message));
        }

        $ad = $this->page->get_ad();

        if (is_null($ad)) {
            $message = __('The Ad associated with this transaction doesn\'t exists.', 'AWPCP');
            return $this->page->render('content', awpcp_print_error($message));
        }

        if (!$transaction->is_completed()) {
            $payments = awpcp_payments_api();
            $payments->set_transaction_status_to_completed($transaction, $errors);

            if (!empty($errors)) {
                return $this->page->render('content', join(',', array_map($errors, 'awpcp_print_error')));
            }

            $ad->renew();
            $ad->save();

            awpcp_send_ad_renewed_email($ad);

            // MOVE inside Ad::renew() ?
            do_action('awpcp-renew-ad', $ad->ad_id, $transaction);
        }

        return $this->page->render_finish_step($ad);
    }
}
