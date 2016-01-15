<?php

function awpcp_license_settings_actions_request_handler() {
    return new AWPCP_License_Settings_Actions_Request_Handler(
        awpcp_licenses_manager(),
        awpcp_modules_manager(),
        awpcp_request()
    );
}

class AWPCP_License_Settings_Actions_Request_Handler {

    private $licenses_manager;
    private $modules_manager;
    private $request;

    public function __construct( $licenses_manager, $modules_manager, $request ) {
        $this->licenses_manager = $licenses_manager;
        $this->modules_manager = $modules_manager;
        $this->request = $request;
    }

    public function dispatch( $location ) {
        if ( wp_verify_nonce( $this->request->post( 'awpcp-update-license-status-nonce' ), 'awpcp-update-license-status-nonce' ) ) {
            $this->process_settings_actions_for_modules( $this->modules_manager->get_modules() );
        }

        return $location;
    }

    private function process_settings_actions_for_modules( $modules ) {
        foreach ( $modules as $module_slug => $module ) {
            $license_setting_name = $this->licenses_manager->get_license_setting_name( $module_slug );
            $sanitized_setting_name = str_replace( '.', '_', $license_setting_name );

            if ( $this->request->post( "awpcp-check-$sanitized_setting_name", false ) ) {
                $this->refresh_license_status( $module->name, $module_slug );
            } else if ( $this->request->post( "awpcp-activate-$sanitized_setting_name", false ) ) {
                $this->licenses_manager->activate_license( $module->name, $module_slug );
            } else if ( $this->request->post( "awpcp-deactivate-$sanitized_setting_name", false ) ) {
                $this->licenses_manager->deactivate_license( $module->name, $module_slug );
            }
        }
    }

    private function refresh_license_status( $module_name, $module_slug ) {
        $this->licenses_manager->drop_license_status( $module_slug );
        $this->licenses_manager->get_license_status( $module_name, $module_slug );
    }
}
