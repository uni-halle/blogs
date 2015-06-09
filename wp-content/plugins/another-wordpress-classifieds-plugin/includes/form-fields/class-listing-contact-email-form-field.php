<?php

function awpcp_listing_contact_email_form_field( $slug ) {
    return new AWPCP_ListingContactEmailFormField( $slug );
}

/**
 * TODO: what if that field shouldn't be shown?
 */
class AWPCP_ListingContactEmailFormField extends AWPCP_FormField {

    public function get_name() {
        return _x( "Contact Person's Email", 'ad details form', 'AWPCP' );
    }

    public function get_help_text() {
        return _x( '(Please enter a valid email. The codes needed to edit your Ad will be sent to your email address)', 'ad details form', 'AWPCP' );
    }

    protected function is_required() {
        return true;
    }

    public function is_readonly( $value ) {
        if ( ! is_user_logged_in() || awpcp_current_user_is_moderator() || empty( $value ) ) {
            return false;
        }

        return true;
    }

    public function render( $value, $errors, $listing, $context ) {
        if ( $this->is_required() ) {
            $validators = 'required email';
        } else {
            $validators = 'email';
        }

        $params = array(
            'required' => $this->is_required(),
            'value' => $value,
            'errors' => $errors,

            'label' => $this->get_label(),
            'help_text' => $this->get_help_text(),
            'validators' => $validators,

            'html' => array(
                'id' => str_replace( '_', '-', $this->get_slug() ),
                'name' => $this->get_slug(),
                'readonly' => $this->is_readonly( $value ),
            ),
        );

        return awpcp_render_template( 'frontend/form-fields/listing-contact-email-form-field.tpl.php', $params );
    }
}
