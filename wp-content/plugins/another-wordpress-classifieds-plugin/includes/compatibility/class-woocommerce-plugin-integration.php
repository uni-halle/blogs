<?php

function awpcp_woocommerce_plugin_integration() {
    return new AWPCP_WooCommercePluginIntegration( awpcp_query(), awpcp()->settings );
}

class AWPCP_WooCommercePluginIntegration {

    private $query;
    private $settings;

    public function __construct( $query, $settings ) {
        $this->query = $query;
        $this->settings = $settings;
    }

    public function filter_prevent_admin_access( $prevent_access ) {
        if ( $this->settings->get_option( 'enable-user-panel' ) ) {
            return false;
        } else {
            return $prevent_access;
        }
    }

    public function filter_unforce_ssl_checkout( $value ) {
        if ( $value && $this->query->is_page_that_accepts_payments() ) {
            return false;
        } else {
            return $value;
        }
    }
}
