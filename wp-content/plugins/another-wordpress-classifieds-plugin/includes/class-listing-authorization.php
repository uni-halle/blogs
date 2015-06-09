<?php

function awpcp_listing_authorization() {
    return new AWPCP_ListingAuthorization( awpcp_roles_and_capabilities(), awpcp_request() );
}

class AWPCP_ListingAuthorization {

    private $roles;
    private $request;

    public function __construct( $roles, $request ) {
        $this->roles = $roles;
        $this->request = $request;
    }

    public function is_current_user_allowed_to_edit_listing( $listing ) {
        if ( $this->roles->current_user_is_moderator() ) {
            return true;
        }

        if ( is_user_logged_in() && $listing->user_id == $this->request->get_current_user()->ID ) {
            return true;
        }

        return false;
    }
}
