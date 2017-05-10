<?php

function awpcp_mashshare_plugin_integration() {
    return new AWPCP_MashShare_Plugin_Integration( awpcp_query() );
}

class AWPCP_MashShare_Plugin_Integration implements AWPCP_Plugin_Integration {

    private $query;

    public function __construct( $query ) {
        $this->query = $query;
    }

    public function load() {
        if ( is_admin() ) {
            return;
        }

        add_action( 'template_redirect', array( $this, 'maybe_remove_mashshare_opengraph_tags' ) );
    }

    public function maybe_remove_mashshare_opengraph_tags() {
        if ( $this->query->is_single_listing_page() ) {
            remove_action( 'wp_head', 'mashsb_meta_tags_init', 1 );
        }
    }
}
