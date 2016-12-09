<?php

class AWPCP_CSV_Import_Session {

    private $settings = array();
    private $csv_importer = null;

    public function __construct( $settings, $csv_importer ) {
        $this->settings = $settings;
        $this->csv_importer = $csv_importer;
    }

    public function get_settings() {
        return $this->settings;
    }

    public function get_id() {
        return $this->settings['session_id'];
    }

    public function set_working_directory( $path ) {
        $this->settings['working_directory'] = $path;
    }

    public function get_working_directory() {
        return $this->settings['working_directory'];
    }

    public function set_images_directory( $path ) {
        $this->settings['images_directory'] = $path;
    }

    public function get_images_directory() {
        return $this->settings['images_directory'];
    }

    public function set_status( $status ) {
        $this->settings['status'] = $status;
    }

    public function is_ready() {
        return $this->settings['status'] == 'ready';
    }

    public function is_in_progress() {
        return $this->settings['status'] == 'in-progress';
    }

    public function set_mode( $mode ) {
        $this->settings['mode'] = $mode;
    }

    public function is_test_mode_enabled() {
        return $this->settings['mode'] === 'test';
    }

    public function get_data( $name, $default = null ) {
        if ( isset( $this->settings['extra'][ $name ] ) ) {
            return $this->settings['extra'][ $name ];
        }

        return $default;
    }

    public function set_data( $name, $value ) {
        $this->settings['extra'][ $name ] = $value;
    }

    public function get_batch_size() {
        return $this->settings['batch_size'];
    }

    public function get_params() {
        return $this->get_data( 'params', array() );
    }

    public function set_params( $params ) {
        return $this->set_data( 'params', $params );
    }

    public function get_param( $name, $default = null ) {
        $params = $this->get_params();

        if ( isset( $params[ $name ] ) ) {
            return $params[ $name ];
        }

        return $default;
    }

    public function get_number_of_rows() {
        return $this->get_data( 'number_of_rows', 0 );
    }

    public function get_number_of_rows_imported() {
        return $this->get_data( 'number_of_rows_imported', 0 );
    }

    public function get_number_of_rows_rejected() {
        return $this->get_data( 'number_of_rows_rejected', 0 );
    }

    public function get_errors() {
        return $this->settings['errors'];
    }

    public function add_errors( $errors = array() ) {
        foreach ( $errors as $error ) {
            array_push( $this->settings['errors'], $error );
        }

        $this->settings['last_errors'] = $errors;
    }

    public function get_last_errors() {
        return (array) $this->settings['last_errors'];
    }

    public function clear_errors() {
        $this->settings['errors'] = array();
        $this->settings['last_errors'] = array();
    }
}
