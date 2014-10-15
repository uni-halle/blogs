<?php

function awpcp_modules_manager() {
    static $instance = null;

    if ( is_null( $instance ) ) {
        $instance = new AWPCP_ModulesManager( awpcp(), awpcp_licenses_manager(), awpcp_modules_updater(), awpcp()->settings );
    }

    return $instance;
}

class AWPCP_ModulesManager {

    private $plugin;
    private $licenses_manager;
    private $modules_updater;
    private $settings;

    private $modules = array();

    public function __construct( $plugin, $licenses_manager, $modules_updater, $settings ) {
        $this->plugin = $plugin;
        $this->licenses_manager = $licenses_manager;
        $this->modules_updater = $modules_updater;
        $this->settings = $settings;
    }

    public function load_modules() {
        do_action( 'awpcp-load-modules', $this );
    }

    public function load( $module ) {
        $this->modules[ $module->slug ] = $module;

        try {
            $this->load_module( $module );
        } catch ( AWPCP_Exception $e ) {
            // pass
        }
    }

    private function load_module( $module ) {
        $module->load_textdomain();
        $this->verify_version_compatibility( $module );

        if ( $this->is_premium_module( $module ) ) {
            $this->settings->add_license_setting( $module->name, $module->slug );
            // $this->verify_license_status( $module );
        }

        $this->handle_module_updates( $module );
        $module->setup( $this->plugin );
    }

    private function verify_version_compatibility( $module ) {
        $modules = $this->plugin->get_premium_modules_information();

        if ( ! isset( $modules[ $module->slug ] ) ) {
            $module->notices[] = 'module-is-not-registered-notice';
            throw new AWPCP_Exception( 'Module is not registered.' );
        }

        if ( version_compare( $this->plugin->version, $module->required_awpcp_version, '<' ) ) {
            $module->notices[] = 'required-awpcp-version-notice';
            throw new AWPCP_Exception( 'Required AWPCP version not installed.' );
        }

        if ( ! $this->plugin->is_compatible_with( $module->slug, $module->version ) ) {
            $module->notices[] = 'module-not-compatible-notice';
            throw new AWPCP_Exception( 'Module not compatible with installed AWPCP version.' );
        }
    }

    private function is_premium_module( $module ) {
        if ( strcmp( $module->slug, 'xml-sitemap') === 0 ) {
            return false;
        } else {
            return true;
        }
    }

    private function verify_license_status( $module ) {
        if ( $this->licenses_manager->is_license_inactive( $module->name, $module->slug ) ) {
            $module->notices[] = 'inactive-license-notice';
            throw new AWPCP_Exception( "Module's license is inactive." );
        } else if ( ! $this->module_has_an_accepted_license( $module ) ) {
            $module->notices[] = 'invalid-license-notice';
            throw new AWPCP_Exception( 'Module has not valid license.' );
        }

        if ( $this->licenses_manager->is_license_expired( $module->name, $module->slug ) ) {
            $module->notices[] = 'expired-license-notice';
        }
    }

    private function module_has_an_accepted_license( $module ) {
        if ( $this->licenses_manager->is_license_valid( $module->name, $module->slug ) ) {
            return true;
        }

        if ( $this->licenses_manager->is_license_expired( $module->name, $module->slug ) ) {
            return true;
        }

        return false;
    }

    private function handle_module_updates( $module ) {
        $license = $this->licenses_manager->get_module_license( $module->slug );
        $this->modules_updater->watch( $module, $license );
    }

    public function show_admin_notices() {
        if ( ! awpcp_current_user_is_admin() ) {
            return;
        }

        foreach ( $this->modules as $module ) {
            $this->show_module_notices( $module );
        }
    }

    private function show_module_notices( $module ) {
        if ( in_array( 'required-awpcp-version-notice', $module->notices ) ) {
            return $module->required_awpcp_version_notice();
        }

        if ( in_array( 'module-not-compatible-notice', $module->notices ) ) {
            echo $this->show_module_not_compatible_notice( $module->slug );
        }

        if ( in_array( 'invalid-license-notice', $module->notices ) ) {
            echo $this->show_invalid_license_notice( $module->name );
        }

        if ( in_array( 'inactive-license-notice', $module->notices ) ) {
            echo $this->show_inactive_license_notice( $module->name );
        }

        if ( in_array( 'expired-license-notice', $module->notices ) ) {
            echo $this->show_expired_license_notice( $module->name );
        }

        if ( in_array( 'module-is-not-registered-notice', $module->notices ) ) {
            echo $this->show_module_no_registered_notice( $module->name );
        }
    }

    private function show_module_not_compatible_notice( $module_slug ) {
        $modules = $this->plugin->get_premium_modules_information();

        $module_name = $modules[ $module_slug ][ 'name' ];
        $required_version = $modules[ $module_slug ][ 'required' ];

        $message = __( 'This version of AWPCP %1$s module is not compatible with AWPCP version %2$s. Please get AWPCP %1$s %3$s or newer!', 'AWPCP' );
        $message = sprintf( $message, '<strong>' . $module_name . '</strong>', $this->plugin->version, '<strong>' . $required_version . '</strong>' );
        $message = sprintf( '<strong>%s:</strong> %s', __( 'Error', 'AWPCP' ), $message );

        return awpcp_print_error( $message );
    }

    private function show_invalid_license_notice( $module_name ) {
        $link = sprintf( '<a href="%s">', awpcp_get_admin_settings_url( 'licenses-settings' ) );

        $message = __( 'The AWPCP <module-name> module requires a license to be used. All features will remain disabled until a valid license is entered. Please go to the <licenses-settings-link>Licenses Settings</a> section to enter or update your license.', 'AWPCP' );
        $message = str_replace( '<module-name>', '<strong>' . $module_name . '</strong>', $message );
        $message = str_replace( '<licenses-settings-link>', $link, $message );

        return awpcp_print_error( $message );
    }

    private function show_inactive_license_notice( $module_name ) {
        $link = sprintf( '<a href="%s">', awpcp_get_admin_settings_url( 'license-settings' ) );

        $message = __( 'The license for AWPCP <module-name> module is inactive. All features will remain disabled until you activate the license. Please go to the <license-settings-link>License Settings</a> section to acivate your license.', 'AWPCP' );
        $message = str_replace( '<module-name>', '<strong>' . $module_name . '</strong>', $message );
        $message = str_replace( '<license-settings-link>', $link, $message );

        return awpcp_print_error( $message );
    }

    private function show_expired_license_notice( $module_name ) {
        $message = __( 'The license for AWPCP <module-name> module expired. The module will continue to work but you will not receive automatic updates when a new version is available.', 'AWPCP' );
        $message = str_replace( '<module-name>', '<strong>' . $module_name . '</strong>', $message );

        return awpcp_print_error( $message );
    }

    private function show_module_no_registered_notice( $module_name ) {
        $message = __( 'Yikes, there has been a mistake. It looks like you have an outdated version of AWPCP <module-name> module. Please contact customer support and ask for a newer version. Please also include a reference to this error in your message.', 'AWPCP' );
        $message = str_replace( '<module-name>', '<strong>' . $module_name . '</strong>', $message );

        return awpcp_print_error( $message );
    }

    public function get_module( $module_slug ) {
        if ( ! isset( $this->modules[ $module_slug ] ) ) {
            throw new AWPCP_Exception( __( 'The specified module does not exists!.', 'AWPCP' ) );
        }

        return $this->modules[ $module_slug ];
    }
}
