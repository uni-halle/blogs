<?php

/**
 * @since 3.4
 */
function awpcp_remove_filter( $filter, $class ) {
    global $wp_filter;

    if ( ! isset( $wp_filter[ $filter ] ) ) {
        return;
    }

    if ( ! class_exists( $class ) ) {
        return;
    }

    foreach ( $wp_filter[ $filter ] as $priority => $functions ) {
        foreach ( $functions as  $id => $item ) {
            if ( is_array( $item['function'] ) && $item['function'][0] instanceof $class ) {
                unset( $wp_filter[ $filter ][ $priority ][ $id ] );
                break 2;
            }
        }
    }
}
