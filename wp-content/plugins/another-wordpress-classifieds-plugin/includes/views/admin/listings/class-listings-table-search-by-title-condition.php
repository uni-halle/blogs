<?php

class AWPCP_ListingsTableSearchByTitleCondition {

    public function match( $search_by ) {
        return $search_by == 'title';
    }

    public function create( $search_term ) {
        global $wpdb;
        return sprintf( AWPCP_TABLE_ADS . ".ad_title LIKE '%%%s%%'", esc_sql( $search_term ) );
    }
}
