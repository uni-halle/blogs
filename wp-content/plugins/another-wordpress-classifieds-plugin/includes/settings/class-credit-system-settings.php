<?php

function awpcp_credit_system_settings() {
    return new AWPCP_CreditSystemSettings( awpcp()->settings );
}

class AWPCP_CreditSystemSettings {

    private $settings;

    public function __construct( $settings ) {
        $this->settings = $settings;
    }

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

    public function validate_credit_system_settings( $options, $group ) {
        $credits_is_the_only_accepted_payment_type = $options['accepted-payment-type'] == AWPCP_Payment_Transaction::PAYMENT_TYPE_CREDITS;
        $credit_system_will_be_enabled = $options['enable-credit-system'];

        if ( $credits_is_the_only_accepted_payment_type && ! $credit_system_will_be_enabled ) {
            $options['accepted-payment-type'] = 'both';

            $message = __( 'You cannot configure Credits as the only accepted payment type unless you unable the Credit System as well. The setting was set to accept both Currency and Credits.', 'AWPCP' );
            awpcp_flash( $message, array( 'error' ) );
        }

        return $options;
    }
}
