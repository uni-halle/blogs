<?php

/**
 * @since 3.4
 */
function awpcp_maybe_add_http_to_url( $url ) {
    if ( empty( $url ) || preg_match( '#^(https?|s?ftp)://#', $url ) ) {
        return $url;
    }

    $new_url = sprintf( 'http://%s', $url );

    if ( isValidURL( $new_url ) ) {
        return $new_url;
    } else {
        return $url;
    }
}

/**
 * Copied from http://gistpages.com/2013/06/30/generate_ordinal_numbers_1st_2nd_3rd_in_php
 *
 * @since 3.3.2
 */
function awpcp_ordinalize($num) {
    $suff = 'th';
    if ( ! in_array( ( $num % 100 ), array( 11,12,13 ) ) ) {
        switch ( $num % 10 ) {
            case 1:  $suff = 'st'; break;
            case 2:  $suff = 'nd'; break;
            case 3:  $suff = 'rd'; break;
        }
        return "{$num}{$suff}";
    }
    return "{$num}{$suff}";
}

function awpcp_render_template( $template, $params ) {
    if ( file_exists( $template ) ) {
        $template_file = $template;
    } else if ( file_exists( AWPCP_DIR . '/templates/' . $template ) ) {
        $template_file = AWPCP_DIR . '/templates/' . $template;
    } else {
        $template_file = null;
    }

    if ( ! is_null( $template_file ) ) {
        ob_start();
        extract( $params );
        include( $template_file );
        $output = ob_get_contents();
        ob_end_clean();
    } else {
        $output = sprintf( 'Template %s not found!', str_replace( AWPCP_DIR, '', $template ) );
    }

    return $output;
}

function awpcp_admin_page_title() {
    $sections = array_merge( func_get_args(), array( __( 'Classifieds Management System', 'AWPCP' ) ) );
    return implode( ' &ndash; ', $sections );
}
