<?php

/**
 * Helper class used to handle API calls & configuration for Facebook integration.
 * @since 3.0.2
 */
class AWPCP_Facebook {

    const GRAPH_API_VERSION = 'v2.12';

	private static $instance = null;
    private $access_token = '';
    private $last_error = null;

    /**
     * @var Settings
     */
    private $settings;

    public function __construct( $settings ) {
        $this->settings = $settings;
    }

    public function validate_config( &$errors ) {
        $errors = !$errors ? array() : $errors;

        $app_id     = $this->settings->get_option( 'facebook-app-id' );
        $app_secret = $this->settings->get_option( 'facebook-app-secret' );
        $user_token = $this->settings->get_option( 'facebook-user-access-token' );
        $page_id    = $this->settings->get_option( 'facebook-page' );
        $page_token = $this->settings->get_option( 'facebook-page-access-token' );

        $app_access_token = '';

        if ( !$app_id || !$app_secret ) {
            $errors[] = __( 'Missing App ID and Secret.', 'another-wordpress-classifieds-plugin' );
            return;
        }

        if ( ! $user_id || ! $user_token ) {
            $errors[] = __( 'Missing a valid User Access Token.', 'another-wordpress-classifieds-plugin' );
            return;
        }

        $required_permissions = $this->get_required_permissions();

        try {
            $required_permissions_included = $this->user_token_has_required_permissions( $required_permissions );
        } catch ( AWPCP_Exception $e ) {
            $errors[] = $e->getMessage();

            return;
        }

        if ( ! $required_permissions_included ) {
            $message = __( 'User Access Token is valid but doesn\'t have the permissions required for AWPCP integration ({permissions}).', 'another-wordpress-classifieds-plugin' );
            $message = str_replace( '{permissions}', implode( ', ', $required_permissions ), $message );

            $errors[] = $message;

            return;
        }

        if ( !$page_token || !$page_id ) {
            $errors[] = __( 'No Facebook page is selected (missing page id or token).', 'another-wordpress-classifieds-plugin' );
        }
    }

    /**
     * @since 3.8.6
     */
    public function get_required_permissions() {
        if ( 'facebook-api' === $this->settings->get_option( 'facebook-integration-method' ) ) {
            // From Access Token Debugger: manage_pages, pages_show_list, publish_pages, publish_to_groups, public_profile
            // Required for API 3.1?
            return array(
                'manage_pages',
                'publish_pages',
                'publish_to_groups',
            );
        }

        return array();
    }

    /**
     * @since 3.8.6
     */
    private function user_token_has_required_permissions( $required_permissions ) {
        $this->set_access_token( 'user_token' );

        $response = $this->api_request( '/me/permissions', 'GET', array() );

        if ( ! $response && is_object( $response->last_error ) ) {
            throw new AWPCP_Exception( $this->last_error->message );
        }

        if ( ! $response || ! isset( $response->data ) ) {
            throw new AWPCP_Exception( __( 'Could not validate User Access Token.', 'another-wordpress-classifieds-plugin' ) );
        }

        $permissions = array();

        foreach ( $response->data as $entry ) {
            if ( $entry->status == 'granted' ) {
                $permissions[] = $entry->permission;
            }
        }

        foreach ( $required_permissions as $permission ) {
            if ( ! in_array( $permission, $permissions, true ) ) {
                return false;
            }
        }

        return true;
    }

