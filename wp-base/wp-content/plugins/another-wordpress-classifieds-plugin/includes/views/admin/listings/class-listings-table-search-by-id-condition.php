<?php

class AWPCP_ListingsTableSearchByIdCondition {

    public function match( $search_by ) {
        return $search_by == 'id';
    }

    public function create( $search_term ) {
        global $wpdb;
        return $wpdb->prepare( 'ad_id = %d', (int) $search_term );
    }
}

