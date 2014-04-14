<?php

class AWPCP_ListingsTableSearchConditionsParser {
    private $conditions = array();

    public function __construct( /*array */$conditions ) {
        $this->conditions = $conditions;
    }

    public function parse( $search_type, $search_term ) {
        foreach ( $this->conditions as $condition ) {
            if ( $condition->match( $search_type ) ) {
                return $condition->create( $search_term );
            }
        }

        throw new AWPCP_Exception( sprintf( 'Unknown search type: ', $search_type ) );
    }
}

function awpcp_listings_table_search_by_condition_parser() {
    $conditions = array(
        new AWPCP_ListingsTableSearchByIdCondition(),
        new AWPCP_ListingsTableSearchByKeywordCondition(),
        new AWPCP_ListingsTableSearchByLocationCondition(),
        new AWPCP_ListingsTableSearchByTitleCondition(),
        new AWPCP_ListingsTableSearchByUserCondition(),
    );

    if ( awpcp_current_user_is_admin() ) {
        $conditions[] = new AWPCP_ListingsTableSearchByPayerEmailCondition();
    }

    return new AWPCP_ListingsTableSearchConditionsParser( $conditions );
}
