<?php

/**
 * @since 3.4
 */
function awpcp_display_listings( $query, $context, $options ) {
    $options = wp_parse_args( $options, array(
        'show_intro_message' => false,
        'show_menu_items' => false,
        'show_category_selector' => false,
        'show_pagination' => false,
        'before_content' => '',
        'before_pagination' => '',
        'before_list' => '',
        'after_pagination' => '',
        'after_content' => '',
    ) );

    if ( has_action( 'awpcp_browse_ads_template_action' ) || has_filter( 'awpcp_browse_ads_template_filter' ) ) {
        do_action( 'awpcp_browse_ads_template_action' );
        return apply_filters( 'awpcp_browse_ads_template_filter' );
    }

    $results_per_page = absint( awpcp_request_param( 'results', get_awpcp_option( 'adresultsperpage', 10 ) ) );
    $results_offset = absint( awpcp_request_param( 'offset', 0 ) );

    if ( empty( $query['limit'] ) && $results_per_page ) {
        $query['limit'] = $results_per_page;
    }

    if ( empty( $query['offset'] ) && $query['limit'] ) {
        $query['offset'] = $results_offset;
    }

    $listings_collection = awpcp_listings_collection();

    $listings = $listings_collection->find_enabled_listings_with_query( $query );
    $listings_count = $listings_collection->count_enabled_listings_with_query( $query );

    $before_content = apply_filters( 'awpcp-content-before-listings-page', $options['before_content'], $context );

    $before_pagination = array();
    if ( $options['show_category_selector'] ) {
        $before_pagination[15]['category-selector'] = awpcp_render_category_selector( array( 'required' => false ) );
    }
    if ( is_array( $options['before_pagination'] ) ) {
        $before_pagination = awpcp_array_merge_recursive( $before_pagination, $options['before_pagination'] );
    } else {
        $before_pagination[20]['user-content'] = $options['before_pagination'];
    }
    $before_pagination = apply_filters( 'awpcp-listings-before-content', $before_pagination, $context );
    ksort( $before_pagination );
    $before_pagination = awpcp_flatten_array( $before_pagination );

    $before_list = apply_filters( 'awpcp-display-ads-before-list', $options['before_list'], $context );

    if ( $listings_count > 0 ) {
        $pagination_options = array(
            'results' => $results_per_page,
            'offset' => $results_offset,
            'total' => $listings_count,
        );
        $pagination = $options['show_pagination'] ? awpcp_pagination( $pagination_options, awpcp_current_url() ) : '';

        $items = awpcp_render_listings_items( $listings, $context );
    } else {
        $pagination = '';
        $items = array();
    }

    $after_pagination = array( 'user-content' => $options['after_pagination'] );
    $after_pagination = apply_filters( 'awpcp-listings-after-content', $after_pagination, $context );

    $after_content = apply_filters( 'awpcp-content-after-listings-page', $options['after_content'], $context );

    ob_start();
    include( AWPCP_DIR . '/templates/frontend/listings.tpl.php' );
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

/**
 * @since 3.4
 */
function awpcp_display_listings_in_page( $query, $context, $options = array() ) {
    $options = wp_parse_args( $options, array(
        'show_intro_message' => true,
        'show_menu_items' => true,
        'show_category_selector' => true,
        'show_pagination' => true,
    ) );

    return awpcp_display_listings( $query, $context, $options );
}
