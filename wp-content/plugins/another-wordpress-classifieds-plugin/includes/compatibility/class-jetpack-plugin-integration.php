<?php

/**
 * Constructor function for Jetpack Plugin Integration class.
 *
 * @since 3.8.3
 */
function awpcp_jetpack_plugin_integration() {
    return new AWPCP_Jetpack_Plugin_Integration( awpcp_query() );
}

/**
 * Integration code for Jetpack plugin.
 *
 * @since 3.8.3
 */
class AWPCP_Jetpack_Plugin_Integration implements AWPCP_Plugin_Integration {

    /**
     * Constructor.
     *
     * @since 3.8.3
     */
    public function __construct( $query ) {
        $this->query = $query;
    }

    /**
     * @see AWPCP_Plugin_Integration::load()
     * @since 3.8.3
     */
    public function load() {
        if ( is_admin() ) {
            return;
        }

        add_action( 'template_redirect', array( $this, 'maybe_disable_jetpack_open_grap' ) );
    }

    /**
     * Disables Jetpack Open Graph meta tags on single listing pages.
     *
     * @since 3.8.3
     */
    public function maybe_disable_jetpack_open_grap() {
        if ( $this->query->is_single_listing_page() ) {
            add_filter( 'jetpack_enable_open_graph', '__return_false' );
        }
    }
}
