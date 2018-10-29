<?php

function awpcp_http() {
    return new AWPCP_HTTP();
}

class AWPCP_HTTP {

    public function get( $url, $args = array() ) {
        $args = array_merge( array(
            'headers' => array(
                'user-agent' => awpcp_user_agent_header(),
            ),
        ) );

        $response = wp_remote_get( $url, $args );

        if ( is_wp_error( $response ) ) {
            return $this->handle_wp_error( $response, $url, $args );
        }

        $response_code = wp_remote_retrieve_response_code( $response );
        $response_message = wp_remote_retrieve_response_message( $response );

        if ( 403 == $response_code ) {
            $url_parts = wp_parse_url( $url );
            $host = $url_parts['host'];

            $message = '<strong>' . __( 'The server returned a 403 Forbidden error.', 'another-wordpress-classifieds-plugin' ) . '</strong>';
            $message.= '<br/><br/>';
            $message.= __( "It look's like your server is not authorized to make requests to <host>. Please <support-link>contact Another WordPress Classifieds Plugin support</support-link> and ask them to add your IP address <ip-address> to the whitelist.", 'another-wordpress-classifieds-plugin' );
            $message.= '<br/><br/>';
            $message.= __( 'Include this error message with your report.', 'another-wordpress-classifieds-plugin' );

            $message = str_replace( '<host>', $host, $message );
            $message = str_replace( '<support-link>', '<a href="https://awpcp.com/contact">', $message );
            $message = str_replace( '</support-link>', '</a>', $message );
            $message = str_replace( '<ip-address>', awpcp_get_server_ip_address(), $message );

            throw new AWPCP_HTTP_Exception( $message );
        }

        return $response;
    }

    /**
     * TODO: Do not assume cURL functions are always available.
     */
    private function handle_wp_error( $response, $url, $args ) {
        $url_parts = wp_parse_url( $url );
        $host = $url_parts['host'];

        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );

        $r = curl_exec( $ch );

        $error_number = curl_errno( $ch );
        $error_message = curl_error( $ch );

        curl_close( $ch );

        if ( in_array( $error_number, array( 7 ), true ) ) {
            $message = '<strong>' . __( "It was not possible to establish a connection with <host>. The connection failed with the following error:", 'another-wordpress-classifieds-plugin' ) . '</strong>';
            $message.= '<br/><br/>';
            $message.= '<code>curl: (' . $error_number . ') ' . $error_message . '</code>';
            $message.= '<br/><br/>';
            $message.= __( "It look's like your server is not authorized to make requests to <host>. Please <support-link>contact Another WordPress Classifieds Plugin support</support-link> and ask them to add your IP address <ip-address> to the whitelist.", 'another-wordpress-classifieds-plugin' );
            $message.= '<br/><br/>';
            $message.= __( 'Include this error message with your report.', 'another-wordpress-classifieds-plugin' );

            $message = str_replace( '<host>', $host, $message );
            $message = str_replace( '<support-link>', '<a href="https://awpcp.com/contact">', $message );
            $message = str_replace( '</support-link>', '</a>', $message );
            $message = str_replace( '<ip-address>', awpcp_get_server_ip_address(), $message );

            throw new AWPCP_HTTP_Exception( $message );
        } elseif ( in_array( $error_number, array( 35 ), true ) ) {
            $message = '<strong>' . __( "It was not possible to establish a connection with <host>. A problem occurred in the SSL/TSL handshake:", 'another-wordpress-classifieds-plugin' ) . '</strong>';

            $message.= '<br/><br/>';
            $message.= '<code>curl: (' . $error_number . ') ' . $error_message . '</code>';
            $message.= '<br/><br/>';
            $message.= __( 'To ensure the security of our systems and adhere to industry best practices, we require that your server uses a recent version of cURL and a version of OpenSSL that supports TLSv1.2 (minimum version with support is OpenSSL 1.0.1c).', 'another-wordpress-classifieds-plugin' );
            $message.= '<br/><br/>';
            $message.= __( 'Upgrading your system will not only allow you to communicate with our servers but also help you prepare your website to interact with services using the latest security standards.', 'another-wordpress-classifieds-plugin' );
            $message.= '<br/><br/>';
            $message.= __( 'Please contact your hosting provider and ask them to upgrade your system. Include this message if necesary.', 'another-wordpress-classifieds-plugin' );

            $message = str_replace( '<host>', $host, $message );

            throw new AWPCP_HTTP_Exception( $message );
        } else {
            throw new AWPCP_HTTP_Exception( $response->get_error_message() );
        }
    }
}
