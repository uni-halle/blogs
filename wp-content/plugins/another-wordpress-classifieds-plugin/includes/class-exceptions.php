<?php

function awpcp_exceptions() {
    return new AWPCP_Exceptions();
}

class AWPCP_Exceptions {

    public function license_request_exception( $specific_message, $module_name, $previous_message = '', $code = 0, $previous_exception = null ) {
        $placeholders = array(
            'module-name' => $this->strong( $module_name ),
            'previous-message' => $previous_message,
        );

        $message = $specific_message . ' ' . __( 'The error was: <previous-message>. Contact <support-link>customer support</a> for further assistance.', 'another-wordpress-classifieds-plugin' );
        $message = $this->replace_content_placeholders( $placeholders, $message );

        return new AWPCP_License_Request_Exception( $message, $code, $previous_exception );
    }

    private function strong( $string ) {
        return '<strong>' . $string . '</strong>';
    }

    private function replace_content_placeholders( $placeholders, $message ) {
        $placeholders = array_merge( $placeholders, array(
            'support-link' => '<a href="http://awpcp.com/contact" target="_blank">',
        ) );

        foreach ( $placeholders as $placeholder => $value ) {
            $message = str_replace( '<' . $placeholder . '>', $value, $message );
        }

        return $message;
    }

    public function no_activations_left_license_request_exception( $module_name, $previous_exception = null ) {
        $placeholders = array( 'module-name' => $this->strong( $module_name ), );

        $message = __( 'Your license for <module-name> is valid, but is already active on another site. Contact <support-link>customer support</a> for further assistance.', 'another-wordpress-classifieds-plugin' );
        $message = $this->replace_content_placeholders( $placeholders, $message );

        debugp( $message );

        return new AWPCP_No_Activations_Left_License_Request_Exception( $message, 0, $previous_exception );
    }
}
