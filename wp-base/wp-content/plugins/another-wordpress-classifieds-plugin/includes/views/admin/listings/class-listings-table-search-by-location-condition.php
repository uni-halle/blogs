<?php

class AWPCP_ListingsTableSearchByLocationCondition {

    public function match( $search_by ) {
        return $search_by == 'location';
    }

    public function create( $search_term ) {
        $region = array(
            'country' => $search_term,
            'state' => $search_term,
            'county' => $search_term,
            'city' => $search_term
        );

        $conditions = awpcp_regions_search_conditions( array( $region ) );

        return implode( ' OR ', $conditions );
    }
}
