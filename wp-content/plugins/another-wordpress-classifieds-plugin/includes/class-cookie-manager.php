<?php

function awpcp_cookie_manager() {
    return new AWPCP_CookieManager();
}

class AWPCP_CookieManager {

    public function set_cookie( $name, $value, $expire = 0 ) {
        $serialized_value = maybe_serialize( $value );
        $encoded_value = base64_encode( $serialized_value );

        if ( is_ssl() && 'https' === parse_url( get_option( 'home' ), PHP_URL_SCHEME ) ) {
            $secure = true;
        } else {
            $secure = false;
        }

        $this->_set_cookie( $name, $encoded_value, $expire, $secure );
    }

    private function _set_cookie( $name, $value, $expire, $secure = true ) {
        setcookie( $name, $value, $expire, COOKIEPATH, COOKIE_DOMAIN, $secure, true );
        setcookie( $name, $value, $expire, SITECOOKIEPATH, COOKIE_DOMAIN, $secure, true );
    }

    public function get_cookie( $name ) {
        $encoded_value = isset( $_COOKIE[ $name ] ) ? $_COOKIE[ $name ] : '';
        $serialized_value = base64_decode( $encoded_value );
        return maybe_unserialize( $serialized_value );
    }

    public function clear_cookie( $name ) {
        $this->_set_cookie( $name, ' ', time() - YEAR_IN_SECONDS );
    }
}
