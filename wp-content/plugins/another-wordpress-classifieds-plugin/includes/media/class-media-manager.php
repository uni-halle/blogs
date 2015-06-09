<?php

function awpcp_new_media_manager() {
    static $instance = null;

    if ( is_null( $instance ) ) {
        $instance = new AWPCP_NewMediaManager(
            awpcp_media_api(),
            awpcp_uploaded_file_logic_factory(),
            awpcp()->settings
        );
    }

    return $instance;
}

class AWPCP_NewMediaManager {

    private $file_handlers;

    private $media_saver;
    private $uploaded_file_logic_factory;
    private $settings;

    public function __construct( $media_saver, $uploaded_file_logic_factory, $settings ) {
        $this->media_saver = $media_saver;
        $this->uploaded_file_logic_factory = $uploaded_file_logic_factory;
        $this->settings = $settings;
    }

    public function register_file_handler( $file_handler ) {
        $this->file_handlers[] = $file_handler;
    }

    public function add_file( $listing, $uploaded_file ) {
        $file_logic = $this->uploaded_file_logic_factory->create_file_logic( $uploaded_file );

        $file_handler = $this->get_file_handler( $file_logic );
        $file_logic = $file_handler->handle_file( $listing, $file_logic );

        return $this->create_media( $listing, $file_logic );
    }

    private function get_file_handler( $uploaded_file ) {
        foreach ( $this->file_handlers as $handler ) {
            if ( $handler->can_handle( $uploaded_file ) ) {
                return $handler;
            }
        }

        $message = _x( 'There is no file handler for this kind of file (<mime-type>). Aborting.', 'file uploads', 'AWPCP' );
        $message = str_replace( '<mime-type>', $uploaded_file->get_mime_type(), $message );

        throw new AWPCP_Exception( $message );
    }

    private function create_media( $listing, $file_logic ) {
        return $this->media_saver->create( array(
            'ad_id' => $listing->ad_id,
            'name' => $file_logic->get_name(),
            'path' => ltrim( $file_logic->get_relative_path(), DIRECTORY_SEPARATOR ),
            'mime_type' => $file_logic->get_mime_type(),
        ) );
    }
}
