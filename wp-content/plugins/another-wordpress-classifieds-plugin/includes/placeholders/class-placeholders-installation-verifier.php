<?php

class AWPCP_PlaceholdersInstallationVerifier {

    private $settings;

    public function __construct( $settings ) {
        $this->settings = $settings;
    }

    public function check_placeholder_installation() {
    }

    protected function is_placeholder_missing( $placeholder ) {
        return $this->is_placeholder_missing_in_single_listing_layout( $placeholder );
    }

    protected function is_placeholder_missing_in_single_listing_layout( $placeholder ) {
        return $this->is_placeholder_missing_in_setting( 'awpcpshowtheadlayout' , $placeholder );
    }

    protected function is_placeholder_missing_in_listings_layout( $placeholder ) {
        return $this->is_placeholder_missing_in_setting( 'displayadlayoutcode' , $placeholder );
    }

    private function is_placeholder_missing_in_setting( $setting_name, $placeholder ) {
        return strpos( $this->settings->get_option( $setting_name ), $placeholder ) === false;
    }

    protected function show_missing_placeholder_notice( $warning_message ) {
        $warning_message = sprintf( '<strong>%s:</strong> %s', __( 'Warning', 'another-wordpress-classifieds-plugin' ), $warning_message );

        $url = awpcp_get_admin_settings_url( 'listings-settings' );
        $link = sprintf( '<a href="%s">%s</a>', $url, __( 'Ad/Listings settings page', 'another-wordpress-classifieds-plugin' ) );
        $go_to_settings_message = sprintf( __( 'Go to the %s to change the Single Ad layout.', 'another-wordpress-classifieds-plugin' ), $link );

        echo awpcp_print_error( sprintf( '%s<br/><br/>%s', $warning_message, $go_to_settings_message ) );
    }
}
