<?php

function awpcp_licenses_manager() {
    return new AWPCP_LicensesManager( awpcp_easy_digital_downloads(), awpcp()->settings );
}

class AWPCP_LicensesManager {

    const LICENSE_STATUS_VALID = 'valid';
    const LICENSE_STATUS_EXPIRED = 'expired';
    const LICENSE_STATUS_INACTIVE = 'inactive';
    const LICENSE_STATUS_SITE_INACTIVE = 'site_inactive';
    const LICENSE_STATUS_DEACTIVATED = 'deactivated';

    private $edd;
    private $settings;

    public function __construct( $edd, $settings ) {
        $this->edd = $edd;
        $this->settings = $settings;
    }

    public function set_module_license( $module_slug, $license ) {
        $this->settings->set_or_update_option( "{$module_slug}-license", $license );
        $this->drop_license_status( $module_slug );
    }

    public function get_module_license( $module_slug ) {
        return $this->settings->get_option( "{$module_slug}-license" );
    }

    public function is_license_valid( $module_name, $module_slug ) {
        return $this->get_license_status( $module_name, $module_slug ) === self::LICENSE_STATUS_VALID;
    }

    private function get_license_status( $module_name, $module_slug ) {
        static $cache = array();

        if ( ! isset( $cache[ $module_slug ] ) ) {
            $cache[ $module_slug ] = $this->calculate_license_status( $module_name, $module_slug );
        }

        return $cache[ $module_slug ];
    }

    private function calculate_license_status( $module_name, $module_slug ) {
        $license_status = get_site_transient( $this->get_license_status_transient_key( $module_slug ) );

        if ( $license_status !== false ) {
            return $license_status;
        }

        try {
            $license_status = $this->get_license_status_from_store( $module_name, $module_slug );
        } catch ( AWPCP_Exception $e ) {
            $license_status = $this->settings->get_option( "{$module_slug}-license-status" );
            awpcp_flash( $e->format_errors() );
        }

        return $license_status;
    }

    private function get_license_status_transient_key( $module_slug ) {
        // using abbreviations (ls: license-status) because transient keys
        // should be 40 characters long or less.
        return "{$module_slug}-ls";
    }

    private function get_license_status_from_store( $module_name, $module_slug ) {
        $license_information = $this->edd->check_license( $module_name, $this->get_module_license( $module_slug ) );
        $license_status = $license_information->license;

        $this->update_license_status( $module_slug, $license_status );

        return $license_status;
    }

    private function update_license_status( $module_slug, $license_status ) {
        set_site_transient( $this->get_license_status_transient_key( $module_slug ), $license_status, DAY_IN_SECONDS );
        $this->settings->set_or_update_option( "{$module_slug}-license-status", $license_status );
    }

    public function is_license_inactive( $module_name, $module_slug ) {
        $license_status = $this->get_license_status( $module_name, $module_slug );

        if ( $license_status === self::LICENSE_STATUS_INACTIVE ) {
            return true;
        }

        if ( $license_status === self::LICENSE_STATUS_SITE_INACTIVE ) {
            return true;
        }

        if ( $license_status === self::LICENSE_STATUS_DEACTIVATED ) {
            return true;
        }

        return false;
    }

    public function is_license_expired( $module_name, $module_slug ) {
        return $this->get_license_status( $module_name, $module_slug ) === self::LICENSE_STATUS_EXPIRED;
    }

    public function activate_license( $module_name, $module_slug ) {
        try {
            $response = $this->edd->activate_license( $module_name, $this->get_module_license( $module_slug ) );
            $this->update_license_status( $module_slug, $response->license );
            return $response->license === self::LICENSE_STATUS_VALID;
        } catch ( AWPCP_Exception $e ) {
            $this->drop_license_status( $module_slug );
            awpcp_flash( $e->format_errors() );
            return false;
        }
    }

    private function drop_license_status( $module_slug ) {
        delete_site_transient( $this->get_license_status_transient_key( $module_slug ) );
        $this->settings->set_or_update_option( "{$module_slug}-license-status", false );
    }

    public function deactivate_license( $module_name, $module_slug ) {
        try {
            $response = $this->edd->deactivate_license( $module_name, $this->get_module_license( $module_slug ) );
            $this->update_license_status( $module_slug, $response->license );
            return $response->license === self::LICENSE_STATUS_DEACTIVATED;
        } catch ( AWPCP_Exception $e ) {
            $this->drop_license_status( $module_slug );
            awpcp_flash( $e->format_errors() );
            return false;
        }
    }
}
