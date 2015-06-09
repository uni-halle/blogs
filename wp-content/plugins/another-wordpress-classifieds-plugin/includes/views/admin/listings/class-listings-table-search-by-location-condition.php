<?php

class AWPCP_ListingsTableSearchByLocationCondition {

    public function match( $search_by ) {
        return $search_by == 'location';
    }

    public function create( $search_term ) {
        return array( 'region' => $search_term );
    }
}
