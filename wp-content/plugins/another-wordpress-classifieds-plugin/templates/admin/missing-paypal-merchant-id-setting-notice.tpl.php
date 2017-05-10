<div id="missing-paypal-merchant-id-setting-notice" class="notice notice-warning is-dismissible awpcp-notice">
    <?php echo awpcp_html_admin_second_level_heading( array( 'content' => __( "What's your PayPal Merchant ID?", 'another-wordpress-classifieds-plugin' ) ) ); ?>
    <p><?php echo __( 'In order to verify payments made through PayPal, Another WordPress Classifieds Plugin needs to know your PayPal Merchant ID.', 'another-wordpress-classifieds-plugin' ); ?></p>
    <p><?php
        $message = __( 'Go to <paypal-link> to obtain the Merchant ID and then go to the <payment-settings-link>Payment Settings</payment-settings-link> page to enter the value.', 'another-wordpress-classifieds-plugin' );

        $payment_settings_link = awpcp_get_admin_settings_url( 'payment-settings' );

        $message = str_replace( '<paypal-link>', '<a href="https://www.paypal.com/myaccount/settings/" target="_blank">https://www.paypal.com/myaccount/settings/</a>', $message );
        $message = str_replace( '<payment-settings-link>', '<a href="' . $payment_settings_link . '">', $message );
        $message = str_replace( '</payment-settings-link>', '</a>', $message );

        echo $message;
    ?></p>
</div>
