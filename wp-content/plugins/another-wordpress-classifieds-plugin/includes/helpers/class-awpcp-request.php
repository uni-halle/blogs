<?php

/**
 * @since 3.0.2
 */
function awpcp_request() {
    return new AWPCP_Request();
}

class AWPCP_Request {

    /**
     * List extracted from http://stackoverflow.com/a/14536035/201354
     */
    private static $bot_user_agents_keywords = array(
        // https://developers.facebook.com/docs/sharing/best-practices#crawl
        'facebookexternalhit',
        'facebot',
        // https://support.google.com/webmasters/answer/1061943?hl=en
        'googlebot', 'mediapartners-google', 'adsbot-google',
        // http://www.bing.com/webmaster/help/which-crawlers-does-bing-use-8c184ec0
        'bingbot', 'msnbot', 'msnbot-media', 'adidxbot', 'bingpreview',
        // https://help.yahoo.com/kb/search/slurp-crawling-page-sln22600.html
        'yahoo! slurp',
        'crawler',
        'baiduspider',
        '80legs',
        'ia_archiver',
        'voyager',
        'curl',
        'wget',
    );

    /**
     * @tested
     * @since 3.0.2
     */
    public function method() {
        return strtoupper( $_SERVER['REQUEST_METHOD'] );
    }

    /**
     * Returns the domain used in the current request, optionally replacing
     * the www part of the domain with $www_prefix_replacement.
     *
     * @since 3.3
     */
    function domain( $include_www = true, $www_prefix_replacement = '' ) {
        $domain = isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : '';

        if ( empty( $domain ) ) {
            $domain = isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : '';
        }

        $should_replace_www = $include_www ? false : true;
        $domain_starts_with_www = substr( $domain, 0, 4 ) === 'www.';

        if ( $should_replace_www && $domain_starts_with_www ) {
            $domain = $www_prefix_replacement . substr( $domain, 4 );
        }

        return $domain;
    }

    /**
     * @tested
     * @since 3.0.2
     */
    public function param( $name, $default='' ) {
        return isset( $_REQUEST[ $name ] ) ? $_REQUEST[ $name ] : $default;
    }

    /**
     * @tested
     * @since 3.0.2
     */
    public function get_param( $name, $default='' ) {
        _deprecated_function( __FUNCTION__, '3.2.3', 'get( $name, $default )' );
        return $this->get( $name, $default );
    }

    /**
     * @tested
     * @since 3.0.2
     */
    public function get( $name, $default='' ) {
        return isset( $_GET[ $name ] ) ? $_GET[ $name ] : $default;
    }

    /**
     * @tested
     * @since 3.0.2
     */
    public function post_param( $name, $default='' ) {
        _deprecated_function( __FUNCTION__, '3.2.3', 'post( $name, $default )' );
        return $this->post( $name, $default );
    }

    /**
     * @since 3.3
     */
    public function all_request_params() {
        return $_REQUEST;
    }

    /**
     * @tested
     * @since 3.0.2
     */
    public function post( $name, $default='' ) {
        return isset( $_POST[ $name ] ) ? $_POST[ $name ] : $default;
    }

    /**
     * @tested
     * @since 3.0.2
     */
    public function get_query_var( $name, $default='' ) {
        $value = get_query_var( $name );
        return strlen( $value ) === 0 ? $default : $value;
    }

    /**
     * @tested
     * @since 3.0.2
     */
    public function get_category_id() {
        $category_id = $this->param( 'category_id', 0 );
        if ( empty( $category_id ) ) {
            return intval( $this->get_query_var( 'cid' ) );
        } else {
            return intval( $category_id );
        }
    }

    /**
     * @tested
     * @since 3.0.2
     */
    public function get_ad_id() {
        $ad_id = $this->param( 'adid' );
        $ad_id = empty( $ad_id ) ? $this->param( 'id' ) : $ad_id;
        $ad_id = empty( $ad_id ) ? $this->get_query_var( 'id' ) : $ad_id;

        return intval( $ad_id );
    }

    /**
     * @since 3.3
     */
    public function get_current_user() {
        global $current_user;
        get_currentuserinfo();
        return $current_user;
    }

    public function is_bot() {
        $regexp = '/' . implode( '|', self::$bot_user_agents_keywords ) . '/';
        return (bool) preg_match( $regexp, strtolower( $_SERVER['HTTP_USER_AGENT'] ) );
    }
}
