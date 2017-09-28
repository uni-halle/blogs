<?php

class AWPCP_Exception extends Exception {

    private $errors = null;

    public function __construct( $message='', $errors=array() ) {
        parent::__construct( $message );
        $this->errors = $errors;
    }

    public function get_errors() {
        return array_filter( array_merge( array( $this->getMessage() ), (array) $this->errors ) );
    }

    public function format_errors() {
        return implode( ' ', $this->get_errors() );
    }
}

class AWPCP_IOError extends AWPCP_Exception {
}

class AWPCP_WPError extends AWPCP_Exception {

    public $wp_error;

    public function __construct( $wp_error ) {
        $this->wp_error = $wp_error;
    }
}

class AWPCP_RedirectionException extends AWPCP_Exception {

    public $step_name = null;
    public $request_method = null;

    public function __construct( $step_name, $request_method ) {
        $this->step_name = $step_name;
        $this->request_method = $request_method;
    }
}

class AWPCP_DatabaseException extends AWPCP_Exception {

    public function __construct( $exception_message, $db_error ) {
        parent::__construct( $this->prepare_exception_message( $exception_message, $db_error ) );
    }

    private function prepare_exception_message( $exception_message, $db_error ) {
        if ( ! empty( $db_error ) ) {
            return $exception_message . ' ' . $db_error;
        } else {
            return $exception_message;
        }
    }
}

class AWPCP_Easy_Digital_Downloads_Exception extends Exception {
}

class AWPCP_License_Request_Exception extends AWPCP_Easy_Digital_Downloads_Exception {
}

class AWPCP_No_Activations_Left_License_Request_Exception extends AWPCP_License_Request_Exception {
}

class AWPCP_Infinite_Loop_Detected_Exception extends AWPCP_Easy_Digital_Downloads_Exception {
}

class AWPCP_CSV_Importer_Exception extends Exception {

    private $errors;

    public function setErrors( $errors ) {
        $this->errors = $errors;
    }

    public function getErrors() {
        if ( $this->errors ) {
            return $this->errors;
        }

        return array( $this->getMessage() );
    }
}

class AWPCP_HTTP_Exception extends Exception {
}
