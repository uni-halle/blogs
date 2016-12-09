<?php

function awpcp_csv_importer_delegate_factory() {
    return new AWPCP_CSV_Importer_Delegate_Factory( $GLOBALS['wpdb'] );
}

class AWPCP_CSV_Importer_Delegate_Factory {

    private $db;

    public function __construct( $db ) {
        $this->db = $db;
    }

    public function create_importer_delegate( $import_session ) {
        return new AWPCP_CSV_Importer_Delegate( $import_session, $this->db );
    }
}
