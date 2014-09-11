<?php

/**
 * Helper class used to handle API calls & configuration for Facebook integration.
 * @since 3.0.2
 */
class AWPCP_Facebook {

    const GRAPH_URL = 'https://graph.facebook.com/';

	private static $instance = null;
    private $access_token = '';
    private $last_error = null;

	public function __construct() { }

    public function validate_config( &$errors ) {
        $errors = !$errors ? array() : $errors;

        $config = $this->get_config();
        $app_id = isset( $config['app_id'] ) ? $config['app_id'] : '';
        $app_secret = isset( $config['app_secret'] ) ? $config['app_secret'] : '';
        $user_id = isset( $config['user_id'] ) ? $config['user_id'] : '';
        $user_token = isset( $config['user_token'] ) ? $config['user_token'] : '';
        $page_id = isset( $config['page_id'] ) ? $config['page_id'] : '';
        $page_token = isset( $config['page_token'] ) ? $config['page_token'] : '';

        $app_access_token = '';

        if ( !$app_id || !$app_secret ) {
            $errors[] = __( 'Missing app ID/secret.', 'AWPCP' );
        } else {
            // Check App ID + secret.
            $res = $this->api_request( '/oauth/access_token',
                                       'GET',
                                       array( 'client_id' => $app_id,
                                              'client_secret' => $app_secret,
                                              'grant_type' => 'client_credentials' ),
                                       true,
                                       false );
            parse_str( $res, $parts );

            if ( !array_key_exists( 'access_token', $parts ) ) {
                $res = json_decode( $res );
                $errors[] = $res->error->message;
            } else {
                $app_access_token = $parts['access_token'];
            }
        }

        if ( !$user_id || !$user_token ) {
            $errors[] = __( 'Missing a valid User Access Token.', 'AWPCP' );
        } else {
            $this->set_access_token( $app_access_token );
            $res = $this->api_request( '/debug_token',
                                       'GET',
                                       array( 'input_token' => $user_token ) );

            if ( !$res || !isset( $res->data ) ) {
                $errors[] = __( 'Could not validate User Access Token. Are you connected to the internet?', 'AWPCP' );
            } else {
                $token_info = $res->data;

                if ( !$token_info->is_valid ) {
                    $errors[] = __( 'User Access Token is not valid for current app. Maybe you de-authorized the app or the token expired? Try clicking "Obtain an access token from Facebook" again.', 'AWPCP' );
                } else {
                    if ( !in_array( 'manage_pages', $token_info->scopes ) || !in_array( 'publish_stream', $token_info->scopes ) )
                        $errors[] = __( 'User Access Token is valid but doesn\'t have the permissions required for AWPCP integration (publish_stream and manage_pages).', 'AWPCP' );
                }

                if ( $token_info->user_id != $user_id )
                    $errors[] = __( 'User Access Token user id does not match stored user id.', 'AWPCP' );                
            }
        }

        if ( !$page_token || !$page_id ) {
            $errors[] = __( 'No Facebook page is selected (missing page id or token).', 'AWPCP' );
        }
    }

    public function get_config( $key=null ) {
        $defaults = array(
            'app_id' => '',
            'app_secret' => '',
            'user_token' => '',
            'page_token' => ''
        );

        $config = get_option( 'awpcp-facebook-config', $defaults );

        if ( $key ) {
            if ( isset( $config[ $key ] ) )
                return $config[ $key ];
            else
                return null;
        } else {
            return $config;
        }
    }

    public function get( $key, $default=null ) {
        $config = $this->get_config();

        if ( isset( $config[ $key ] ) )
            return $config[ $key ];

        return $default;
    }

    public function set( $key, $value ) {
        $config = $this->get_config();

        if ( array_key_exists( $key, $config ) ){
            $config[ $key ] = $value;

            $this->set_config( $config );

            return true;
        }

        return false;
    }

    public function set_access_token( $key_or_token = '' ) {
        if ( $key_or_token == 'user_token' || $key_or_token == 'page_token' )
            $token = $this->get( $key_or_token );
        else
            $token = $key_or_token;
        $this->access_token = $token;
    }

