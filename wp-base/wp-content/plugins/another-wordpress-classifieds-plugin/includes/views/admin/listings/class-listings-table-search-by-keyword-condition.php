<?php

class AWPCP_ListingsTableSearchByKeywordCondition {

    public function match( $search_by ) {
        return $search_by == 'keyword';
    }

    public function create( $search_term ) {
        global $wpdb;
        return $wpdb->prepare( 'MATCH (ad_title, ad_details) AGAINST (%s)', $search_term );
    }
}
