<?php

function awpcp_image_media_creator() {
    return new AWPCP_Image_Media_Creator(
        awpcp_image_dimensions_generator(),
        awpcp_listing_media_creator()
    );
}

class AWPCP_Image_Media_Creator {

    private $image_dimensions_generator;
    private $media_creator;

    public function __construct( $image_dimensions_generator, $media_creator ) {
        $this->image_dimensions_generator = $image_dimensions_generator;
        $this->media_creator = $media_creator;
    }

    public function create_media( $listing, $file_logic ) {
        $image = $this->media_creator->create_media( $listing, $file_logic );

        $this->image_dimensions_generator->set_image_dimensions( $image );

        return $image;
    }
}
