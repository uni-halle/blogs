<?php

function awpcp_woocommerce_plugin_integration() {
    return new AWPCP_WooCommercePluginIntegration( awpcp()->settings );
}

class AWPCP_WooCommercePluginIntegration {

    private $settings;

    public function __construct( $settings ) {
        $this->settings = $settings;
    }

    public function filter_prevent_admin_access( $prevent_access ) {
        if ( $this->settings->get_option( 'enable-user-panel' ) ) {
            return false;
        } else {
            return $prevent_access;
        }
    }
}
