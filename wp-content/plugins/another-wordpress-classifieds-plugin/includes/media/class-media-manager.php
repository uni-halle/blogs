<?php

function awpcp_new_media_manager() {
    static $instance = null;

    if ( is_null( $instance ) ) {
        $instance = new AWPCP_Media_Manager(
            awpcp_file_handlers_manager(),
            awpcp_uploaded_file_logic_factory(),
            awpcp()->settings
        );
    }

    return $instance;
}

class AWPCP_Media_Manager {

    private $file_handlers;
    private $uploaded_file_logic_factory;
    private $settings;

    public function __construct( $file_handlers, $uploaded_file_logic_factory, $settings ) {
        $this->file_handlers = $file_handlers;
        $this->uploaded_file_logic_factory = $uploaded_file_logic_factory;
        $this->settings = $settings;
    }

    public function add_file( $listing, $uploaded_file ) {
        $file_logic = $this->uploaded_file_logic_factory->create_file_logic( $uploaded_file );
        $file_handler = $this->file_handlers->get_handler_for_file( $file_logic );
        return $file_handler->handle_file( $listing, $file_logic );
    }
}
