<?php

function awpcp_missing_paypal_merchant_id_setting_notice() {
    return new AWPCP_Missing_PayPal_Merchant_ID_Setting_Notice(
        awpcp()->settings,
        awpcp_request()
    );
}

class AWPCP_Missing_PayPal_Merchant_ID_Setting_Notice {

    private $settings;
    private $request;

    public function __construct( $settings, $request ) {
        $this->settings = $settings;
        $this->request = $request;
    }

    public function maybe_show_notice() {
        if ( ! get_option( 'awpcp-show-missing-paypal-merchant-id-setting-notice' ) ) {
            return;
        }

        if ( ! $this->settings->get_option( 'freepay', false ) ) {
            return;
        }

        if ( ! $this->settings->get_option( 'activatepaypal', false ) ) {
            return;
        }

        if ( $this->settings->get_option( 'paypal_merchant_id', false ) ) {
            return;
        }

        $page = $this->request->get( 'page' );

        if ( 'awpcp-admin-upgrade' === $page || 'awpcp' != substr( $page, 0, 5 ) ) {
            return;
        }

        echo $this->render_notice();
    }

    private function render_notice() {
        $template = AWPCP_DIR . '/templates/admin/missing-paypal-merchant-id-setting-notice.tpl.php';
        return awpcp_render_template( $template, array() );
    }
}
