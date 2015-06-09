<?php

function awpcp_listing_image_file_validator() {
    return new AWPCP_ListingImageFileValidator( awpcp_listing_upload_limits(), awpcp_file_validation_errors() );
}

class AWPCP_ListingImageFileValidator extends AWPCP_ListingFileValidator {

    protected function get_listing_upload_limits( $listing ) {
        return $this->upload_limits->get_listing_upload_limits_by_file_type( $listing, 'images' );
    }

    protected function additional_verifications( $file, $upload_limits ) {
        $this->validate_image_dimensions( $file, $upload_limits );
    }

    private function validate_image_dimensions( $file, $image_upload_limits ) {
        $img_info = getimagesize( $file->get_path() );

        if ( ! isset( $img_info[ 0 ] ) && ! isset( $img_info[ 1 ] ) ) {
            $message = _x( 'There was an error trying to find out the dimension of <filename>. The file was not uploaded.', 'upload files', 'AWPCP' );
            $message = str_replace( '<filename>', '<strong>' . $file->get_real_name() . '</strong>' );
            throw new AWPCP_Exception( $message );
        }

        if ( $img_info[ 0 ] < $image_upload_limits['min_image_width'] ) {
            $message = _x( 'The image %s did not meet the minimum width of %s pixels. The file was not uploaded.', 'upload files', 'AWPCP');
            $message = sprintf( $message, '<strong>' . $file->get_real_name() . '</strong>', $image_upload_limits['min_image_width'] );
            throw new AWPCP_Exception( $message );
        }

        if ( $img_info[ 1 ] < $image_upload_limits['min_image_height'] ) {
            $message = _x( 'The image %s did not meet the minimum height of %s pixels. The file was not uploaded.', 'upload files', 'AWPCP');
            $message = sprintf( $message, '<strong>' . $file->get_real_name() . '</strong>', $image_upload_limits['min_image_height'] );
            throw new AWPCP_Exception( $message );
        }
    }
}
