<?php

function awpcp_image_file_handler() {
    return new AWPCP_ListingFileHandler(
        awpcp_image_file_validator(),
        awpcp_image_file_mover(),
        awpcp_image_file_processor(),
        awpcp_image_media_creator()
    );
}

class AWPCP_ListingFileHandler {

    private $validator;
    private $mover;
    private $processor;
    private $creator;

    public function __construct( $validator, $mover, $processor, $creator ) {
        $this->validator = $validator;
        $this->mover = $mover;
        $this->processor = $processor;
        $this->creator = $creator;
    }

    public function handle_file( $listing, $file ) {
        $this->validator->validate_file( $listing, $file );
        $this->mover->move_file( $file );
        $this->processor->process_file( $listing, $file );

        return $this->creator->create_media( $listing, $file );
    }
}
