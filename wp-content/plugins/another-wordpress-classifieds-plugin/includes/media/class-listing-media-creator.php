<?php

function awpcp_listing_media_creator() {
    return new AWPCP_Listing_Media_Creator( awpcp_media_api() );
}

class AWPCP_Listing_Media_Creator {

    private $media_saver;

    public function __construct( $media_saver ) {
        $this->media_saver = $media_saver;
    }

    public function create_media( $listing, $file_logic ) {
        return $this->media_saver->create( array(
            'ad_id' => $listing->ad_id,
            'name' => $file_logic->get_name(),
            'path' => ltrim( $file_logic->get_relative_path(), DIRECTORY_SEPARATOR ),
            'mime_type' => $file_logic->get_mime_type(),
        ) );
    }
}
