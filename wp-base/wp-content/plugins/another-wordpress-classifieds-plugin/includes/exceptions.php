<?php

class AWPCP_Exception extends Exception {

    private $errors = null;

    public function __construct( $message='', $errors=array() ) {
        parent::__construct( $message );
        $this->errors = $errors;
    }

    public function get_errors() {
        return array_filter( array_merge( array( $this->getMessage() ), $this->errors ) );
    }
}

class AWPCP_IOError extends AWPCP_Exception {
}

class AWPCP_RedirectionException extends AWPCP_Exception {

    public $step_name = null;
    public $request_method = null;

    public function __construct( $step_name, $request_method ) {
        $this->step_name = $step_name;
        $this->request_method = $request_method;
    }
}
