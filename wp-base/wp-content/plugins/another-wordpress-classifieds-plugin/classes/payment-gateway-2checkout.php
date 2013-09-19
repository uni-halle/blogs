<?php

class AWPCP_2CheckoutPaymentGateway extends AWPCP_PaymentGateway {

    public function __construct() {
        $icon = AWPCP_URL . '/images/payments-2checkout.png';
        parent::__construct('2checkout', _x('2Checkout', 'payment gateways', 'AWPCP'), '', $icon);
    }

    public function get_integration_type() {
        return self::INTEGRATION_BUTTON;
    }

    private function render_payment_button($transaction) {
        global $awpcp_imagesurl;

        // no current support for multiple items
        $item = $transaction->get_item(0);

        $is_recurring = get_awpcp_option('twocheckoutpaymentsrecurring');
        $is_test_mode_enabled = get_awpcp_option('paylivetestmode') == 1;

        $custom = $transaction->id;

        $totals = $transaction->get_totals();
        $amount = $totals['money'];

        $x_login = get_awpcp_option('2checkout');

        $payments = awpcp_payments_api();
        $return_url = $payments->get_return_url($transaction);
        $notify_url = $payments->get_notify_url($transaction);
        $cancel_url = $payments->get_cancel_url($transaction);

        ob_start();
            include(AWPCP_DIR . '/frontend/templates/payments-2checkout-payment-button.tpl.php');
            $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    public function process_payment($transaction) {
        return $this->render_payment_button($transaction);
    }

    /**
     * TODO: validate md5 hash
     */
    public function verify_transaction($transaction) {
        $x_response_code = awpcp_request_param('x_response_code');
        $x_twocorec = awpcp_request_param('x_twocorec');

        $transaction->set('txn-id', awpcp_request_param('x_trans_id'));

        if ($x_response_code == 1 || $x_twocorec == 1) {
            $transaction->errors['verification'] = array();
            return true;

        } else {
            $msg=__("There appears to be a problem with your payment. Please contact the administrator if you are viewing this message after having made a payment via 2Checkout. If you have not tried to make a payment and you are viewing this message, it means this message has been sent in error and can be disregarded.","AWPCP");
            $transaction->errors['verification'][] = $msg;
            return false;
        }
    }

    private function validate_transaction($transaction) {
        $x_amount = number_format(awpcp_request_param('x_amount'), 2);
        $x_Login = awpcp_request_param('x_login');

        $x_2checked = awpcp_request_param('x_2checked');
        $x_MD5_Hash = awpcp_request_param('x_MD5_Hash');
        $x_trans_id = awpcp_request_param('x_trans_id');
        $card_holder_name = awpcp_request_param('card_holder_name');
        $x_Country = awpcp_request_param('x_Country');
        $x_City = awpcp_request_param('x_City');
        $x_State = awpcp_request_param('x_State');
        $x_Zip = awpcp_request_param('x_Zip');
        $x_Address = awpcp_request_param('x_Address');
        $x_Email = awpcp_request_param('x_Email');
        $x_Phone = awpcp_request_param('x_Phone');
        $demo = awpcp_request_param('demo');
        $x_response_code= awpcp_request_param('x_response_code');
        $x_response_reason_code = awpcp_request_param('x_response_reason_code');
        $x_response_reason_text = awpcp_request_param('x_response_reason_text');
        $x_item_number = awpcp_request_param('x_item_number');
        $x_custom = awpcp_request_param('x_custom');
        $x_buyer_mail = awpcp_request_param('email');
        $x_twocorec = awpcp_request_param('x_twocorec');
        $x_order_number = awpcp_request_param('order_number');
        $x_sid = awpcp_request_param('sid');

        $totals = $transaction->get_totals();
        $amount = number_format($totals['money'], 2);
        if ($amount !== $x_amount) {
            $msg = __("The amount you have paid does not match the required amount for this transaction. Please contact us to clarify the problem.", "AWPCP");
            $transaction->errors['validation'] = $msg;
            $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_INVALID;
            awpcp_payment_failed_email($transaction, $message);
            return false;
        }

        if (strcasecmp($x_Login, get_awpcp_option('2checkout')) !== 0) {
            $msg = __("There was an error processing your transaction. If funds have been deducted from your account, they have not been processed to our account. You will need to contact PayPal about the matter.", "AWPCP");
            $transaction->errors['validation'] = $msg;
            $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_INVALID;
            awpcp_payment_failed_email($transaction, $message);
            return false;
        }

        // TODO: handle this filter for Ads and Subscriptions
        $duplicated = apply_filters('awpcp-payments-is-duplicated-transaction', false, $txn_id);
        if ($duplicated) {
            $msg = __("It appears this transaction has already been processed. If you do not see your ad in the system please contact the site adminstrator for assistance.", "AWPCP");
            $transaction->errors['validation'] = $msg;
            $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_INVALID;
            awpcp_payment_failed_email($transaction, $message);
            return false;
        }

        $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_PENDING;

        // at this point the validation was successful, any previously stored
        // errors are irrelevant
        unset($transaction->errors['validation']);

        return true;
    }

    public function process_payment_completed($transaction) {
        if (!$this->verify_transaction($transaction)) {
            $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_NOT_VERIFIED;
            return;
        }

        $this->validate_transaction($transaction);
    }

    public function process_payment_notification($transaction) {
        // Not implemented yet. We don't support 2CO INS.
    }

    public function process_payment_canceled($transaction) {
        // Dosen't seems to be a way to cancel a payment from 2CO website.
    }
}
