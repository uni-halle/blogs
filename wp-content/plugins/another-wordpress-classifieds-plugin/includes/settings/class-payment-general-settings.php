<?php

class AWPCP_PaymentGeneralSettings {

    public function register_settings( $settings ) {
        $key = $settings->add_section( 'payment-settings', __( 'Payment Settings', 'another-wordpress-classifieds-plugin' ), 'default', 10, array( $settings, 'section' ) );

        $link = sprintf( '<a href="%s">', esc_attr( awpcp_get_admin_fees_url() ) );
        $helptext = __( 'When this is turned on, people will use <manage-fees-link>fee plans</a> to pay for your classifieds. Leave it off if you never want to charge for any ads.', 'another-wordpress-classifieds-plugin' );
        $helptext = str_replace( '<manage-fees-link>', $link, $helptext );

        $settings->add_setting( $key, 'freepay', __( 'Charge Listing Fee?', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, $helptext );

        $order_options = array(
            1 => __( 'Name', 'another-wordpress-classifieds-plugin' ),
            2 => __( 'Price', 'another-wordpress-classifieds-plugin' ),
            3 => __( 'Images Allowed', 'another-wordpress-classifieds-plugin' ),
            5 => __( 'Duration', 'another-wordpress-classifieds-plugin' ),
        );

        $settings->add_setting(
            $key,
            'fee-order',
            __( 'Fee Plan sort order', 'another-wordpress-classifieds-plugin' ),
            'radio',
            1,
            __( 'The order used to sort Fees in the payment screens.', 'another-wordpress-classifieds-plugin' ),
            array( 'options' => $order_options )
        );

        $direction_options = array(
            'ASC' => __( 'Ascending', 'another-wordpress-classifieds-plugin' ),
            'DESC' => __( 'Descending', 'another-wordpress-classifieds-plugin' ),
        );

        $settings->add_setting(
            $key,
            'fee-order-direction',
            __( 'Fee Plan sort direction', 'another-wordpress-classifieds-plugin' ),
            'radio',
            'ASC',
            __( 'The direction used to sort Fees in the payment screens.', 'another-wordpress-classifieds-plugin' ),
            array( 'options' => $direction_options )
       );

        $settings->add_setting(
            $key,
            'hide-all-payment-terms-if-no-category-is-selected',
            __( 'Hide all fee plans if no category is selected', 'another-wordpress-classifieds-plugin' ),
            'checkbox',
            false,
            ''
        );

        $settings->add_setting( $key, 'pay-before-place-ad', _x( 'Pay before entering Ad details', 'settings', 'another-wordpress-classifieds-plugin' ), 'checkbox', 1, _x( 'Check to ask for payment before entering Ad details. Uncheck if you want users to pay for Ads at the end of the process, after images have been uploaded.', 'settings', 'another-wordpress-classifieds-plugin' ) );
        $settings->add_setting( $key, 'paylivetestmode', __( 'Put payment gateways in test mode?', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'Leave this OFF to accept real payments, turn it on to perform payment tests.', 'another-wordpress-classifieds-plugin' ) );
        $settings->add_setting( $key, 'force-secure-urls', __( 'Force secure URLs on payment pages', 'another-wordpress-classifieds-plugin' ), 'checkbox', 0, __( 'If checked all classifieds pages that involve payments will be accessed through a secure (HTTPS) URL. Do not enable this feature if your server does not support HTTPS.', 'another-wordpress-classifieds-plugin' ) );
    }

    public function validate_group_settings( $options, $group ) {
        // debugp( $options, $group );
        if ( isset( $options[ 'force-secure-urls' ] ) && $options[ 'force-secure-urls' ] ) {
            if ( $this->is_https_disabled() ) {
                $message = __( "Force Secure URLs was not enabled because your website couldn't be accessed using a secure connection.", 'another-wordpress-classifieds-plugin' );
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
