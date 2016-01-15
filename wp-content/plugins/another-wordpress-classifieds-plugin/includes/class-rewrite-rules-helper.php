<?php

/**
 * @since 3.6
 */
function awpcp_rewrite_rules_helper() {
    return new AWPCP_Rewrite_Rules_Helper(_Helper);
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
}
