<?php

class AWPCP_ListingsTableSearchByPayerEmailCondition {

    public function match( $search_by ) {
        return $search_by == 'payer-email';
    }

    public function create( $search_term ) {
        global $wpdb;
        return $wpdb->prepare( 'payer_email = %s', $search_term );
    }
}
