<?php

/**
 * @since 3.3
 */
function awpcp_get_listing_renderer() {
    $renderer = apply_filters( 'awpcp-get-listing-renderer', null );

    if ( is_null( $renderer ) ) {
        $renderer = awpcp_listing_renderer();
    }

    return $renderer;
}

/**
 * @since 3.3
 */
function awpcp_listing_renderer() {
    return new AWPCP_ListingRenderer();
}

/**
 * @since 3.3
 */
class AWPCP_ListingRenderer {
    public function get_view_listing_link( $listing ) {
        $url = $this->get_view_listing_url( $listing );
        $title = $listing->get_title();

        return sprintf( '<a href="%s" title="%s">%s</a>', $url, esc_attr( $title ), $title );
    }

    public function get_view_listing_url( $listing ) {
        return url_showad( $listing->ad_id );
    }
}
