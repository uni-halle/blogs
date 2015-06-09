<?php

class AWPCP_ListingsTableSearchByTitleCondition {

    public function match( $search_by ) {
        return $search_by == 'title';
    }

    public function create( $search_term ) {
        return array( 'title' => $search_term );
    }
}
