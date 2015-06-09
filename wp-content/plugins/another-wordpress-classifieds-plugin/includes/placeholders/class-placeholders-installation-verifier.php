<?php

class AWPCP_PlaceholdersInstallationVerifier {

    private $settings;

    public function __construct( $settings ) {
        $this->settings = $settings;
    }

    public function check_placeholder_installation() {
    }

    protected function is_placeholder_missing( $placeholder ) {
        return strpos( $this->settings->get_option( 'awpcpshowtheadlayout' ), $placeholder ) === false;
    }

    protected function show_missing_placeholder_notice( $warning_message ) {
        $warning_message = sprintf( '<strong>%s:</strong> %s', __( 'Warning', 'awpcp-attachments' ), $warning_message );

        $url = awpcp_get_admin_settings_url( 'listings-settings' );
        $link = sprintf( '<a href="%s">%s</a>', $url, __( 'Ad/Listings settings page', 'awpcp-attachments' ) );
        $go_to_settings_message = sprintf( __( 'Go to the %s to change the Single Ad layout.', 'awpcp-attachments' ), $link );

        echo awpcp_print_error( sprintf( '%s<br/><br/>%s', $warning_message, $go_to_settings_message ) );
    }
}
