<?php

class AWPCP_DatabaseColumnCreator {

    /**
     * TODO: pass $wpdb as a parameter to the constructor
     */
    public function create( $table, $column_name, $column_definition ) {
        global $wpdb;

        if ( ! $this->column_exists( $table, $column_name ) ) {
            $query = sprintf( 'ALTER TABLE %s ADD `%s` %s', $table, $column_name, $column_definition );
            $wpdb->query( $query );
        }
    }

    private function column_exists( $table, $column ) {
        global $wpdb;

        $suppress_errors = $wpdb->suppress_errors();
        $show_errors = $wpdb->show_errors( false );

        $result = $wpdb->query( "SELECT `$column` FROM $table" );

        $wpdb->show_errors( $show_errors );
        $wpdb->suppress_errors( $suppress_errors );

        return $result !== false;
    }
}
