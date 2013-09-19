<?php


function awpcp_payment_urls($transaction) {
	$thank_you_id = awpcp_get_page_id_by_ref('payment-thankyou-page-name');
	$thank_you_url = get_permalink($thank_you_id);
	$cancel_id = awpcp_get_page_id_by_ref('payment-cancel-page-name');
	$cancel_url = get_permalink($cancel_id);

	$permalink_structure = get_option('permalink_structure');
	if (!empty($permalink_structure)) {
		$return_url = trailingslashit($thank_you_url) . $transaction->id;
		$notify_url = trailingslashit($thank_you_url) . $transaction->id;
		$cancel_url = trailingslashit($cancel_url) . $transaction->id;
	} else {
		$return_url = add_query_arg(array('awpcp-txn' => $transaction->id), $thank_you_url);
		$notify_url = add_query_arg(array('awpcp-txn' => $transaction->id), $thank_you_url);
		$cancel_url = add_query_arg(array('awpcp-txn' => $transaction->id), $cancel_url);
	}

	return array($return_url, $notify_url, $cancel_url);
}


/**
 * Verify data received from PayPal IPN notifications using cURL and
 * returns PayPal's response.
 *
 * Request errors, if any, are returned by reference.
 *
 * @since 2.1.1
 */
function awpcp_paypal_verify_recevied_data_with_curl($postfields='', $cainfo=true, &$errors=array()) {
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
function awpcp_paypal_verify_received_data_with_fsockopen($content, &$errors=array()) {
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
function awpcp_paypal_verify_received_data($data=array(), &$errors=array()) {
	$content = 'cmd=_notify-validate';
	foreach ($data as $key => $value) {
		$value = urlencode(stripslashes($value));
		$content .= "&$key=$value";
	}

	$response = 'ERROR';
	if (in_array('curl', get_loaded_extensions())) {
		// try using custom CA information -- included with the plugin
		$response = awpcp_paypal_verify_recevied_data_with_curl($content, true, $errors);

		// try using default CA information -- installed in the server
		if (strcmp($response, 'ERROR') === 0)
			$response = awpcp_paypal_verify_recevied_data_with_curl($content, false, $errors);
	}

	if (strcmp($response, 'ERROR') === 0)
		$response = awpcp_paypal_verify_received_data_with_fsockopen($content, $errors);

	return $response;
}


/**
 * email the administrator and the user to notify that the payment process was failed
 * @since  2.1.4
 */
function awpcp_payment_failed_email($transaction, $message='') {
	$user = get_userdata($transaction->user_id);

	// user email

	$mail = new AWPCP_Email;
	$mail->to[] = "{$user->display_name} <{$user->user_email}>";
	$mail->subject = get_awpcp_option('paymentabortedsubjectline');

	$template = AWPCP_DIR . '/frontend/templates/email-abort-payment-user.tpl.php';
	$mail->prepare($template, compact('message', 'user', 'transaction'));

	$mail->send();

	// admin email

	$mail = new AWPCP_Email;
	$mail->to[] = awpcp_admin_email_to();
	$mail->subject = __("Customer attempt to pay has failed", "AWPCP");

	$template = AWPCP_DIR . '/frontend/templates/email-abort-payment-admin.tpl.php';
	$mail->prepare($template, compact('message', 'user', 'transaction'));

	$mail->send();
}
