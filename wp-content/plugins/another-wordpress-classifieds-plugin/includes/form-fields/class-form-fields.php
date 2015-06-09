<?php

function awpcp_form_fields() {
    static $instance = null;

    if ( is_null( $instance ) ) {
        $instance = new AWPCP_FormFields();
    }

    return $instance;
}

class AWPCP_FormFields {

    private $fields = null;

    public function get_fields() {
        if ( is_null( $this->fields ) ) {
            $this->fields = $this->build_fields();
        }

        return $this->fields;
    }

    private function build_fields() {
        $fields = array();

        foreach ( apply_filters( 'awpcp-form-fields', array() ) as $field_slug => $field_constructor ) {
            if ( is_callable( $field_constructor ) ) {
                $fields[ $field_slug ] = call_user_func( $field_constructor, $field_slug );
            }
        }

        $sorted_fields = array();

        foreach ( $this->get_fields_order() as $field_slug ) {
            if ( isset( $fields[ $field_slug ] ) ) {
                $sorted_fields[ $field_slug ] = $fields[ $field_slug ];
                unset( $fields[ $field_slug ] );
            }
        }

        return array_merge( $sorted_fields, $fields );
    }

    public function get_fields_order() {
        return get_option( 'awpcp-form-fields-order', array() );
    }

    public function update_fields_order( $order ) {
        return update_option( 'awpcp-form-fields-order', $order );
    }

    public function render_fields( $form_values, $form_errors, $listing, $context ) {
        $output = array();

        foreach( $this->get_fields() as $field_slug => $field ) {
            if ( ! $field->is_allowed_in_context( $context ) ) {
                continue;
            }

            $output[ $field_slug ] = $field->render(
                isset( $form_values[ $field_slug ] ) ? $form_values[ $field_slug ] : '',
                $form_errors,
                $listing,
                $context
            );
        }

        return implode( "\n", $output );
    }
}
