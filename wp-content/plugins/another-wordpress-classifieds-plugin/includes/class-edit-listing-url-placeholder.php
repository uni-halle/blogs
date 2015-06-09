<?php

function awpcp_edit_listing_url_placeholder() {
    return new AWPCP_EditListingURLPlaceholder();
}

class AWPCP_EditListingURLPlaceholder {

    public function do_placeholder( $listing, $placeholder, $context ) {
        return esc_url( awpcp_get_edit_listing_url( $listing ) );
    }
}
