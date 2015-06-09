<?php

class AWPCP_ListingsTableSearchByIdCondition {

    public function match( $search_by ) {
        return $search_by == 'id';
    }

    public function create( $search_term ) {
        return array( 'id' => absint( $search_term ) );
    }
}

