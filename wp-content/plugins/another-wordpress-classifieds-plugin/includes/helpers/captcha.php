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

    public function __construct($public_key, $private_key) {
        parent::__construct();

        // lazy load reCAPTCHA to avoid conflicts with other plugins
        if ( !function_exists( 'recaptcha_get_html' ) ) {
            require_once( AWPCP_DIR . '/vendors/recaptcha/recaptchalib.php' );
        }

        $this->public_key = $public_key;
        $this->private_key = $private_key;
    }

    private function missing_key_message() {
        $message = __( 'To use reCAPTCHA you must get an API key from %s.', 'AWPCP' );
        $link = sprintf( '<a href="%1$s">%1$s</a>', 'https://www.google.com/recaptcha/admin/create' );
        return sprintf( $message, $link );
    }

    private function missing_ip_message() {
        return __( 'For security reasons, you must pass the remote ip to reCAPTCHA.', 'AWPCP' );
    }

    public function render() {
        if ( empty( $this->public_key ) ) {
            return $this->missing_key_message();
        }

        return recaptcha_get_html( $this->public_key );
    }

    public function validate(&$error='') {
        if ( empty( $this->private_key ) ) {
            $error = $this->missing_key_message();
            return false;
        }

        $response = recaptcha_check_answer( $this->private_key,
                                            $_SERVER['REMOTE_ADDR'],
                                            awpcp_post_param( 'recaptcha_challenge_field' ),
                                            awpcp_post_param( 'recaptcha_response_field' ) );

        if ( !$response->is_valid ) {
            $error = __( "The characters in the image weren't entered correctly. Please try it again.", 'AWPCP' );
        }

        return $response->is_valid;
    }
}


function awpcp_create_captcha($type='default') {
    switch ($type) {
        case 'recaptcha':
            $public_key = get_awpcp_option( 'recaptcha-public-key' );
            $private_key = get_awpcp_option( 'recaptcha-private-key' );

            return new AWPCP_reCAPTCHA( $public_key, $private_key );

        case 'default':
        default:
            $max = get_awpcp_option( 'math-captcha-max-number' );
            return new AWPCP_DefaultCAPTCHA( $max );
    }
}