    public function set_config( $config=array() ) {
        $defaults = array(
            'app_id' => '',
            'app_secret' => '',
            'user_token' => '',
            'page_token' => '',
            'user_id' => '',
            'page_id' => ''
        );

        $config = array_merge( $defaults, $config );
        array_walk( $config, create_function( '&$x', '$x = str_replace( " ", "", $x );' ) );

        $previous_config = $this->get_config();

        if ( ( $previous_config['app_id'] != $config['app_id'] ) || ( $previous_config['app_secret'] != $config['app_secret'] ) ) {
            $config['user_token'] = '';
            $config['user_id'] = '';
            $config['page_id'] = '';
            $config['page_token'] = '';
        } elseif ( $previous_config['user_token'] != $config['user_token'] ) {
            if ( !$config['user_token'] ) {
                $config['user_id'] = 0;
            } elseif( !$config['user_id'] ) {
                $this->set_access_token( $config['user_token'] );
                $response = $this->api_request( '/me', 'GET', array( 'fields' => 'id' ) );

                if ( !$response || !isset( $response->id ) )
                    $config['user_id'] = 0;
                else
                    $config['user_id'] = $response->id;
            }

            $config['page_id'] = '';
            $config['page_token'] = '';
        }

        update_option( 'awpcp-facebook-config', $config );
        return true;
    }

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function get_user_pages() {
        if ( !$this->get( 'user_id' ) || !$this->get( 'user_token' ) )
            return array();
        
        $pages = array();

        $this->set_access_token( 'user_token' );

        // Add own user page.
        $response = $this->api_request( '/me', 'GET', array( 'fields' => 'id,name' ) );
        if ( $response ) {
            $pages[] = array( 'id' => $response->id,
                              'name' => $response->name,
                              'access_token' => $this->get( 'user_token' ),
                              'profile' => true );
        }

        $response = $this->api_request( '/me/accounts' );
        if ( $response && isset( $response->data ) ) {
            foreach ( $response->data as &$p ) {
                if ( in_array( 'CREATE_CONTENT', $p->perms ) )
                    $pages[] = array( 'id' => $p->id,
                                      'name' => $p->name,
                                      'access_token' => $p->access_token );
            }
        }
	
    	return $pages;
    }

    public function get_login_url( $redirect_uri = '', $scope = '' ) {
        return sprintf( 'https://www.facebook.com/dialog/oauth?client_id=%s&redirect_uri=%s&scope=%s',
                        $this->get( 'app_id' ),
                        urlencode( $redirect_uri ),
                        urlencode( $scope )
                      );
    }

    public function token_from_code( $code, $redirect_uri='' ) {
        if ( !$code )
            return false;

        if ( !$redirect_uri ) {
            // Assume $redirect_uri is the current URL sans stuff added by FB.
            $redirect_uri  = '';
            $redirect_uri .= $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
            $redirect_uri .= $_SERVER['HTTP_HOST'];
            $redirect_uri .= $_SERVER['REQUEST_URI'];
            $redirect_uri = remove_query_arg( array( 'client_id', 'code', 'error', 'error_reason', 'error_description', 'redirect_uri' ), $redirect_uri );
        }

        $response = $this->api_request( '/oauth/access_token',
                                        'GET',
                                        array( 'redirect_uri' => $redirect_uri,
                                               'code' => $code ),
                                        true,
                                        false );

        if ( $response ) {
            parse_str( $response, $parts );
            return $parts['access_token'];
        }

        return '';
    }

    public function api_request( $path, $method = 'GET', $args = array(), $notoken=false, $json_decode=true ) {
        $this->last_error = '';

        $url = self::GRAPH_URL . '/' . ltrim( $path, '/' );
        $url .= '?client_id=' . $this->get( 'app_id' );
        $url .= '&client_secret=' . $this->get( 'app_secret' );

        if ( !$notoken && $this->access_token )
            $url .= '&access_token=' . $this->access_token;

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
}
