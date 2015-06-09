<?php

class AWPCP_ListingsTableSearchByPayerEmailCondition {

    public function match( $search_by ) {
        return $search_by == 'payer-email';
    }

    public function create( $search_term ) {
        return array( 'payer_email' => $search_term );
    }
}
