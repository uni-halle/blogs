<?php

function awpcp_listing_contact_phone_form_field( $slug ) {
    return new AWPCP_ListingContactPhoneFormField( $slug, awpcp()->settings );
}

/**
 * TODO: what if that field shouldn't be shown?
 */
class AWPCP_ListingContactPhoneFormField extends AWPCP_FormField {

    protected $settings;

    public function __construct( $slug, $settings ) {
        parent::__construct( $slug );
        $this->settings = $settings;
    }

    public function get_name() {
        return _x( "Contact Person's Phone Number", 'ad details form', 'AWPCP' );
    }

    protected function is_required() {
        return $this->settings->get_option( 'displayphonefieldreqop' );
    }

    public function is_allowed_in_context( $context ) {
        if ( ! $this->settings->get_option( 'displayphonefield' ) ) {
            return false;
        }

        return parent::is_allowed_in_context( $context );
    }

    public function render( $value, $errors, $listing, $context ) {
        if ( $this->is_required() ) {
            $validators = 'required';
        } else {
            $validators = '';
        }

        $params = array(
            'required' => $this->is_required(),
            'value' => $value,
            'errors' => $errors,

            'label' => $this->get_label(),
            'help_text' => '',
            'validators' => $validators,

            'html' => array(
                'id' => str_replace( '_', '-', $this->get_slug() ),
                'name' => $this->get_slug(),
                'readonly' => false,
            ),
        );

        return awpcp_render_template( 'frontend/form-fields/listing-contact-phone-form-field.tpl.php', $params );
    }
}
