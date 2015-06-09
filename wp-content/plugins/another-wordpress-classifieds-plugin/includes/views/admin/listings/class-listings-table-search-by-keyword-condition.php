<?php

class AWPCP_ListingsTableSearchByKeywordCondition {

    public function match( $search_by ) {
        return $search_by == 'keyword';
    }

    public function create( $search_term ) {
        return array( 'keyword' => $search_term );
    }
}
