<?php

function awpcp_http() {
    return new AWPCP_HTTP( get_bloginfo( 'version' ), $GLOBALS['awpcp_db_version'] );
}

class AWPCP_HTTP {

    private $wordpress_version;
    private $plugin_version;

    public function __construct( $wordpress_version, $plugin_version ) {
        $this->wordpress_version = $wordpress_version;
        $this->plugin_version = $plugin_version;
    }

    public function get( $url, $args = array() ) {
        $user_agent = "WordPress %s / Another WordPress Classifieds Plugin %s";
        $user_agent = sprintf( $user_agent, $this->wordpress_version, $this->plugin_version );

        $args = array_merge( array(
            'headers' => array(
                'user-agent' => $user_agent,
            ),
        ) );

        return $this->response( wp_remote_get( $url, $args ) );
    }

    private function response( $response ) {
        if ( is_wp_error( $response ) ) {
            throw new AWPCP_WPError( $response );
        } else {
            return $response;
        }
    }
}