    public function set_access_token( $key_or_token = '' ) {
        $token = $key_or_token;

        if ( $key_or_token == 'page_token' ) {
            $token = $this->settings->get_option( 'facebook-page-access-token' );
        }

        if ( $key_or_token == 'user_token' ) {
            $token = $this->settings->get_option( 'facebook-user-access-token' );
        }

        $this->access_token = $token;
    }

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self( awpcp()->settings );
        }
        return self::$instance;
    }

    public function get_user_pages() {
        $user_access_token = $this->settings->get_option( 'facebook-user-access-token' );

        if ( empty( $user_access_token ) ) {
            return array();
        }

        $this->set_access_token( $user_access_token );

        $pages = array();

        // Add own user page.
        $response = $this->api_request( '/me', 'GET', array( 'fields' => 'id,name' ) );

        if ( $response ) {
            $pages[] = array( 'id' => $response->id,
                              'name' => $response->name,
                'access_token' => $user_access_token,
                              'profile' => true );
        }

        $after = null;

        do {
            $response = $this->api_request( '/me/accounts', 'GET', array(
                'fields'  => 'id,name,access_token,perms',
                'summary' => 'total_count',
                'after'   => $after,
            ) );

            if ( $response && isset( $response->data ) ) {
                foreach ( $response->data as &$p ) {
                    if ( in_array( 'CREATE_CONTENT', $p->perms, true ) ) {
                        $pages[] = array(
                            'id'           => $p->id,
                            'name'         => $p->name,
                            'access_token' => $p->access_token
                        );
                    }
                }
            }

            if ( ! isset( $response->paging->cursors->after ) || ! isset( $response->paging->next ) ) {
                break;
            }

            $after = $response->paging->cursors->after;
        } while( $after );

    	return $pages;
    }

    public function get_user_groups() {
        $user_access_token = $this->settings->get_option( 'facebook-user-access-token' );

        if ( empty( $user_access_token ) ) {
            return array();
        }

        $this->set_access_token( $user_access_token );

        $groups = array();
        $after  = null;

        do {
            $response = $this->api_request( '/me/groups', 'GET', array(
                'after' => $after,
            ) );

            if ( $response && isset( $response->data ) ) {
                foreach ( $response->data as &$p ) {
                    $groups[] = array(
                        'id'   => $p->id,
                        'name' => $p->name
                    );
                }
            }

            if ( ! isset( $response->paging->cursors->after ) || ! isset( $response->paging->next ) ) {
                break;
            }

            $after = $response->paging->cursors->after;
        } while( $after );

        return $groups;
    }

    public function get_login_url( $redirect_uri = '', $scope = '' ) {
        $app_id = $this->settings->get_option( 'facebook-app-id' );

        return sprintf( 'https://www.facebook.com/' . self::GRAPH_API_VERSION . '/dialog/oauth?client_id=%s&redirect_uri=%s&scope=%s',
            $app_id,
                        urlencode( $redirect_uri ),
                        urlencode( $scope )
                      );
    }

    public function token_from_code( $code, $redirect_uri='' ) {
        if ( !$code )
            return false;

        if ( !$redirect_uri ) {
            // Assume $redirect_uri is the current URL sans stuff added by FB.
            $redirect_uri = remove_query_arg( array( 'client_id', 'code', 'error', 'error_reason', 'error_description', 'redirect_uri' ), awpcp_current_url() );
        }

        $response = $this->api_request( '/oauth/access_token',
                                        'GET',
                                        array( 'redirect_uri' => $redirect_uri,
                                               'code' => $code ),
            true
        );

        if ( $response && isset( $response->access_token ) ) {
            return $response->access_token;
        } else {
            return '';
        }
    }

    public function api_request( $path, $method = 'GET', $args = array(), $notoken=false, $json_decode=true ) {
        $this->last_error = '';

        $app_secret = $this->settings->get_option( 'facebook-app-secret' );

        $url = 'https://graph.facebook.com/' . self::GRAPH_API_VERSION . '/' . ltrim( $path, '/' );
        $url .= '?client_id=' . $this->settings->get_option( 'facebook-app-id' );
        $url .= '&client_secret=' . $app_secret;

        if ( !$notoken && $this->access_token ) {
            $url .= '&access_token=' . $this->access_token;
            $url .= '&appsecret_proof=' . hash_hmac( 'sha256', $this->access_token, $app_secret );
        }

        if ( $method == 'GET' && $args ) {
            foreach ( $args as $k => $v ) {
                if ( in_array( $k, array( 'client_id', 'client_secret', 'access_token' ) ) )
                    continue;

                $url .= '&' . $k . '=' . urlencode( $v );
            }
        }

        $c = curl_init();
        curl_setopt( $c, CURLOPT_URL, $url );
        curl_setopt( $c, CURLOPT_HEADER, 0 );
        curl_setopt( $c, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $c, CURLOPT_SSL_VERIFYPEER, 1 );
        curl_setopt( $c, CURLOPT_CAINFO, AWPCP_DIR . '/cacert.pem' );

        if ( $method == 'POST' ) {
            curl_setopt( $c, CURLOPT_POST, 1 );
            curl_setopt( $c, CURLOPT_POSTFIELDS, $args );
        }

        $res = curl_exec( $c );
        $curl_error_number = curl_errno( $c );
        $curl_error_message = curl_error( $c );
        curl_close( $c );

        if ( $curl_error_number === 0 ) {
            $res = $json_decode ? json_decode( $res ) : $res;

            if ( isset( $res->error ) )
                $this->last_error = $res->error;

            $response = !$res || isset( $res->error ) ? false : $res;
        } else {
            $this->last_error = new stdClass();
            $this->last_error->message = $curl_error_message;
            $response = false;
        }

        return $response;
    }

    /**
     * @since 3.0.2
     */
    public function get_last_error() {
        return $this->last_error;
    }

    public function is_page_set() {
        $page_id = $this->settings->get_option( 'facebook-page' );

        if ( empty( $page_id ) )  {
            return false;
        }

        $page_token = $this->settings->get_option( 'facebook-page-access-token' );

        if ( empty( $page_id ) ) {
            return false;
        }

        return true;
    }

    public function is_group_set() {
        return (bool) $this->settings->get_option( 'facebook-group' );
    }
}
