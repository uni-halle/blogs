<?php

function awpcp_listing_details_form_field( $slug ) {
    return new AWPCP_ListingDetailsFormField( $slug, awpcp_payments_api() );
}

/**
 * TODO: what if that field shouldn't be shown?
 */
class AWPCP_ListingDetailsFormField extends AWPCP_FormField {

    protected $payments;

    public function __construct( $slug, $payments ) {
        parent::__construct( $slug );
        $this->payments = $payments;
    }

    public function get_name() {
        return _x( 'Ad Details', 'ad details form', 'AWPCP' );
    }

    protected function is_required() {
        return true;
    }

    public function render( $value, $errors, $listing, $context ) {
        $characters_limit = $this->get_characters_limit_for_listing( $listing );

        if ( $characters_limit['characters_allowed'] == 0 ) {
            $characters_allowed_text = _x('No characters limit.', 'ad details form', 'AWPCP');
            $remaining_characters_text = '';
        } else {
            $characters_allowed_text = _x('characters left.', 'ad details form', 'AWPCP');
            $remaining_characters_text = $characters_limit['remaining_characters'];
        }

        if ( $this->is_required() ) {
            $validators = 'required';
        } else {
            $validators = '';
        }

        $params = array(
            'required' => $this->is_required(),
            'value' => $this->format_value( $value ),
            'errors' => $errors,

            'label' => $this->get_label(),
            'help_text' => nl2br( get_awpcp_option( 'htmlstatustext' ) ),
            'validators' => $validators,

            'characters_allowed' => $characters_limit['characters_allowed'],
            'characters_allowed_text' => $characters_allowed_text,
            'remaining_characters' => $characters_limit['remaining_characters'],
            'remaining_characters_text' => $remaining_characters_text,

            'html' => array(
                'id' => str_replace( '_', '-', $this->get_slug() ),
                'name' => $this->get_slug(),
                'readonly' => false,
            ),
        );

        return awpcp_render_template( 'frontend/form-fields/listing-details-form-field.tpl.php', $params );
    }

    private function get_characters_limit_for_listing( $listing ) {
        if ( is_a( $listing, 'AWPCP_Ad' ) ) {
            $payment_term = $listing->get_payment_term();
            $characters_used = strlen( $listing->ad_details );
        } else if ( $transaction = $this->payments->get_transaction() ) {
            $payment_term = $this->payments->get_transaction_payment_term( $transaction );
            $characters_used = 0;
        }

        if ( ! is_null( $payment_term ) ) {
            $characters_allowed = $payment_term->get_characters_allowed();
            $remaining_characters = max( 0, $characters_allowed - $characters_used );
        }

        return compact( 'characters_allowed', 'remaining_characters' );
    }
}
