<?php

// uncomment this line for testing
// set_site_transient( 'update_plugins', null );

/**
 * @since 3.3
 */
function awpcp_modules_updater() {
    static $instance = null;

    if ( is_null( $instance ) ) {
        $instance = new AWPCP_ModulesUpdater( awpcp_easy_digital_downloads() );
    }

    return $instance;
}

/**
 * An adaptation of EDD_SL_Plugin_Updater to handle
 * multiple modules.
 *
 * @since 3.3
 */
class AWPCP_ModulesUpdater {

    private $modules = array();
    private $modules_information = array();
    private $up_to_date_modules = array();

    private $edd;

    public function __construct( $edd ) {
        $this->edd = $edd;
    }

    public function watch( $module, $license ) {
        $this->modules[ $module->slug ] = array(
            'instance' => $module,
            'basename' => plugin_basename( $module->file ),
            'license' => $license,
        );
    }

    public function filter_plugins_version_information( $plugins_information ) {
        if ( empty( $plugins_information ) || ! isset( $plugins_information->response ) ) {
            return $plugins_information;
        }

        foreach ( $this->modules as $module ) {
            $plugins_information = $this->filter_version_information_for_module( $module, $plugins_information );
        }

        return $plugins_information;
    }

    private function filter_version_information_for_module( $module, $plugins_information ) {
        if ( isset( $plugins_information->response[ $module['basename'] ] ) ) {
            return $plugins_information;
        }

        if ( isset( $this->up_to_date_modules[ $module['instance']->slug ] ) ) {
            return $plugins_information;
        }

        try {
            $information = $this->get_information_for_module( $module );
        } catch ( AWPCP_Exception $e ) {
            awpcp_flash( $e->format_errors() );
            return $plugins_information;
        }

        if ( version_compare( $module['instance']->version , $information->new_version, '<' ) ) {
            $plugins_information->response[ $module['basename'] ] = $information;
        } else {
            $this->up_to_date_modules[ $module['instance']->slug ] = true;
        }

        return $plugins_information;
    }

    private function get_information_for_module( $module ) {;
        if ( isset( $this->modules_information[ $module['basename'] ] ) ) {
            $information = $this->modules_information[ $module['basename'] ];
        } else {
            $module_name = $module['instance']->name;
            $module_slug = $module['instance']->slug;
            $license = $module['license'];

            $information = $this->edd->get_version( $module_name, $module_slug, 'D. Rodenbaugh', $license );
            $this->modules_information[ $module['basename'] ] = $information;
        }

        return $information;
    }

    public function filter_detailed_plugin_information( $response, $action, $args ) {
        if ( $action != 'plugin_information' || ! isset( $this->modules[ $args->slug ] ) ) {
            return $response;
        }

        try {
            $information = $this->get_information_for_module( $this->modules[ $args->slug ] );
        } catch ( AWPCP_Exception $e ) {
            awpcp_flash( $e->format_errors() );
            return $response;
        }

        return $information;
    }

    public function filter_http_request_args( $args, $url ) {
        if ( strpos( $url, 'https://' ) !== false && strpos( $url, 'edd_action=package_download' ) ) {
            $args['sslverify'] = false;
        }

        return $args;
    }
}
