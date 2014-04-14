<?php

class AWPCP_PayPalStandardPaymentGateway extends AWPCP_PaymentGateway {

    const PAYPAL_URL = 'https://www.paypal.com/cgi-bin/webscr';
    const SANDBOX_URL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

    public function __construct() {
        $icon = AWPCP_URL . '/resources/images/payments-paypal.jpg';
        parent::__construct('paypal', _x('PayPal', 'payment gateways', 'AWPCP'), '', $icon);
    }

    public function get_integration_type() {
        return self::INTEGRATION_BUTTON;
    }

    /**
     * Verify data received from PayPal IPN notifications using cURL and
     * returns PayPal's response.
     *
     * Request errors, if any, are returned by reference.
     *
     * @since 2.1.4
     */
    private function verify_recevied_data_with_curl($postfields='', $cainfo=true, &$errors=array()) {
        if (get_awpcp_option('paylivetestmode') == 1) {
            $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        } else {
            $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
        }

        $ch = curl_init($paypal_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

        if ($cainfo)
            curl_setopt($ch, CURLOPT_CAINFO, AWPCP_DIR . '/cacert.pem');

        $result = curl_exec($ch);
        if (in_array($result, array('VERIFIED', 'INVALID'))) {
            $response = $result;
        } else {
            $response = 'ERROR';
        }

        if (curl_errno($ch)) {
            $errors[] = sprintf('%d: %s', curl_errno($ch), curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }

    /**
     * Verify data received from PayPal IPN notifications using fsockopen and
     * returns PayPal's response.
     *
     * Request errors, if any, are returned by reference.
     *
     * @since 2.1.1
     */
    private function verify_received_data_with_fsockopen($content, &$errors=array()) {
        if (get_awpcp_option('paylivetestmode') == 1) {
            $host = "www.sandbox.paypal.com";
        } else {
            $host = "www.paypal.com";
        }

        $response = 'ERROR';

        // post back to PayPal system to validate
        $header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
        $header.= "Host: $host\r\n";
        $header.= "Connection: close\r\n";
        $header.= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header.= "Content-Length: " . strlen($content) . "\r\n\r\n";
        $fp = fsockopen("ssl://$host", 443, $errno, $errstr, 30);

        if ($fp) {
            fputs ($fp, $header . $content);

            while(!feof($fp)) {
                $line = fgets($fp, 1024);
                if (strcasecmp($line, "VERIFIED") == 0 || strcasecmp($line, "INVALID") == 0) {
                    $response = $line;
                    break;
                }
            }

            fclose($fp);
        } else {
            $errors[] = sprintf('%d: %s', $errno, $errstr);
        }

        return $response;
    }

    /**
     * Verify data received from PayPal IPN notifications and returns PayPal's
     * response.
     *
     * Request errors, if any, are returned by reference.
     *
     * @since 2.0.7
     */
    private function verify_received_data($data=array(), &$errors=array()) {
        $content = 'cmd=_notify-validate';
        foreach ($data as $key => $value) {
            $value = urlencode(stripslashes($value));
            $content .= "&$key=$value";
        }

        $response = 'ERROR';
        if (in_array('curl', get_loaded_extensions())) {
            // try using custom CA information -- included with the plugin
            $response = $this->verify_recevied_data_with_curl($content, true, $errors);

            // try using default CA information -- installed in the server
            if (strcmp($response, 'ERROR') === 0)
                $response = $this->verify_recevied_data_with_curl($content, false, $errors);
        }

        if (strcmp($response, 'ERROR') === 0)
            $response = $this->verify_received_data_with_fsockopen($content, $errors);

        return $response;
    }

    private function verify_transaction($transaction) {
        $errors = array();

        // PayPal can redirect users using a GET request and issuing
        // a POST request in the background. If the transaction was
        // already verified during the POST request the result
        // should be stored in the transaction's verified attribute
        if (!empty($_POST)) {
            $response = $this->verify_received_data($_POST, $errors);
            $verified = strcasecmp($response, 'VERIFIED') === 0;
        } else {
            $verified = $transaction->get('verified', false);
        }

        if (!$verified) {
            $variables = count($_POST);
            $url = awpcp_current_url();

            if ($variables <= 0) {
                $message = __("We haven't received your payment information from PayPal yet and we are unable to verify your transaction. Please reload this page or visit <a href=\"%s\">%s</a> in 30 seconds to continue placing your Ad.", 'AWPCP');
                $errors[] = sprintf($message, $url, $url);
            } else {
                $message = __("PayPal returned the following status from your payment: %s. %d payment variables were posted.",'AWPCP');
                $errors[] = sprintf($message, $response, count($_POST));
                $errors[] = __("If this status is not COMPLETED or VERIFIED, then you may need to wait a bit before your payment is approved, or contact PayPal directly as to the reason the payment is having a problem.",'AWPCP');
            }

            $errors[] = __("If you have any further questions, please contact this site administrator.",'AWPCP');

            if ($variables <= 0)
                $transaction->errors['verification-get'] = $errors;
            else
                $transaction->errors['verification-post'] = $errors;
        } else {
            // clean up previous errors
            unset($transaction->errors['verification-get']);
            unset($transaction->errors['verification-post']);
        }

        $transaction->set('txn-id', awpcp_post_param('txn_id'));
        $transaction->set('verified', $verified);

        return $verified;
    }

    private function validate_transaction($transaction) {
        $errors = $transaction->errors;

        // PayPal can redirect users using a GET request and issuing
        // a POST request in the background. If the transaction was
        // already verified during the POST transaction the result
        // should be stored in the transaction's validated attribute
        if (empty($_POST)) {
            return $transaction->get('validated', false);
        }

        $business = awpcp_post_param('business');
        $mc_gross = $mcgross = number_format((double) awpcp_post_param('mc_gross'), 2);
        $payment_gross = number_format((double) awpcp_post_param('payment_gross'), 2);
        $txn_id = awpcp_post_param('txn_id');
        $txn_type = awpcp_post_param('txn_type');
        $custom = awpcp_post_param('custom');
        $receiver_email = awpcp_post_param('receiver_email');
        $payer_email = awpcp_post_param('payer_email');

        // this variables are not used for verification purposes
        $item_name = awpcp_post_param('item_name');
        $item_number = awpcp_post_param('item_number');
        $quantity = awpcp_post_param('quantity');
        $mc_fee = awpcp_post_param('mc_fee');
        $tax = awpcp_post_param('tax');
        $payment_currency = awpcp_post_param('mc_currency');
        $exchange_rate = awpcp_post_param('exchange_rate');
        $payment_status = awpcp_post_param('payment_status');
        $payment_type = awpcp_post_param('payment_type');
        $payment_date = awpcp_post_param('payment_date');
        $first_name = awpcp_post_param('first_name');
        $last_name = awpcp_post_param('last_name');
        $address_street = awpcp_post_param('address_street');
        $address_zip = awpcp_post_param('address_zip');
        $address_city = awpcp_post_param('address_city');
        $address_state = awpcp_post_param('address_state');
        $address_country = awpcp_post_param('address_country');
        $address_country_code = awpcp_post_param('address_country_code');
        $residence_country = awpcp_post_param('residence_country');

        // TODO: Add support for recurring payments and subscriptions?
        if ( ! in_array( $txn_type, array( 'web_accept', 'cart' ) ) ) {
            // we do not support other forms of payment right now
            return;
        }

        $totals = $transaction->get_totals();
        $amount = number_format($totals['money'], 2);
        $amount_before_tax = number_format($mc_gross - $tax, 2);
        if ($amount != $mc_gross && $amount != $payment_gross && $amount != $amount_before_tax) {
            $message = __("The amount you have paid does not match the required amount for this transaction. Please contact us to clarify the problem.", "AWPCP");
            $transaction->errors['validation'] = $message;
            $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_INVALID;
            awpcp_payment_failed_email($transaction, $message);
            return false;
        }

        $paypal_email = get_awpcp_option('paypalemail');
        if (strcasecmp($receiver_email, $paypal_email) !== 0 && strcasecmp($business, $paypal_email) !== 0) {
            $message = __("There was an error processing your transaction. If funds have been deducted from your account, they have not been processed to our account. You will need to contact PayPal about the matter.", "AWPCP");
            $transaction->errors['validation'] = $message;
            $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_INVALID;
            awpcp_payment_failed_email($transaction, $message);
            return false;
        }

        // TODO: handle this filter for Ads and Subscriptions
        $duplicated = apply_filters('awpcp-payments-is-duplicated-transaction', false, $txn_id);
        if ($duplicated) {
            $message = __("It appears this transaction has already been processed. If you do not see your ad in the system please contact the site adminstrator for assistance.", "AWPCP");
            $transaction->errors['validation'] = $message;
            $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_INVALID;
            awpcp_payment_failed_email($transaction, $message);
            return false;
        }

        if (strcasecmp($payment_status, 'Completed') === 0) {
            $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_COMPLETED;

        } else if (strcasecmp($payment_status, 'Pending') === 0) {
            $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_PENDING;

        } else if (strcasecmp($payment_status, 'Refunded') === 0 ||
                   strcasecmp($payment_status, "Reversed") == 0 ||
                   strcasecmp($payment_status, "Partially-Refunded") == 0 ||
                   strcasecmp($payment_status, "Canceled_Reversal") == 0 ||
                   strcasecmp($payment_status, "Denied") == 0 ||
                   strcasecmp($payment_status, "Expired") == 0 ||
                   strcasecmp($payment_status, "Failed") == 0 ||
                   strcasecmp($payment_status, "Voided") == 0)
        {
            $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_FAILED;

        } else {
            $message = __("We couldn't determine the payment status for your transaction. Please contact customer service if you are viewing this message after having made a payment. If you have not tried to make a payment and you are viewing this message, it means this message is being shown in error and can be disregarded.", "AWPCP");
            $transaction->errors['validation'] = $message;
            $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_UNKNOWN;

            return false;
        }

        // at this point the validation was successful, any previously stored
        // errors are irrelevant
        unset($transaction->errors['validation']);

        $transaction->set( 'validated', true );
        $transaction->payment_gateway = $this->slug;
        $transaction->payer_email = $payer_email;

        return true;
    }

    public function process_payment($transaction) {
        return $this->render_payment_button($transaction);
    }

    private function render_payment_button($transaction) {
        global $awpcp_imagesurl;

        // no current support for multiple items
        $item = $transaction->get_item(0);

        $is_recurring = get_awpcp_option('paypalpaymentsrecurring');
        $is_test_mode_enabled = get_awpcp_option('paylivetestmode') == 1;

        $currency = get_awpcp_option('paypalcurrencycode');
        $custom = $transaction->id;

        $totals = $transaction->get_totals();
        $amount = $totals['money'];

        $payments = awpcp_payments_api();
        $return_url = $payments->get_return_url($transaction);
        $notify_url = $payments->get_notify_url($transaction);
        $cancel_url = $payments->get_cancel_url($transaction);

        $paypal_url = $is_test_mode_enabled ? self::SANDBOX_URL : self::PAYPAL_URL;

        ob_start();
            include(AWPCP_DIR . '/frontend/templates/payments-paypal-payment-button.tpl.php');
            $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    public function process_payment_completed($transaction) {
        if (!$this->verify_transaction($transaction)) {
            $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_NOT_VERIFIED;
        } else {
            $this->validate_transaction( $transaction );
        }
    }

    public function process_payment_notification($transaction) {
        $this->process_payment_completed($transaction);
    }

    public function process_payment_canceled($transaction) {
        $transaction->errors[] = __("The payment transaction was canceled by the user.", "AWPCP");
        $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_CANCELED;
    }
}
