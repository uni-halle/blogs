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
        $key = $settings->add_section( 'payment-settings' , __( 'Credit System', 'another-wordpress-classifieds-plugin' ), 'credit-system', 5, array( $settings, 'section' ) );

        $options = array(
            AWPCP_Payment_Transaction::PAYMENT_TYPE_MONEY => __( 'Currency', 'another-wordpress-classifieds-plugin' ),
            AWPCP_Payment_Transaction::PAYMENT_TYPE_CREDITS => __( 'Credits', 'another-wordpress-classifieds-plugin' ),
            'both' => __( 'Currency & Credits', 'another-wordpress-classifieds-plugin' ),
        );

        $settings->add_setting( $key, 'enable-credit-system', __( 'Enable Credit System', 'another-wordpress-classifieds-plugin'), 'checkbox', 0, __( 'The Credit System allows users to purchase credit that can later be used to pay for placing Ads.', 'another-wordpress-classifieds-plugin' ) );

        $settings->add_setting(
            $key,
            'accepted-payment-type',
            __( 'Accepted payment type', 'another-wordpress-classifieds-plugin' ),
            'radio',
            'both',
            __( 'Select the type of payment that can be used to purchase Ads.', 'another-wordpress-classifieds-plugin' ),
            array( 'options' => $options )
        );
    }

    public function validate_credit_system_settings( $options, $group ) {
        $credits_is_the_only_accepted_payment_type = $options['accepted-payment-type'] == AWPCP_Payment_Transaction::PAYMENT_TYPE_CREDITS;
        $credit_system_will_be_enabled = $options['enable-credit-system'];

        if ( $credits_is_the_only_accepted_payment_type && ! $credit_system_will_be_enabled ) {
            $options['accepted-payment-type'] = 'both';

            $message = __( 'You cannot configure Credits as the only accepted payment type unless you enable the Credit System as well. The setting was set to accept both Currency and Credits.', 'another-wordpress-classifieds-plugin' );
            awpcp_flash( $message, array( 'error' ) );
        }

        return $options;
    }
}
