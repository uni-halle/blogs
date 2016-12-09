<?php

class AWPCP_ListingsTableSearchByPhoneCondition {

    public function match( $search_by ) {
        return $search_by == 'phone';
    }

    public function create( $search_term ) {
        return array( 'phone' => $search_term );
    }
}
