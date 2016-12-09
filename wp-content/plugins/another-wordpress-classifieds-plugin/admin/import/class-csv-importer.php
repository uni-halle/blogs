<?php

class AWPCP_CSV_Importer {

    private $delegate;
    private $import_session;
    private $csv_reader;

    public function __construct( $delegate, $import_session, $csv_reader ) {
        $this->delegate = $delegate;
        $this->import_session = $import_session;
        $this->csv_reader = $csv_reader;
    }

    public function import_rows() {
        $number_of_rows = $this->import_session->get_number_of_rows();
        $number_of_rows_imported = $this->import_session->get_data( 'number_of_rows_imported', 0 );
        $number_of_rows_rejected = $this->import_session->get_data( 'number_of_rows_rejected', 0 );

        $last_row_processed = $this->import_session->get_data( 'last_row_processed', 0 );
        $batch_size = $this->import_session->get_batch_size();
        $errors = array();

        $number_of_rows_to_process = min( $batch_size, $number_of_rows - $last_row_processed );

        for ( $n = 0; $n < $number_of_rows_to_process; $n = $n + 1 ) {
            $last_row_processed = $last_row_processed + 1;

            try {
                $row_data = $this->csv_reader->get_row( $last_row_processed );
            } catch ( UnexpectedValueException $e ) {
                $errors[] = array( 'type' => 'error', 'line' => $last_row_processed, 'content' => $e->getMessage() );

                $number_of_rows_rejected = $number_of_rows_rejected + 1;

                continue;
            }

            if ( $row_data === false ) {
                break;
            }

            try {
                $this->delegate->import_row( $row_data );
            } catch ( AWPCP_CSV_Importer_Exception $e ) {
                foreach ( $e->getErrors() as $error ) {
                    $errors[] = array( 'type' => 'error', 'line' => $last_row_processed, 'content' => $error );
                }

                $number_of_rows_rejected = $number_of_rows_rejected + 1;

                continue;
            }

            $number_of_rows_imported = $number_of_rows_imported + 1;
        }

        $this->import_session->set_data( 'last_row_processed', $last_row_processed );
        $this->import_session->set_data( 'number_of_rows_imported', $number_of_rows_imported );
        $this->import_session->set_data( 'number_of_rows_rejected', $number_of_rows_rejected );
        $this->import_session->add_errors( $errors );

        $this->csv_reader->release();
    }

    public function count_rows() {
        $number_of_rows = $this->csv_reader->get_number_of_rows();
        $this->csv_reader->release();
        return $number_of_rows;
    }
}
