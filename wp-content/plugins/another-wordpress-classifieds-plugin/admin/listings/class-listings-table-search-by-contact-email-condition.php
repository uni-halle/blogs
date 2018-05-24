<?php

class AWPCP_ListingsTableSearchByContactEmailCondition {

    public function match( $search_by ) {
        return $search_by == 'ad-contact-email';
    }

    public function create( $search_term ) {
        return array( 'ad_contact_email' => $search_term );
    }
}
