<?php

function awpcp_database_column_creator() {
    return new AWPCP_DatabaseColumnCreator( $GLOBALS['wpdb'] );
}

class AWPCP_DatabaseColumnCreator {

    private $db;

    public function __construct( $db ) {
        $this->db = $db;
    }

    public function create( $table, $column_name, $column_definition ) {
        if ( ! $this->column_exists( $table, $column_name ) ) {
            $query = sprintf( 'ALTER TABLE %s ADD `%s` %s', $table, $column_name, $column_definition );
            $this->db->query( $query );
        }
    }

    private function column_exists( $table, $column ) {
        $suppress_errors = $this->db->suppress_errors();
        $show_errors = $this->db->show_errors( false );

        $result = $this->db->query( "SELECT `$column` FROM $table" );

        $this->db->show_errors( $show_errors );
        $this->db->suppress_errors( $suppress_errors );

        return $result !== false;
    }
}
