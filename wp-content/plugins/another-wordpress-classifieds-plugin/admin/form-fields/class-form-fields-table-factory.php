<?php

function awpcp_form_fields_table_factory() {
    return new AWPCP_FormFieldsTableFactory( awpcp_request() );
}

class AWPCP_FormFieldsTableFactory {

    private $request;

    public function __construct( $request ) {
        $this->request = $request;
    }

    public function create_table( $page ) {
        return new AWPCP_FormFieldsTable( $page, $this->request );
    }
}
