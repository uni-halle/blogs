<?php

class AWPCP_ListingsTableSearchByUserCondition {

    public function match( $search_by ) {
        return $search_by == 'user';
    }

    public function create( $search_term ) {
        return array( 'user' => $search_term );
    }
}
