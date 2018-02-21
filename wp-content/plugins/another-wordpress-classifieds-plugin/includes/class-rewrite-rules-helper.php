<?php

/**
 * @since 3.6
 */
function awpcp_rewrite_rules_helper() {
    return new AWPCP_Rewrite_Rules_Helper();
}

/**
 * @since 3.6
 */
class AWPCP_Rewrite_Rules_Helper {

    /**
     * WP_Rewrite is not available when plugins are loaded. The dependency
     * will be hidden in this method until the minimum supported version of
     * WordPress guarantees that flush_rules is not executed before wp_loaded.
     */
    public function rewrite() {
        return $GLOBALS['wp_rewrite'];
    }

    /**
     * @see WP's add_rewrite_rule().
     */
    public function add_page_rewrite_rule( $pattern, $redirect, $position ) {
        $this->rewrite()->add_rule( $this->create_page_rewrite_rule_regex( $pattern ), $redirect, $position );
    }

    private function create_page_rewrite_rule_regex( $pattern ) {
        return str_replace( '%pagename%', $pattern, $this->rewrite()->get_page_permastruct() );
    }

    /**
     * See https://github.com/drodenbaugh/awpcp/issues/1482#issuecomment-173084162
     *
     * @since 3.8.3
     */
    public function generate_page_uri_variants( $regular_page_uri ) {
        $uppercase_page_uri = $regular_page_uri;

        preg_match_all( '/%[0-9a-zA-Z]{2}/', $regular_page_uri, $matches );

        foreach ( $matches[0] as $match ) {
            $uppercase_page_uri = str_replace( $match, strtoupper( $match ), $uppercase_page_uri );
        }

        if ( strcmp( $regular_page_uri, $uppercase_page_uri ) !== 0 ) {
            return array( $regular_page_uri, $uppercase_page_uri );
        } else {
            return array( $regular_page_uri );
        }
    }
}
