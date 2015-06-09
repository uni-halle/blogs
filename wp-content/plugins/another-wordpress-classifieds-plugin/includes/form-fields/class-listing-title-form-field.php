<?php

function awpcp_listing_title_form_field( $slug ) {
    return new AWPCP_ListingTitleFormField( $slug, awpcp_payments_api() );
}

class AWPCP_ListingTitleFormField extends AWPCP_FormField {

    private $slug;
    private $payments;

    public function __construct( $slug, $payments ) {
        parent::__construct( $slug );
        $this->payments = $payments;
    }

    public function get_name() {
        return __( 'Listing Title', 'AWPCP' );
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

        $params = array(
            'required' => true,
            'value' => $value,
            'errors' => $errors,

            'characters_allowed' => $characters_limit['characters_allowed'],
            'characters_allowed_text' => $characters_allowed_text,
            'remaining_characters' => $characters_limit['remaining_characters'],
            'remaining_characters_text' => $remaining_characters_text,

            'label' => $this->get_label(),

            'html' => array(
                'id' => 'ad-title',
                'name' => $this->get_slug(),
            ),
        );

        return awpcp_render_template( 'frontend/form-fields/listing-title-form-field.tpl.php', $params );
    }

    private function get_characters_limit_for_listing( $listing ) {
        if ( is_a( $listing, 'AWPCP_Ad' ) ) {
            $payment_term = $listing->get_payment_term();
            $characters_used = strlen( $listing->ad_title );
        } else if ( $transaction = $this->payments->get_transaction() ) {
            $payment_term = $this->payments->get_transaction_payment_term( $transaction );
            $characters_used = 0;
        }

        if ( ! is_null( $payment_term ) ) {
            $characters_allowed = $payment_term->get_characters_allowed_in_title();
            $remaining_characters = max( 0, $characters_allowed - $characters_used );
        }

        return compact( 'characters_allowed', 'remaining_characters' );
    }
}
