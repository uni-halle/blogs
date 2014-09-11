<?php

/**
 * @since 3.0.2
 */
function awpcp_request() {
    return new AWPCP_Request();
}

class AWPCP_Request {

    /**
     * @tested
     * @since 3.0.2
     */
    public function method() {
        return strtoupper( $_SERVER['REQUEST_METHOD'] );
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
        return isset( $_GET[ $name ] ) ? $_GET[ $name ] : $default;
    }

    /**
     * @tested
     * @since 3.0.2
     */
    public function get( $name, $default='' ) {
        return $this->get_param( $name, $default );
    }

    /**
     * @tested
     * @since 3.0.2
     */
    public function post_param( $name, $default='' ) {
        return isset( $_POST[ $name ] ) ? $_POST[ $name ] : $default;
    }

    /**
     * @tested
     * @since 3.0.2
     */
    public function post( $name, $default='' ) {
        return $this->post_param( $name, $default );
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
            return $this->get_query_var( 'cid' );
        } else {
            return $category_id;
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

        return $ad_id;
    }
}
