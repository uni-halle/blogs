<?php

class AWPCP_CreditPlansSettings {

    public function register_settings( $settings ) {
        $key = $settings->add_section( 'payment-settings' , __( 'Credit System', 'AWPCP' ), 'credit-system', 5, array( $settings, 'section' ) );

        $options = array(
            AWPCP_Payment_Transaction::PAYMENT_TYPE_MONEY => __( 'Currency', 'AWPCP' ),
            AWPCP_Payment_Transaction::PAYMENT_TYPE_CREDITS => __( 'Credits', 'AWPCP' ),
            'both' => __( 'Currency & Credits', 'AWPCP' ),
        );

        $settings->add_setting( $key, 'enable-credit-system', __( 'Enable Credit System', 'AWPCP'), 'checkbox', 0, __( 'The Credit System allow users to purchase credit that can later be used to pay for placing Ads.', 'AWPCP' ) );
        $settings->add_setting( $key, 'accepted-payment-type', __( 'Accepted payment type', 'AWPCP' ), 'select', 'both', __( 'Select the type of payment that can be used to purchase Ads.', 'AWPCP' ), array( 'options' => $options ) );
    }
}
