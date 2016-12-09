<?php

function awpcp_csv_reader_factory() {
    return new AWPCP_CSV_Reader_Factory();
}

class AWPCP_CSV_Reader_Factory {

    public function create_reader( $csv_file_path ) {
        return new AWPCP_CSV_Reader( $csv_file_path );
    }
}
