<?php

function awpcp_image_file_handler() {
    return new AWPCP_ImageFileHandler(
        awpcp_listing_image_file_validator(),
        awpcp_image_file_processor(),
        awpcp_uploads_manager(),
        awpcp()->settings
    );
}

class AWPCP_ImageFileHandler extends AWPCP_ListingFileHandler {

    private $settings;

    public function __construct( $validator, $processor, $uploads_manager, $settings ) {
        $this->validator = $validator;
        $this->processor = $processor;
        $this->uploads_manager = $uploads_manager;
        $this->settings = $settings;
    }

    public function can_handle( $file ) {
        return in_array( $file->get_mime_type(), $this->settings->get_runtime_option( 'image-mime-types' ) );
    }

    protected function move_file( $file ) {
        $this->uploads_manager->move_file_with_thumbnail_to( $file, 'images' );
    }
}
