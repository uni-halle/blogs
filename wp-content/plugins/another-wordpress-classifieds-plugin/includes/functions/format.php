<?php

/**
 * Copied from http://gistpages.com/2013/06/30/generate_ordinal_numbers_1st_2nd_3rd_in_php
 *
 * @since next-release
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
