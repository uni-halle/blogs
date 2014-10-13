<?php

function awpcp_update_license_status_request_handler() {
    return new AWPCP_UpdateLicenseStatusRequestHandler( awpcp_licenses_manager(), awpcp_modules_manager(), awpcp_request() );
}

class AWPCP_UpdateLicenseStatusRequestHandler {

    private $licenses_manager;
    private $modules_manager;
    private $request;

    public function __construct( $licenses_manager, $modules_manager, $request ) {
        $this->licenses_manager = $licenses_manager;
        $this->modules_manager = $modules_manager;
        $this->request = $request;
    }

    public function dispatch() {
        if ( wp_verify_nonce( $this->request->post( 'awpcp-update-license-status-nonce' ), 'awpcp-update-license-status-nonce' ) ) {
            $this->handle_request();
        }
    }

    private function handle_request() {
        foreach ( $this->request->post( 'awpcp-options' ) as $option_name => $new_license ) {
            $module_slug = str_replace( '-license', '', $option_name );
            $old_license = $this->licenses_manager->get_module_license( $module_slug );

            if ( strcmp( $new_license, $old_license ) !== 0 ) {
                $this->update_license( $module_slug, $new_license );
            } else if ( $this->request->post( "awpcp-check-$option_name", false ) ) {
                $this->check_license( $module_slug, $new_license );
            } else if ( $this->request->post( "awpcp-activate-$option_name", false ) ) {
                $this->activate_license( $module_slug );
            } else if ( $this->request->post( "awpcp-deactivate-$option_name", false ) ) {
                $this->deactivate_license( $module_slug );
            }
        }
    }

    private function update_license( $module_slug, $new_license ) {
        if ( ! empty( $new_license ) ) {
            $this->licenses_manager->set_module_license( $module_slug, $new_license );
            $this->activate_license( $module_slug );
        } else {
            $this->licenses_manager->set_module_license( $module_slug, $new_license );
        }
    }

    private function check_license( $module_slug, $license ) {
        // calling set module license causes the license manager to drop
        // the saved status, forcing it to check with the store in the next
        // request.
        $this->licenses_manager->set_module_license( $module_slug, $license );
    }

    private function activate_license( $module_slug ) {
        $module = $this->modules_manager->get_module( $module_slug );
        $this->licenses_manager->activate_license( $module->name, $module->slug );
    }

    private function deactivate_license( $module_slug ) {
        $module = $this->modules_manager->get_module( $module_slug );
        $this->licenses_manager->deactivate_license( $module->name, $module->slug );
    }
}
