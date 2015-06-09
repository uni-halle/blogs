<?php


class AWPCP_CAPTCHA {

    public function __construct() { }

    public function render() {
        // must be overriden in sub-class
        return '';
    }

    public function validate(&$error='') {
        // must be overriden in sub-class
        return false;
    }
}


class AWPCP_DefaultCAPTCHA extends AWPCP_CAPTCHA {

    public function __construct($max_number) {
        parent::__construct();

        $this->max_number = $max_number;
    }

    private function hash($number) {
        return md5( NONCE_SALT . $number );
    }

    public function render() {
        $a = rand( 1, $this->max_number );
        $b = rand( 1, $this->max_number );

        $hash = $this->hash( $a + $b );
        $answer = awpcp_post_param( 'captcha' );

        $label = _x( 'Enter the value of the following sum: %d + %d', 'CAPTCHA', 'AWPCP' ) . '*';
        $label = sprintf( $label, $a, $b );

        $html = '<label for="captcha"><span>%s</span></label>';
        $html.= '<input type="hidden" name="captcha-hash" value="%s" />';
        $html.= '<input id="captcha" class="required" type="text" name="captcha" value="%s" size="5" />';

        return sprintf( $html, $label, $hash, esc_attr( $answer ) );
    }

    public function validate(&$error='') {
        $answer = awpcp_post_param( 'captcha' );
        $expected = awpcp_post_param( 'captcha-hash' );

        $is_valid = strcmp( $expected, $this->hash( $answer ) ) === 0;

        if ( empty( $answer ) ) {
            $error = __( 'You did not solve the math problem. Please solve the math problem to proceed.', 'AWPCP' );
        } else if ( !$is_valid ) {
            $error = __( 'Your solution to the math problem was incorrect. Please try again.', 'AWPCP' );
        }

        return $is_valid;
    }
}

class AWPCP_reCAPTCHA extends AWPCP_CAPTCHA {

    private $site_key;
    private $secret_key;
    private $request;

    public function __construct( $site_key, $secret_key, $request ) {
        parent::__construct();

        $this->site_key = $site_key;
        $this->secret_key = $secret_key;

        $this->request = $request;
    }

    public function render() {
        if ( empty( $this->site_key ) ) {
            return $this->missing_key_message();
        }

        wp_enqueue_script(
            'awpcp-recaptcha',
            'https://www.google.com/recaptcha/api.js?onload=AWPCPreCAPTCHAonLoadCallback&render=explicit',
            array( 'awpcp' ),
            'v2',
            true
        );

        return $this->get_recaptcha_html( $this->site_key );
    }

    private function missing_key_message() {
        $message = __( 'To use reCAPTCHA you must get an API key from %s.', 'AWPCP' );
        $link = sprintf( '<a href="%1$s">%1$s</a>', 'https://www.google.com/recaptcha/admin' );
        return sprintf( $message, $link );
    }

    private function get_recaptcha_html( $site_key ) {
        return '<div class="g-recaptcha awpcp-recaptcha" data-sitekey="' . esc_attr( $site_key ) . '"></div>';
    }

    public function validate(&$error='') {
        if ( empty( $this->secret_key ) ) {
            $error = $this->missing_key_message();
            return false;
        }

        $response = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', array(
            'body' => array(
                'secret' => $this->secret_key,
                'response' => $this->request->post( 'g-recaptcha-response' ),
                $_SERVER['REMOTE_ADDR'],
            ),
        ) );

        if ( is_wp_error( $response ) ) {
            $message = __( 'There was an error trying to verify the reCAPTCHA answer. <reCAPTCHA-error>', 'AWPCP' );
            $error = str_replace( 'reCAPTCHA-error', $response->get_error_message(), $message );
            return false;
        }

        $json = json_decode( $response['body'], true );

        if ( $json['success'] ) {
            return true;
        } else if ( $json['error-codes'] ) {
            $error = $this->process_error_codes( $json['error-codes'] );
            return false;
        } else {
            $error = __( "Your answers couldn't be verified by the reCAPTCHA server.", 'AWPCP' );
            return false;
        }
    }

    private function process_error_codes( $error_codes ) {
        $errors = array();

        foreach ( $error_codes as $error_code ) {
            switch( $error_code ) {
                case 'missing-input-secret':
                    $errors[] = _x( 'The secret parameter is missing', 'recaptcha-error', 'AWPCP' );
                    break;
                case 'invalid-input-secret':
                    $errors[] = _x( 'The secret parameter is invalid or malformed.', 'recaptcha-error', 'AWPCP' );
                    break;
                case 'missing-input-response':
                    $errors[] = _x( 'The response parameter is missing.', 'recaptcha-error', 'AWPCP' );
                    break;
                case 'invalid-input-response':
                default:
                    $errors[] = _x( 'The response parameter is invalid or malformed.', 'recaptcha-error', 'AWPCP' );
                    break;
            }
        }

        return implode( ' ', $errors );
    }
}

function awpcp_create_captcha($type='default') {
    switch ($type) {
        case 'recaptcha':
            $site_key = get_awpcp_option( 'recaptcha-public-key' );
            $secret_key = get_awpcp_option( 'recaptcha-private-key' );

            return new AWPCP_reCAPTCHA( $site_key, $secret_key, awpcp_request() );

        case 'default':
        default:
            $max = get_awpcp_option( 'math-captcha-max-number' );
            return new AWPCP_DefaultCAPTCHA( $max );
    }
}
