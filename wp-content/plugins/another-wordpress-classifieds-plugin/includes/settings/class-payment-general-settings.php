<?php

class AWPCP_PaymentGeneralSettings {

    public function register_settings( $settings ) {
        $key = $settings->add_section( 'payment-settings', __( 'Payment Settings', 'AWPCP' ), 'default', 10, array( $settings, 'section' ) );

        $order_options = array(
            1 => __( 'Name', 'AWPCP' ),
            2 => __( 'Price', 'AWPCP' ),
            3 => __( 'Images Allowed', 'AWPCP' ),
            5 => __( 'Duration', 'AWPCP' ),
        );

        $direction_options = array(
            'ASC' => __( 'Ascending', 'AWPCP' ),
            'DESC' => __( 'Descending', 'AWPCP' ),
        );

        $link = sprintf( '<a href="%s">', esc_attr( awpcp_get_admin_fees_url() ) );
        $helptext = __( 'When this is turned on, people will use <manage-fees-link>fee plans</a> to pay for your classifieds. Leave it off if you never want to charge for any ads.', 'AWPCP' );
        $helptext = str_replace( '<manage-fees-link>', $link, $helptext );

        $settings->add_setting( $key, 'freepay', __( 'Charge Listing Fee?', 'AWPCP' ), 'checkbox', 0, $helptext );
        $settings->add_setting( $key, 'fee-order', __( 'Fee Plan sort order', 'AWPCP' ), 'select', 1, __( 'The order used to sort Fees in the payment screens.', 'AWPCP' ), array( 'options' => $order_options ) );
        $settings->add_setting( $key, 'fee-order-direction', __( 'Fee Plan sort direction', 'AWPCP' ), 'select', 'ASC', __( 'The direction used to sort Fees in the payment screens.', 'AWPCP' ), array( 'options' => $direction_options ) );

        $settings->add_setting(
            $key,
            'hide-all-payment-terms-if-no-category-is-selected',
            __( 'Hide all fee plans if no category is selected', 'AWPCP' ),
            'checkbox',
            false,
            ''
        );

        $settings->add_setting( $key, 'pay-before-place-ad', _x( 'Pay before entering Ad details', 'settings', 'AWPCP' ), 'checkbox', 1, _x( 'Check to ask for payment before entering Ad details. Uncheck if you want users to pay for Ads at the end of the process, after images have been uploaded.', 'settings', 'AWPCP' ) );
        $settings->add_setting( $key, 'paylivetestmode', __( 'Put payment gateways in test mode?', 'AWPCP' ), 'checkbox', 0, __( 'Leave this OFF to accept real payments, turn it on to perform payment tests.', 'AWPCP' ) );
        $settings->add_setting( $key, 'force-secure-urls', __( 'Force secure URLs on payment pages', 'AWPCP' ), 'checkbox', 0, __( 'If checked all classifieds pages that involve payments will be accessed through a secure (HTTPS) URL. Do not enable this feature if your server does not support HTTPS.', 'AWPCP' ) );
    }

    public function validate_group_settings( $options, $group ) {
        // debugp( $options, $group );
        if ( isset( $options[ 'force-secure-urls' ] ) && $options[ 'force-secure-urls' ] ) {
            if ( $this->is_https_disabled() ) {
                $message = __( "Force Secure URLs was not enabled because your website couldn't be accessed using a secure connection.", 'AWPCP' );
                awpcp_flash( $message, 'error' );

                $options['force-secure-urls'] = 0;
            }
        }

        return $options;
    }

    public function is_https_disabled() {
        $url = set_url_scheme( awpcp_get_page_url( 'place-ad-page-name' ), 'https' );
        $response = wp_remote_get( $url, array( 'timeout' => 30 ) );

        if ( ! is_wp_error( $response ) ) {
            return false;
        }

        if ( ! isset( $response->errors ) || ! isset( $response->errors['http_request_failed'] ) ) {
            return false;
        }

        foreach ( (array) $response->errors['http_request_failed'] as $error ) {
            if ( false === strpos( $error, 'Connection refused' ) ) {
                return false;
            }
        }

        return true;
    }
}
