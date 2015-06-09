<?php

/**
 * @since 3.4
 */
function awpcp_build_categories_hierarchy( &$categories ) {
    $hierarchy = array();

    foreach ( $categories as $category ) {
        if ( $category->parent == 0 ) {
            $hierarchy['root'][] = $category;
        } else {
            $hierarchy[ $category->parent ][] = $category;
        }
    }

    return $hierarchy;
}

/**
 * @since 3.4
 */
function awpcp_organize_categories_by_id( &$categories ) {
    $organized = array();

    foreach ( $categories as $category ) {
        $organized[ $category->id ] = $category;
    }

    return $organized;
}

/**
 * @param $categories   Array of categories index by Category ID.
 * @since 3.4
 */
function awpcp_get_category_hierarchy( $category_id, &$categories ) {
    $category_parents = array();

    while ( $category_id > 0 && isset( $categories[ $category_id ] ) ) {
        $category_parents[] = $categories[ $category_id ];
        $category_id = $categories[ $category_id ]->parent;
    }

    return $category_parents;
}

/**
 * @since 3.4
 */
function awpcp_render_categories_dropdown_options( &$categories, &$hierarchy, $selected_category ) {
    $output = '';

    foreach ( $categories as $category ) {
        $category_name = stripslashes( stripslashes( $category->name ) );

        if( $category->id == $selected_category ) {
            $item = '<option class="dropdownparentcategory" selected="selected" value="' . $category->id . '">' . $category_name . '</option>';
            $item = '<option selected="selected" value="' . $category->id . '">- ' . $category_name . '</option>';
        } else {
            $item = '<option class="dropdownparentcategory" value="' . $category->id . '">' . $category_name . '</option>';
            $item = '<option value="' . $category->id . '">-' . $category_name . '</option>';
        }

        $output .= awpcp_render_categories_dropdown_option( $category, $selected_category );

        if ( isset( $hierarchy[ $category->id ] ) ) {
            $output .= awpcp_render_categories_dropdown_options( $hierarchy[ $category->id ], $hierarchy, $selected_category );
        }
    }

    return $output;
}

/**
 * @since 3.4
 */
function awpcp_render_categories_dropdown_option( $category, $selected_category ) {
    if ( $selected_category == $category->id ) {
        $selected_attribute = 'selected="selected"';
    } else {
        $selected_attribute = '';
    }

    if ( $category->parent == 0 ) {
        $class_attribute = 'class="dropdownparentcategory"';
        $category_name = esc_html( $category->name );
    } else {
        $class_attribute = '';
        $category_name = sprintf('- %s', esc_html( $category->name ) );
    }

    return sprintf(
        '<option %s %s value="%d">%s</option>',
        $class_attribute,
        $selected_attribute,
        esc_attr( $category->id ),
        $category_name
    );
}

/**
 * @since 3.4
 */
function awpcp_get_count_of_listings_in_categories() {
    static $listings_count;

    if ( is_null( $listings_count ) ) {
        $listings_count = awpcp_count_listings_in_categories();
    }

    return $listings_count;
}

/**
 * @since 3.4
 */
function awpcp_count_listings_in_categories() {
    global $wpdb;

    // never allow Unpaid, Unverified or Disabled Ads
    $conditions[] = "payment_status != 'Unpaid'";
    $conditions[] = 'verified = 1';
    $conditions[] = 'disabled = 0';

    if( ( get_awpcp_option( 'enable-ads-pending-payment' ) == 0 ) && ( get_awpcp_option( 'freepay' ) == 1 ) ) {
        $conditions[] = "payment_status != 'Pending'";
    }

    // TODO: ideally there would be a function to get all visible Ads,
    // and modules, like Regions, would use hooks to include their own
    // conditions.
    if ( function_exists( 'awpcp_regions' ) && function_exists( 'awpcp_regions_api' ) ) {
        if ( $active_region = awpcp_regions()->get_active_region() ) {
            $conditions[] = awpcp_regions_api()->sql_where( $active_region->region_id );
        }
    }

    // TODO: at some point we should start using the Category model.
    $query = 'SELECT ad_category_parent_id AS parent_category_id, ad_category_id AS category_id, count(*) AS count ';
    $query.= 'FROM ' . AWPCP_TABLE_ADS;
    $query = sprintf( '%s WHERE %s', $query, implode( ' AND ', $conditions ) );
    $query.= ' GROUP BY ad_category_id, ad_category_parent_id';
    $query.= ' ORDER BY ad_category_parent_id, ad_category_id';

    $listings_count = array();

    foreach ( $wpdb->get_results( $query ) as $row ) {
        if ( $row->parent_category_id > 0 ) {
            if ( isset( $listings_count[ $row->parent_category_id ] ) ) {
                $listings_count[ $row->parent_category_id ] = $listings_count[ $row->parent_category_id ] + $row->count;
            } else {
                $listings_count[ $row->parent_category_id ] = $row->count;
            }
        }

        if ( isset( $listings_count[ $row->category_id ] ) ) {
            $listings_count[ $row->category_id ] = $listings_count[ $row->category_id ] + $row->count;
        } else {
            $listings_count[ $row->category_id ] = $row->count;
        }
    }

    return $listings_count;
}

function total_ads_in_cat( $category_id ) {
    $listings_count = awpcp_get_count_of_listings_in_categories();
    return isset( $listings_count[ $category_id ] ) ? $listings_count[ $category_id ] : 0;
}
