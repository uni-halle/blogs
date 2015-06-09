<?php

function awpcp_drip_autoresponder_ajax_handler() {
    return new AWPCP_DripAutoresponderAjaxHandler( awpcp()->settings, awpcp_request(), awpcp_ajax_response() );
}

class AWPCP_DripAutoresponderAjaxHandler extends AWPCP_AjaxHandler {

    const DRIP_FORM_URL = 'https://www.getdrip.com/forms/2206627/submissions';

    private $settings;
    private $request;

    public function __construct( $settings, $request, $response ) {
        parent::__construct( $response );

        $this->settings = $settings;
        $this->request = $request;
    }

    public function ajax() {
        if ( ! wp_verify_nonce( $this->request->post( 'nonce' ), 'drip-autoresponder' ) ) {
            return $this->error_response( __( 'You are not authorizred to perform this action.', 'AWPCP' ) );
        }

        $action = $this->request->post( 'action' );

        if ( $action == 'awpcp-autoresponder-user-subscribed' ) {
            return $this->user_subscribed();
        } else if ( $action == 'awpcp-autoresponder-dismissed' ) {
            return $this->autoresponder_dismissed();
        }
    }

    public function user_subscribed() {
        $posted_data = $this->get_posted_data();

        if ( ! awpcp_is_valid_email_address( $posted_data['email'] ) ) {
            return $this->error_response( _x( 'The email address entered is not valid.', 'drip-autoresponder', 'AWPCP' ) );
        }

        $response = wp_remote_post( self::DRIP_FORM_URL, array(
            'body' => array(
                'fields[name]' => $posted_data['name'],
                'fields[email]' => $posted_data['email'],
                'fields[website]' => get_bloginfo( 'url' ),
                'fields[gmt_offset]' => get_option( 'gmt_offset' ),
            ),
        ) );

        if ( $this->was_request_successful( $response ) ) {
            $this->disable_autoresponder();
            return $this->success( array( 'pointer' => $this->build_confirmation_pointer() ) );
        } else if ( isset( $response['body'] ) ) {
            return $this->error_response( $this->get_error_from_response_body( $response['body'] ) );
        } else {
            return $this->error_response( $this->get_unexpected_error_message() );
        }
    }

    function get_posted_data() {
        $current_user = $this->request->get_current_user();
        $name_alternatives = array( 'display_name', 'user_login', 'username' );

        return array(
            'name' => awpcp_get_object_property_from_alternatives( $current_user, $name_alternatives ),
            'email' => $this->request->post( 'email' ),
        );
    }

    function was_request_successful( $response ) {
        if ( is_wp_error( $response ) ) {
            return false;
        }

        if ( ! isset( $response['headers']['status'] ) || $response['headers']['status'] != '200 OK' ) {
            return false;
        }

        if ( ! isset( $response['headers']['x-xhr-redirected-to'] ) ) {
            return false;
        }

        return true;
    }

    private function disable_autoresponder() {
        $this->settings->update_option( 'show-drip-autoresponder', false, true );
    }

    private function build_confirmation_pointer() {
        return array(
            'content' => $this->render_pointer_content(),
            'buttons' => array(
                array(
                    'label' => 'Got it!',
                    'event' => 'awpcp-autoresponder-confirmation-dismissed',
                    'elementClass' => 'button',
                    'elementCSS' => array(
                        'marginLeft' => '10px'
                    ),
                ),
            ),
            'position' => array(
                'edge' => 'top',
                'align' => 'center',
            ),
        );
    }

    private function render_pointer_content() {
        $template = '<h3><title></h3><p><content></p>';

        $title = _x( 'Thank you for signing up!', 'drip-autoresponder', 'AWPCP' );
        $content = _x( 'Please check your email and click the link provided to confirm your subscription.', 'drip-autoresponder', 'AWPCP' );

        $template = str_replace( '<title>', $title, $template );
        $template = str_replace( '<content>', $content, $template );

        return $template;
    }

    private function get_error_from_response_body( $body ) {
        $errors = array();

        if ( preg_match_all( ';<span class="error">(.*?mail.*?)</span>;', $body, $matches, PREG_SET_ORDER ) ) {
            foreach ( $matches as $match ) {
                $errors[] = $match[1];
            }
        }

        $errors = array_unique( $errors );

        if ( count( $errors ) == 1 ) {
            return trim( reset( $errors ) );
        } else if ( ! empty( $errors ) ) {
            return sprintf( '<li>%s</li>', implode( '</li><li>', array_map( 'trim', $errors ) ) );
        } else {
            return $this->get_unexpected_error_message();
        }
    }

    private function get_unexpected_error_message() {
        return __( 'An unexpected error ocurred.', 'AWPCP' );
    }

    private function autoresponder_dismissed() {
        $this->disable_autoresponder();
        return $this->success();
    }
}
