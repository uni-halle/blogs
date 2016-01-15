<?php

function awpcp_file_handlers_manager() {
    return new AWPCP_File_Handlers_Manager();
}

class AWPCP_File_Handlers_Manager {

    private $handlers;

    public function get_handler_for_file( $uploaded_file ) {
        foreach ( $this->get_file_handlers() as $slug => $handler ) {
            if ( ! in_array( $uploaded_file->get_mime_type(), $handler['mime_types'] ) ) {
                continue;
            }

            if ( ! is_callable( $handler['constructor'] ) ) {
                continue;
            }

            $instance = call_user_func( $handler['constructor'] );

            if ( ! is_object( $instance ) ) {
                continue;
            }

            return $instance;
        }

        $message = _x( 'There is no file handler for this kind of file (<mime-type>). Aborting.', 'file uploads', 'another-wordpress-classifieds-plugin' );
        $message = str_replace( '<mime-type>', $uploaded_file->get_mime_type(), $message );

        throw new AWPCP_Exception( $message );
    }

    public function get_file_handlers() {
        if ( is_null( $this->handlers ) ) {
            $this->handlers = apply_filters( 'awpcp-file-handlers', array() );
        }

        return $this->handlers;
    }
}
