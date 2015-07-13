<?php

function awpcp_page_name_monitor() {
    return new AWPCP_PageNameMonitor( awpcp_request(), $GLOBALS['wpdb'] );
}

class AWPCP_PageNameMonitor {

    private $request;
    private $db;

    public function __construct( $request, $db ) {
        $this->request = $request;
        $this->db = $db;
    }

    public function flush_rewrite_rules_if_plugin_pages_name_changes( $post_id, $post_after, $post_before ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( $this->request->post( 'post_type' ) === 'page' && ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

        if ( $post_before->post_name == $post_after->post_name ) {
            return;
        }

        if ( ! is_awpcp_page( $post_id ) ) {
            return;
        }

        flush_rewrite_rules();
    }
}
