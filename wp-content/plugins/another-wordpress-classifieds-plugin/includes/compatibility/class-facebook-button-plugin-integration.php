<?php

function awpcp_facebook_button_plugin_integration() {
    return new AWPCP_Facebook_Button_Plugin_Integration( awpcp_query() );
}

class AWPCP_Facebook_Button_Plugin_Integration {

    private $query;

    public function __construct( $query ) {
        $this->query = $query;
    }

    public function setup() {
        add_action( 'wp', array( $this, 'remove_unnecessary_actions' ) );
    }

    public function remove_unnecessary_actions() {
        if ( function_exists( 'fcbkbttn_meta' ) && $this->query->is_single_listing_page() ) {
            remove_filter( 'wp_head', 'fcbkbttn_meta' );
        }
    }
}
