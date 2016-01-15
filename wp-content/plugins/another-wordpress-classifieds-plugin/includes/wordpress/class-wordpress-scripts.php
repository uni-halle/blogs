<?php

/**
 * @since 3.6
 */
function awpcp_wordpress_scripts() {
    if ( ! isset( $GLOBALS['wp_scripts'] ) ) {
        _doing_it_wrong( __FUNCTION__, __( 'The instance of WP_Scripts is not ready!' ), '3.5.4' );
        $GLOBALS['wp_scripts'] = new WP_Scripts();
    }

    return new AWPCP_WordPress_Scripts( $GLOBALS['wp_scripts'] );
}

/**
 * @package AWPCP
 * @subpackage WordPress_Facade
 * @uses WP_Scripts
 * @since 3.6
 */
class AWPCP_WordPress_Scripts {

    private $wp_scripts;

    /**
     * @since 3.6
     */
    public function __construct( $wp_scripts ) {
        $this->wp_scripts = $wp_scripts;
    }

    /**
     * @since 3.6
     */
    public function script_will_be_printed( $handle ) {
        if ( $this->query( $handle, 'queue' ) ) {
            return true;
        }

        if ( $this->query( $handle, 'done' ) ) {
            return true;
        }

        if ( $this->query( $handle, 'to_do' ) ) {
            return true;
        }

        return false;
    }

    /**
     * Based on WP_Dependency::query implementation in WordPress 4.3.
     *
     * @since 3.6
     */
    public function query( $handle, $list = 'registered' ) {
        if ( version_compare( get_bloginfo( 'version' ), '4.0', '>' ) ) {
            return $this->wp_scripts->query( $handle, $list );
        }

        if ( ! in_array( $list, array( 'enqueued', 'queue' ) ) ) {
            return $this->wp_scripts->query( $handle, $list );
        }

        if ( ! $this->wp_scripts->query( $handle, $list ) ) {
            return $this->recurse_deps( $this->wp_scripts->queue, $handle );
        }

        return true;
    }

    /**
     * Recursively search the passed dependency tree for $handle.
     * Borrowed from WP_Dependency in WordPress 4.3.
     *
     * @since 3.6
     *
     * @param array  $queue  An array of queued _WP_Dependency handle objects.
     * @param string $handle Name of the item. Should be unique.
     * @return bool Whether the handle is found after recursively searching the dependency tree.
     */
    function recurse_deps( $queue, $handle ) {
        $registered = $this->wp_scripts->registered;

        foreach ( $queue as $queued ) {
            if ( ! isset( $registered[ $queued ] ) ) {
                continue;
            }
            if ( in_array( $handle, $registered[ $queued ]->deps ) ) {
                return true;
            } elseif ( $this->recurse_deps( $registered[ $queued ]->deps, $handle ) ) {
                return true;
            }
        }
        return false;
    }
}
