<?php

class AWPCP_ListingActionAdminPage {

    protected $listings;
    protected $request;

    public function __construct( $listings, $request ) {
        $this->listings = $listings;
        $this->request = $request;
    }

    protected function get_selected_listings() {
        $listing_id = $this->request->param( 'id' );

        $listings_ids = $this->request->param( 'selected', array( $listing_id ) );
        $listings_ids = array_filter( array_map( 'intval', $listings_ids ) );

        return $this->listings->find_all_by_id( $listings_ids );
    }

    protected function show_bulk_operation_result_message( $successful_count, $failed_count, $success_message, $error_message ) {
        if ( $successful_count > 0 && $failed_count > 0) {
            $message = _x( '%s and %s.', 'Listing bulk operations: <message-ok> and <message-error>.', 'AWPCP' );
            awpcp_flash( sprintf( $message, $success_message, $error_message ), 'error' );
        } else if ( $successful_count > 0 ) {
            awpcp_flash( $success_message . '.' );
        } else if ( $failed_count > 0 ) {
            awpcp_flash( ucfirst( $error_message . '.' ), 'error' );
        }
    }
}
