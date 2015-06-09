<?php

abstract class AWPCP_FormField {

    private $slug;

    public function __construct( $slug ) {
        $this->slug = $slug;
    }

    public function get_slug() {
        return $this->slug;
    }

    public function get_label() {
        return $this->get_name();
    }

    public abstract function get_name();

    protected function is_required() {
        return false;
    }

    public function is_readonly( $value ) {
        return false;
    }

    public function is_allowed_in_context( $context ) {
        if ( $context['action'] == 'search' ) {
            return false;
        }

        return true;
    }

    protected function format_value( $value ) {
        return $value;
    }

    public abstract function render( $value, $errors, $listing, $context );
}
