<?php

/**
 * @since 3.0
 */
class AWPCP_JavaScript {

    private static $instance = null;

    private $data;
    private $l10n;

    private function __construct() {
        $this->data = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
        $this->l10n = array();
    }

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new AWPCP_JavaScript();
        }
        return self::$instance;
    }

    public function set($key, $value, $replace=true) {
        if ( $replace || !isset( $this->data[ $key ] ) ) {
            $this->data[ $key ] = $value;
        }
    }

    public function get_data() {
        return $this->data;
    }

    public function localize($context, $key, $value=null) {
        if ( is_array( $key ) && isset( $this->l10n[ $context ] ) ) {
            $this->l10n[ $context ] = array_merge( $this->l10n[ $context ], $key );
        } else if ( is_array( $key ) ) {
            $this->l10n[ $context ] = $key;
        } else {
            $this->l10n[ $context ][ $key ] = $value;
        }
    }

    public function get_l10n() {
        return $this->l10n;
    }
}
