<?php

function awpcp_update_form_fields_order_ajax_handler() {
    return new AWPCP_UpdateFormFieldsOrderAjaxHandler( awpcp_form_fields(), awpcp_request(), awpcp_ajax_response() );
}

class AWPCP_UpdateFormFieldsOrderAjaxHandler extends AWPCP_AjaxHandler {

    private $form_fields;
    private $request;

    public function __construct( $form_fields, $request, $response ) {
        parent::__construct( $response );

        $this->form_fields = $form_fields;
        $this->request = $request;
    }

    public function ajax() {
        $fields = $this->form_fields->get_fields();
        $fields_order = array();

        foreach ( $this->request->post( 'awpcp-form-fields-order' ) as $element_id ) {
            $field_slug = str_replace( 'field-', '', $element_id );

            if ( ! isset( $fields[ $field_slug ] ) ) {
                continue;
            }

            $fields_order[] = $field_slug;
        }

        if ( $this->form_fields->update_fields_order( $fields_order ) ) {
            return $this->success( array( 'selected' => $this->request->post( 'selected' ) ) );
        }
    }
}
