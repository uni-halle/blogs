<?php

if ( ! class_exists( 'AWPCP_AjaxHandler' ) ) {

function awpcp_ajax_response() {
    return new AWPCP_AjaxResponse();
}

/**
 * @since 3.2.2
 */
class AWPCP_AjaxResponse {

    /**
     * @since 3.2.2
     */
    public function set_content_type( $content_type ) {
        header( sprintf( "Content-Type: %s", $content_type ) );
    }

    /**
     * @since 3.2.2
     */
    public function write( $content ) {
        echo $content;
    }

    /**
     * TODO: use wp_die instead of die()
     * @since 3.2.2
     */
    public function close() {
        die();
    }
}

/**
 * @since 3.2.2
 */
abstract class AWPCP_AjaxHandler {

    private $response;

    public function __construct( $response ) {
        $this->response = $response;
    }

    /**
     * @since 3.2.2
     */
    public abstract function ajax();

    /**
     * @since 3.2.2
     */
    protected function success( $params = array() ) {
        return $this->flush( array_merge( array( 'status' => 'ok' ), $params ) );
    }

    /**
     * @since 3.2.2
     */
    protected function error( $params = array() ) {
        return $this->flush( array_merge( array( 'status' => 'error' ), $params ) );
    }

    /**
     * @since 3.2.2
     */
    protected function progress_response( $records_count, $records_left ) {
        return $this->success( array( 'recordsCount' => $records_count, 'recordsLeft' => $records_left ) );
    }

    /**
     * @since 3.2.2
     */
    protected function response( $records_count, $records_left ) {
        _deprecated_function( __FUNCTION__, '3.2.2', 'AWPCP_AjaxHandler::progress_response' );
        return $this->progress_response( $records_count, $records_left );
    }

    /**
     * @since 3.2.2
     */
    protected function error_response( $error_message ) {
        return $this->error( array( 'error' => $error_message ) );
    }

    /**
     * @since 3.2.2
     */
    protected function multiple_errors_response( $errors ) {
        return $this->error( array( 'errors' => (array) $errors ) );
    }

    /**
     * @since 3.2.2
     */
    protected function flush( $array_response ) {
        $this->response->set_content_type( 'application/json' );
        $this->response->write( json_encode( $array_response ) );
        $this->response->close();
    }
}

}
