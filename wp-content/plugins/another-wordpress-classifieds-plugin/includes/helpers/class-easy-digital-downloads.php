<?php

function awpcp_easy_digital_downloads() {
    return new AWPCP_EasyDigitalDownloads( awpcp()->settings, awpcp_http() );
}

class AWPCP_EasyDigitalDownloads {

    private $settings;
    private $http;

    public function __construct( $settings, $http ) {
        $this->settings = $settings;
        $this->http = $http;
    }

    public function check_license( $module_name, $license ) {
        $params = array(
            'edd_action' => 'check_license',
            'item_name' => $module_name,
            'license' => $license
        );

        try {
            return $this->license_request( $params );
        } catch ( AWPCP_Exception $e ) {
            $message = __( 'There was an error trying to retrieve information about your <module-name> license.', 'AWPCP' );
            $message = str_replace( '<module-name>', '<strong>' . $module_name . '</strong>', $message );
            throw new AWPCP_Exception( $this->build_error_message( $e, $message ) );
        }
    }

    private function license_request( $params ) {
        $response = $this->request( $params );

        if ( ! isset( $response->license ) ) {
            throw new AWPCP_Exception( 'Missing License Status parameter' );
        }

        if ( $response->license === 'failed' ) {
            throw new AWPCP_Exception( 'License Status parameter was set to <strong>Failed</strong>' );
        }

        return $response;
    }

    private function request( $params ) {
        $params = urlencode_deep( $params );
        $url = add_query_arg( $params, $this->settings->get_runtime_option( 'easy-digital-downloads-store-url' ) );

        $response = $this->http->get( $url, array( 'timeout' => 15, 'sslverify' => false ) );
        $decoded_data = json_decode( wp_remote_retrieve_body( $response ) );

        if ( isset( $decoded_data->error ) ) {
            throw new AWPCP_Exception( $decoded_data->error );
        }

        return $decoded_data;
    }

    public function activate_license( $module_name, $license ) {
        try {
            return $this->perform_license_action( 'activate_license', $module_name, $license );
        } catch ( AWPCP_Exception $e ) {
            $message = __( 'There was an error trying to activate your <module-name> license.', 'AWPCP' );
            $message = str_replace( '<module-name>', '<strong>' . $module_name . '</strong>', $message );
            throw new AWPCP_Exception( $this->build_error_message( $e, $message ) );
        }
    }

    private function perform_license_action( $action_name, $module_name, $license ) {
        $params = array(
            'edd_action' => $action_name,
            'license' => $license,
            'item_name' => $module_name,
            'url' => home_url(),
        );

        return $this->license_request( $params );
    }

    private function build_error_message( $exception, $message ) {
        $template = __( '<specific-message> The error was: %s. Please contact customer support.', 'AWPCP' );
        $message = str_replace( '<specific-message>', $message, $template );
        $message = sprintf( $message, '<strong>' . $exception->format_errors() . '</strong>' );
        return $message;
    }

    public function deactivate_license( $module_name, $license ) {
        try {
            return $this->perform_license_action( 'deactivate_license', $module_name, $license );
        } catch ( AWPCP_Exception $e ) {
            $message = __( 'There was an error trying to deactivate your <module-name> license.', 'AWPCP' );
            $message = str_replace( '<module-name>', '<strong>' . $module_name . '</strong>', $message );
            throw new AWPCP_Exception( $this->build_error_message( $e, $message ) );
        }
    }

    public function get_version( $module_name, $module_slug, $author, $license ) {
        $params = array(
            'edd_action' => 'get_version',
            'license' => $license,
            'name' => $module_name,
            'slug' => $module_slug,
            'author' => $author,
            'url' => home_url(),
        );

        $response = $this->request( $params );

        if ( isset( $response->sections ) ) {
            $response->sections = maybe_unserialize( $response->sections );
        }

        return $response;
    }
}
