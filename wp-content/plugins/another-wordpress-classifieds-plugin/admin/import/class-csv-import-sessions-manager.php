<?php

function awpcp_csv_import_sessions_manager() {
    return new AWPCP_CSV_Import_Sessions_Manager(
        awpcp_csv_importer_factory(),
        awpcp_csv_importer_delegate_factory(),
        awpcp_csv_reader_factory(),
        awpcp()->settings,
        awpcp_wordpress()
    );
}

class AWPCP_CSV_Import_Sessions_Manager {

    private $csv_importer_factory;
    private $csv_importer_delegate_factory;
    private $csv_reader_factory;
    private $wordpress;

    public function __construct( $csv_importer_factory, $csv_importer_delegate_factory, $csv_reader_factory, $settings, $wordpress ) {
        $this->csv_importer_factory = $csv_importer_factory;
        $this->csv_importer_delegate_factory = $csv_importer_delegate_factory;
        $this->csv_reader_factory = $csv_reader_factory;
        $this->settings = $settings;
        $this->wordpress = $wordpress;
    }

    public function get_current_import_session() {
        $settings = get_option( 'awpcp-csv-import-session' );

        if ( $settings === false ) {
            return null;
        }

        return $this->create_import_session( $settings );
    }

    public function create_import_session( $settings = array() ) {
        $settings = wp_parse_args( $settings, array(
            'session_id' => wp_hash( uniqid() ),

            'type' => null,
            'working_directory' => null,
            'batch_size' => 20,
            'in_progress' => false,

            'csv_reader' => array(),
            'csv_importer' => array(),

            'params' => array(),
            'errors' => array(),
        ) );

        $settings['working_directory'] = $this->make_absolute_path( $settings['working_directory'] );

        // $importer_delegate = $this->csv_importer_delegate_factory->create_importer_delegate(
        //     $settings['type']
        // );

        // $csv_importer = $this->csv_importer_factory->create_importer(
        //     array_merge(
        //         $settings['csv_importer'],
        //         array( 'working_directory' => $settings['working_directory'] )
        //     ),
        //     $importer_delegate,
        //     $csv_reader
        // );

        return new AWPCP_CSV_Import_Session( $settings, null );
    }

    private function make_absolute_path( $relative_path ) {
        return $this->get_uploads_dir_path() . DIRECTORY_SEPARATOR . $relative_path;
    }

    private function get_uploads_dir_path() {
        return $this->settings->get_runtime_option( 'awpcp-uploads-dir' );
    }

    public function update_current_import_session( $import_session ) {
        $data = $import_session->get_settings();

        $data['working_directory'] = $this->make_relative_path( $data['working_directory'] );

        return update_option( 'awpcp-csv-import-session', $data, false );
    }

    private function make_relative_path( $absolute_path ) {
        return trim( str_replace( $this->get_uploads_dir_path(), '', $absolute_path ), '/' );
    }

    public function delete_current_import_session() {
        return delete_option( 'awpcp-csv-import-session' );
    }
}
