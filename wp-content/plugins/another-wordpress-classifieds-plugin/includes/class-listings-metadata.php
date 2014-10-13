<?php

function awpcp_listings_metadata() {
    return new AWPCP_ListingsMetadata();
}

class AWPCP_ListingsMetadata {

    public function get( $listing_id, $name ) {
        return awpcp_get_ad_meta( $listing_id, $name, true );
    }

    public function set( $listing_id, $name, $value ) {
        return awpcp_update_ad_meta( $listing_id, $name, $value );
    }
}

//
// Metadata API.
//

function awpcp_add_ad_meta( $ad_id, $meta_key, $meta_value, $unique = false ) {
    return add_metadata( 'awpcp_ad', $ad_id, $meta_key, $meta_value, $unique );
}

function awpcp_update_ad_meta( $ad_id, $meta_key, $meta_value, $prev_value = '' ) {
    return update_metadata( 'awpcp_ad', $ad_id, $meta_key, $meta_value, $prev_value );
}

function awpcp_delete_ad_meta( $ad_id, $meta_key, $meta_value = '', $delete_all = false) {
    return delete_metadata( 'awpcp_ad', $ad_id, $meta_key, $meta_value, $delete_all );
}

function awpcp_get_ad_meta( $ad_id, $meta_key='', $single = false ) {
    return get_metadata( 'awpcp_ad', $ad_id, $meta_key, $single );
}
