<?php

abstract class AWPCP_ListingFileValidator {

    protected $upload_limits;
    protected $validation_errors;

    public function __construct( $upload_limits, $validation_errors ) {
        $this->upload_limits = $upload_limits;
        $this->validation_errors = $validation_errors;
    }

    public function validate_file( $listing, $file ) {
        $upload_limits = $this->get_listing_upload_limits( $listing );

        if ( ! in_array( $file->get_mime_type(), $upload_limits['mime_types'] ) ) {
            $message = __( 'The type of the uploaded file <filename> is not allowed.', 'AWPCP' );
            $this->throw_file_validation_exception( $file, $message );
        }

        if ( ! $this->upload_limits->can_add_file_to_listing( $listing, $file ) ) {
            $message = $this->validation_errors->get_cannot_add_more_files_of_type_error_message();
            $this->throw_file_validation_exception( $file, $message );
        }

        if ( ! file_exists( $file->get_path() ) ) {
            $message = __( 'The file <filename> was not found in the temporary uploads directory.', 'AWPCP' );
            $this->throw_file_validation_exception( $file, $message );
        }

        $this->validate_file_size( $file, $upload_limits );
        $this->additional_verifications( $file, $upload_limits );
    }

    abstract protected function get_listing_upload_limits( $listing );

    private function throw_file_validation_exception( $file, $message ) {
        $message = str_replace( '<filename>', '<strong>' . $file->get_real_name() . '</strong>', $message );
        throw new AWPCP_Exception( $message );
    }

    private function validate_file_size( $file, $upload_limits ) {
        $filesize = filesize( $file->get_path() );

        if ( empty( $filesize ) || $filesize <= 0 ) {
            $message = __( 'There was an error trying to find out the file size of the file <filename>.', 'AWPCP' );
            $this->throw_file_validation_exception( $file, $message );
        }

        if ( $filesize > $upload_limits['max_file_size'] ) {
            $message = $this->validation_errors->get_file_is_too_large_error_message();
            $message = sprintf( $message, '<strong>' . $file->get_real_name() . '</strong>', $upload_limits['max_file_size'] );
            throw new AWPCP_Exception( $message );
        }

        if ( $filesize < $upload_limits['min_file_size'] ) {
            $message = __( 'The file <filename> is smaller than the minimum allowed file size of <bytes-count> bytes. The file was not uploaded.', 'AWPCP' );
            $message = str_replace( '<bytes-count>', $upload_limits['min_file_size'], $message );
            $this->throw_file_validation_exception( $file, $message );
        }
    }

    protected function additional_verifications( $file, $upload_limits ) {
        // nothing here!
    }
}
