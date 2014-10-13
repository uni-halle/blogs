<?php

function awpcp_users_autocomplete_ajax_handler() {
    return new AWPCP_UsersAutocompleteAjaxHandler( awpcp_users_collection(), awpcp_request(), awpcp_ajax_response() );
}

class AWPCP_UsersAutocompleteAjaxHandler extends AWPCP_AjaxHandler {

    private $users;
    private $request;

    public function __construct( $users, $request, $response ) {
        parent::__construct( $response );

        $this->users = $users;
        $this->request = $request;
    }

    public function ajax() {
        $users = $this->users->find_by_search_string( $this->request->param( 'term' ) );
        return $this->success( array( 'items' => array_values( $users ) ) );
    }
}
