<?php

function awpcp_wordpress_status_header_filter() {
    return new AWPCP_WordPress_Status_Header_Filter(
        awpcp_listings_collection(),
        awpcp_query(),
        awpcp_request()
    );
}

class AWPCP_WordPress_Status_Header_Filter {

    private $listings;
    private $query;
    private $request;

    public function __construct( $listings, $query, $request ) {
        $this->listings = $listings;
        $this->query = $query;
        $this->request = $request;
    }

    public function filter_status_header( $status_header, $code, $description, $protocol ) {
        if ( $code != 200 ) {
            return $status_header;
        }

        if ( ! $this->query->is_single_listing_page() ) {
            return $status_header;
        }

        $listing_id = $this->request->get_current_listing_id();

        if ( empty( $listing_id ) ) {
            return $status_header;
        }

        try {
            $listing = $this->listings->get( $listing_id );
        } catch ( AWPCP_Exception $e ) {
            return $this->get_listing_not_found_status_header( $protocol );
        }

        if ( $listing->disabled && ! awpcp_current_user_is_moderator() ) {
            return $this->get_listing_not_found_status_header( $protocol );
        }

        return $status_header;
    }

    private function get_listing_not_found_status_header( $protocol ) {
        $description = _x( 'Listing not found', '404 HTTP status description', 'another-wordpress-classifieds-plugin' );
        return "$protocol 404 $description";
    }
}
