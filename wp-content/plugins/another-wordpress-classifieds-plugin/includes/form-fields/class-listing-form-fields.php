<?php

function awpcp_listing_form_fields() {
    return new AWPCP_ListingFormFields();
}

class AWPCP_ListingFormFields {

    public function register_listing_form_fields( $fields ) {
        $fields['ad_title'] = 'awpcp_listing_title_form_field';
        $fields['websiteurl'] = 'awpcp_listing_website_form_field';
        $fields['ad_contact_name'] = 'awpcp_listing_contact_name_form_field';
        $fields['ad_contact_email'] = 'awpcp_listing_contact_email_form_field';
        $fields['ad_contact_phone'] = 'awpcp_listing_contact_phone_form_field';
        $fields['regions'] = 'awpcp_listing_regions_form_field';
        $fields['ad_item_price'] = 'awpcp_listing_price_form_field';
        $fields['ad_details'] = 'awpcp_listing_details_form_field';

        return $fields;
    }
}
