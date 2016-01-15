<?php

function awpcp_easy_digital_downloads() {
    return new AWPCP_EasyDigitalDownloads(
        awpcp()->settings,
        awpcp_http(),
        awpcp_request(),
        awpcp_exceptions()
    );
}

class AWPCP_EasyDigitalDownloads {

    private $settings;
    private $http;
    private $request;
    private $exceptions;

    public function __construct( $settings, $http, $request, $exceptions ) {
        $this->settings = $settings;
        $this->http = $http;
        $this->request = $request;
        $this->exceptions = $exceptions;
    }

    public function check_license( $module_name, $license ) {
        $params = array(
            'edd_action' => 'check_license',
            'item_name' => $module_name,
            'license' => $license
        );

        try {
            return $this->license_request( $params );
        } catch ( AWPCP_Infinite_Loop_Detected_Exception $e ) {
            throw $e;
        } catch ( AWPCP_Easy_Digital_Downloads_Exception $e ) {
            $message = __( 'There was an error trying to check the license status for <module-name>.', 'another-wordpress-classifieds-plugin' );
            $exception = $this->exceptions->license_request_exception( $message, $module_name, $e->getMessage(), 0, $e );
            throw $exception;
        }
    }

    private function license_request( $params ) {
        $response = $this->request( $params );

        if ( ! isset( $response->license ) ) {
            $exception = $this->exceptions->easy_digital_downloads_exception( 'Missing License Status parameter' );
            throw $exception;
        }

        if ( $response->license === 'failed' ) {
            $exception = $this->exceptions->easy_digital_downloads_exception( 'License Status parameter was set to <strong>Failed</strong>' );
            throw $exception;
        }

        if ( $response->license === 'item_name_mismatch' ) {
            $exception = $this->exceptions->easy_digital_downloads_exception( 'item_name_mismatch' );
            throw $exception;
        }

        return $response;
    }

    private function request( $params ) {
        if ( $this->request->get( 'edd_action', false ) ) {
            throw new AWPCP_Infinite_Loop_Detected_Exception( 'The request was cancelled to avoid infinite recursion.' );
        }

        $params = urlencode_deep( $params );
        $url = add_query_arg( $params, $this->settings->get_runtime_option( 'easy-digital-downloads-store-url' ) );

        try {
            $response = $this->http->get( $url, array(
                'timeout' => 15,
                'sslverify' => false,
            ) );

            $decoded_data = json_decode( wp_remote_retrieve_body( $response ) );
        } catch ( AWPCP_WPError $e ) {
            throw new AWPCP_Easy_Digital_Downloads_Exception( $e->wp_error->get_error_message() );
        }

        if ( isset( $decoded_data->error ) ) {
            $exception = $this->exceptions->easy_digital_downloads_exception( $decoded_data->error );
            throw $exception;
        }

        return $decoded_data;
    }

    public function activate_license( $module_name, $license ) {
        try {
            return $this->perform_license_action( 'activate_license', $module_name, $license );
        } catch ( AWPCP_Easy_Digital_Downloads_Exception $e ) {
            if ( strcmp( $e->getMessage(), 'no_activations_left' ) === 0 ) {
                $exception = $this->exceptions->no_activations_left_license_request_exception( $module_name, $e );
                throw $exception;
            } else {
                $message = __( 'There was an error trying to activate your <module-name> license.', 'another-wordpress-classifieds-plugin' );
                $exception = $this->exceptions->license_request_exception( $message, $module_name, $e->getMessage(), 0, $e );
                throw $exception;
            }
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

    public function deactivate_license( $module_name, $license ) {
        try {
            return $this->perform_license_action( 'deactivate_license', $module_name, $license );
        } catch ( AWPCP_Easy_Digital_Downloads_Exception $e ) {
            $message = __( 'There was an error trying to deactivate your <module-name> license.', 'another-wordpress-classifieds-plugin' );
            $exception = $this->exceptions->license_request_exception( $message, $module_name, $e->getMessage(), 0, $e );
            throw $exception;
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
