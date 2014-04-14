<?php

if ( defined( 'CRYPTX_BASENAME' ) ) {
    add_action( 'init', 'awpcp_cryptx_compatibility' );
    add_action( 'awpcp-shortcode', 'awpcp_cryptx_shortcode_compatibility' );

    function awpcp_cryptx_exclude_posts($posts=array()) {
        global $cryptX_var;
        $excluded = explode( ',', $cryptX_var['excludedIDs'] );
        $excluded = array_unique( array_merge( $excluded, $posts ) );
        $cryptX_var['excludedIDs'] = join( ',', array_filter( $excluded ) );
    }

    function awpcp_cryptx_compatibility() {
        awpcp_cryptx_exclude_posts( awpcp_get_page_ids_by_ref( array(
            'place-ad-page-name',
            'edit-ad-page-name',
            'reply-to-ad-page-name',
        ) ) );
    }

    function awpcp_cryptx_shortcode_compatibility($shortcode) {
        global $wp_the_query;

        if ( in_array( $shortcode, array( 'place-ad', 'edit-ad', 'reply-to-ad' ) ) ) {
            awpcp_cryptx_exclude_posts( (array) $wp_the_query->queried_object->ID );
        }
    }
}
