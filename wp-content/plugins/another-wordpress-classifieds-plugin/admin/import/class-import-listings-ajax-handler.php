<?php

function awpcp_import_listings_ajax_handler() {
    return new AWPCP_Import_Listings_Ajax_Handler(
        awpcp_csv_import_sessions_manager(),
        awpcp_csv_importer_factory(),
        awpcp_ajax_response()
    );
}

class AWPCP_Import_Listings_Ajax_Handler extends AWPCP_AjaxHandler {

    private $import_sessions_manager;

    public function __construct( $import_sessions_manager, $csv_importer_factory, $response ) {
        parent::__construct( $response );

        $this->import_sessions_manager = $import_sessions_manager;
        $this->csv_importer_factory = $csv_importer_factory;
    }

    public function ajax() {
        $import_session = $this->import_sessions_manager->get_current_import_session();

        $csv_importer = $this->csv_importer_factory->create_importer( $import_session );
        $csv_importer->import_rows();

        $this->import_sessions_manager->update_current_import_session( $import_session );

        return $this->success( array(
            'rowsCount' => $import_session->get_number_of_rows(),
            'rowsImported' => $import_session->get_number_of_rows_imported(),
            'rowsRejected' => $import_session->get_number_of_rows_rejected(),
            'errors' => $import_session->get_last_errors(),
        ) );
    }
}
