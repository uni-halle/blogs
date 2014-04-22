<?php

class AWPCP_ListingsTableSearchByKeywordCondition {

    public function match( $search_by ) {
        return $search_by == 'keyword';
    }

    public function create( $search_term ) {
        global $wpdb;
        return $wpdb->prepare( 'MATCH ( ' . AWPCP_TABLE_ADS . '.ad_title, ' . AWPCP_TABLE_ADS . '.ad_details) AGAINST (%s)', $search_term );
    }
}
