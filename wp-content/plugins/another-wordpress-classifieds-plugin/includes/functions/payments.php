<?php

/**
 * Verify data received from PayPal IPN notifications using cURL and
 * returns PayPal's response.
 *
 * Request errors, if any, are returned by reference.
 *
 * @since 2.1.1
 *
 * @return VERIFIED, INVALID or ERROR
 */
function awpcp_paypal_verify_received_data_with_curl($postfields='', $cainfo=true, &$errors=array()) {
    if (get_awpcp_option('paylivetestmode') == 1) {
        $paypal_url = "https://ipnpb.sandbox.paypal.com/cgi-bin/webscr";
    } else {
        $paypal_url = "https://ipnpb.paypal.com/cgi-bin/webscr";
    }

    $ch = curl_init($paypal_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_VERBOSE, true );
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
 * @since 3.8.6
 */
function awpcp_paypal_verify_received_data_wp_remote( $postfields='', &$errors = array() ) {
    if ( get_awpcp_option( 'paylivetestmode' ) == 1 ) {
        $paypal_url = "https://ipnpb.sandbox.paypal.com/cgi-bin/webscr";
    } else {
        $paypal_url = "https://ipnpb.paypal.com/cgi-bin/webscr";
    }

    $params = array(
        'httpversion' => '1.1',
        'body'        => $postfields,
    );

    $response = wp_remote_post( $paypal_url, $params );

    if ( is_wp_error( $response ) ) {
        $errors = array_merge( $errors, $response->get_error_messages() );

        return 'ERROR';
    }

    $response_body = wp_remote_retrieve_body( $response );

    if ( ! in_array( $response_body, array( 'VERIFIED', 'INVALID' ), true ) ) {
        return 'ERROR';
    }

    return $response_body;
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
        $host = "ipnpb.sandbox.paypal.com";
    } else {
        $host = "ipnpb.paypal.com";
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
 *
 * @return VERIFIED, INVALID or ERROR
 */
function awpcp_paypal_verify_received_data($data=array(), &$errors=array()) {
    $content = 'cmd=_notify-validate';
    foreach ($data as $key => $value) {
        $value = urlencode(stripslashes($value));
        $content .= "&$key=$value";
    }

    $response = awpcp_paypal_verify_received_data_wp_remote( $content, $errors );

    if ( strcmp( $response, 'ERROR' ) && in_array( 'curl', get_loaded_extensions(), true ) ) {
        // try using custom CA information -- included with the plugin
        $response = awpcp_paypal_verify_received_data_with_curl( $content, true, $errors );

        // try using default CA information -- installed in the server
        if ( strcmp( $response, 'ERROR' ) === 0 ) {
            $response = awpcp_paypal_verify_received_data_with_curl( $content, false, $errors );
        }
    }

    if ( strcmp( $response, 'ERROR' ) === 0 ) {
        $response = awpcp_paypal_verify_received_data_with_fsockopen( $content, $errors );
    }

    return $response;
}

/**
 * Validate the data received from PayFast.
 *
 * @since 3.7.8
 */
function awpcp_payfast_verify_received_data( $data = array() ) {
    $content = '';

    foreach ( $data as $key => $value ) {
        if ( $key == 'signature' ) {
            continue;
        }

        $content .= $key . '=' . urlencode( stripslashes( $value ) ) . '&';
    }

    $content = rtrim( $content, '&' );
    $response = 'ERROR';

    if (in_array('curl', get_loaded_extensions())) {
        // try using custom CA information -- included with the plugin
        $response = awpcp_payfast_verify_received_data_with_curl( $content, true );

        // try using default CA information -- installed in the server
        if ( strcmp( $response, 'ERROR' ) === 0 ) {
            $response = awpcp_payfast_verify_received_data_with_curl( $content, false );
        }
    }

    if ( strcmp( $response, 'ERROR' ) === 0 ) {
        $response = awpcp_payfast_verify_received_data_with_fsockopen( $content, $errors );
    }

    return $response;
}

/**
 * @since 3.7.8
 */
function awpcp_payfast_verify_received_data_with_curl( $content = '', $cainfo = false ) {
    if ( get_awpcp_option( 'paylivetestmode' ) ) {
        $host = 'sandbox.payfast.co.za';
    } else {
        $host = 'www.payfast.co.za';
    }

    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_USERAGENT, 'Another WordPress Classifieds Plugin' );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_HEADER, false );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

    curl_setopt( $ch, CURLOPT_URL, 'https://'. $host .'/eng/query/validate' );
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $content );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );

    if ( $cainfo ) {
        curl_setopt( $ch, CURLOPT_CAINFO, AWPCP_DIR . '/cacert.pem' );
    }

    $response = explode( "\r\n", curl_exec( $ch ) );
    $response = trim( $response[0] );

    curl_close( $ch );

    if ( in_array( $response, array( 'VALID', 'INVALID' ) ) ) {
        $response = $response;
    } else {
        $response = 'ERROR';
    }

    return $response;
}

/**
 * @since 3.7.8
 */
function awpcp_payfast_verify_received_data_with_fsockopen( $content ) {
    if ( get_awpcp_option( 'paylivetestmode' ) ) {
        $host = 'sandbox.payfast.co.za';
    } else {
        $host = 'www.payfast.co.za';
    }

    $header_processed = false;
    $response = '';

    $header = "POST /eng/query/validate HTTP/1.0\r\n";
    $header .= "Host: ". $host ."\r\n";
    $header .= "User-Agent: Another WordPress Classifieds Plugin\r\n";
    $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header .= "Content-Length: " . strlen( $content ) . "\r\n\r\n";

    $socket = fsockopen( 'ssl://'. $host, 443, $errno, $errstr, 30 );

    fputs( $socket, $header . $content );

    while( ! feof( $socket ) ) {
        $line = fgets( $socket, 1024 );

        if ( strcmp( $line, "\r\n" ) == 0 ) {
            $header_processed = true;
        } else if ( $header_processed ) {
            $response .= $line;
        }
    }

    $response = explode( "\r\n", curl_exec( $ch ) );
    $response = trim( $response[0] );

    if ( in_array( $response, array( 'VALID', 'INVALID' ) ) ) {
        $response = $response;
    } else {
        $response = 'ERROR';
    }

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
    $mail->to[] = awpcp_format_recipient_address( $user->user_email, $user->display_name );
    $mail->subject = get_awpcp_option('paymentabortedsubjectline');

    $template = AWPCP_DIR . '/frontend/templates/email-abort-payment-user.tpl.php';
    $mail->prepare($template, compact('message', 'user', 'transaction'));

    $mail->send();

    // admin email

    $mail = new AWPCP_Email;
    $mail->to[] = awpcp_admin_email_to();
    $mail->subject = __("Customer attempt to pay has failed", 'another-wordpress-classifieds-plugin');

    $template = AWPCP_DIR . '/frontend/templates/email-abort-payment-admin.tpl.php';
    $mail->prepare($template, compact('message', 'user', 'transaction'));

    $mail->send();
}

function awpcp_paypal_supported_currencies() {
    return array(
        'AUD','BRL','CAD','CZK','DKK','EUR','HKD','HUF','ILS','JPY','MYR',
        'MXN','NOK','NZD','PHP','PLN','GBP','RUB','SGD','SEK','CHF','TWD',
        'THB','TRY','USD',
    );
}

function awpcp_paypal_supports_currency( $currency_code ) {
    $currency_codes = awpcp_paypal_supported_currencies();

    if ( ! in_array( $currency_code, $currency_codes ) ) {
        return false;
    }

    return true;
}
