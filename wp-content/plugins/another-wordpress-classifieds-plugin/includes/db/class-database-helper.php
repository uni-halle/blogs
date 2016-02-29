<?php

function awpcp_database_helper() {
    return new AWPCP_Database_Helper( $GLOBALS['wpdb'] );
}

class AWPCP_Database_Helper {

    private $db;

    public function __construct( $db ) {
        $this->db = $db;
    }

    public function replace_charset_and_collate( $table_defintion ) {
        $table_defintion = str_replace( '<charset>', $this->get_charset(), $table_defintion );
        $table_defintion = str_replace( '<collate>', $this->get_collate(), $table_defintion );
        return $table_defintion;
    }

    public function get_charset() {
        if ( $this->db->charset === 'utf8mb4' && $this->db->has_cap( 'utf8mb4' ) ) {
            return 'utf8mb4';
        }

        return 'utf8';
    }

    public function get_collate() {
        if ( $this->db->charset === 'utf8mb4' && $this->db->has_cap( 'utf8mb4' ) ) {
            return $this->db->collate;
        }

        return 'utf8_general_ci';
    }
}
