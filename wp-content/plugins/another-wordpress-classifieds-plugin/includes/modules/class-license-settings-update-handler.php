<?php

function awpcp_license_settings_update_handler() {
    return new AWPCP_License_Settings_Update_Handler(
        awpcp_licenses_manager(),
        awpcp_modules_manager()
    );
}

class AWPCP_License_Settings_Update_Handler {

    private $licenses_manager;
    private $modules_manager;

    public function __construct( $licenses_manager, $modules_manager ) {
        $this->licenses_manager = $licenses_manager;
        $this->modules_manager = $modules_manager;
    }

    public function process_settings( $old_settings, $new_settings ) {
        $modules = $this->modules_manager->get_modules();

        foreach ( $modules as $module_slug => $module ) {
            $license_setting_name = $this->licenses_manager->get_license_setting_name( $module_slug );

            $old_license = isset( $old_settings[ $license_setting_name ] ) ? $old_settings[ $license_setting_name ] : '';
            $new_license = isset( $new_settings[ $license_setting_name ] ) ? $new_settings[ $license_setting_name ] : '';

            if ( strcmp( $new_license, $old_license ) !== 0 ) {
                $this->update_license_status( $module_slug, $module->name, $new_license );
            }
        }
    }

    private function update_license_status( $module_slug, $module_name, $new_license ) {
        if ( ! empty( $new_license ) ) {
            $this->licenses_manager->activate_license( $module_name, $module_slug );
        } else {
            $this->licenses_manager->drop_license_status( $module_slug );
        }
    }
}
