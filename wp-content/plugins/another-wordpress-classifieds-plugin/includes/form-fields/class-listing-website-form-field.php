<?php

function awpcp_listing_website_form_field( $slug ) {
    return new AWPCP_ListingWebsiteFormField( $slug, awpcp()->settings );
}

/**
 * TODO: what if that field shouldn't be shown?
 */
class AWPCP_ListingWebsiteFormField extends AWPCP_FormField {

    protected $settings;

    public function __construct( $slug, $settings ) {
        parent::__construct( $slug );
        $this->settings = $settings;
    }

    public function get_name() {
        return _x( 'Website URL', 'ad details form', 'AWPCP' );
    }

    protected function is_required() {
        return $this->settings->get_option( 'displaywebsitefieldreqop' );
    }

    public function is_allowed_in_context( $context ) {
        if ( ! $this->settings->get_option( 'displaywebsitefield' ) ) {
            return false;
        }

        return parent::is_allowed_in_context( $context );
    }

    public function render( $value, $errors, $listing, $context ) {
        $params = array(
            'required' => $this->is_required(),
            'value' => $value,
            'errors' => $errors,

            'label' => $this->get_label(),

            'html' => array(
                'id' => str_replace( '_', '-', $this->get_slug() ),
                'name' => $this->get_slug(),
            ),
        );

        return awpcp_render_template( 'frontend/form-fields/listing-website-form-field.tpl.php', $params );
    }
}
