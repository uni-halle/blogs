<?php

function awpcp_image_file_mover() {
    return new AWPCP_Image_File_Mover( awpcp_uploads_manager() );
}

class AWPCP_Image_File_Mover {

    private $uploads_manager;

    public function __construct( $uploads_manager ) {
        $this->uploads_manager = $uploads_manager;
    }

    public function move_file( $file ) {
        $this->uploads_manager->move_file_with_thumbnail_to( $file, 'images' );
    }
}
