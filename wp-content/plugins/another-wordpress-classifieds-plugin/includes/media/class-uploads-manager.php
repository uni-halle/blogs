<?php

function awpcp_uploads_manager() {
    return new AWPCP_UploadsManager( awpcp()->settings );
}

class AWPCP_UploadsManager {

    private $settings;

    public function __construct( $settings ) {
        $this->settings = $settings;
    }

    public function get_url_for_relative_path( $relative_path ) {
        $uploads_dir = $this->settings->get_runtime_option( 'awpcp-uploads-url' );
        return implode( DIRECTORY_SEPARATOR, array( $uploads_dir, $relative_path ) );
    }

    public function get_path_for_relative_path( $relative_path ) {
        $uploads_dir = $this->settings->get_runtime_option( 'awpcp-uploads-dir' );
        return implode( DIRECTORY_SEPARATOR, array( $uploads_dir, $relative_path ) );
    }

    public function move_file_to( $file, $relative_path, $related_directories = array() ) {
        $destination_dir = $this->get_path_for_relative_path( $relative_path );

        if ( ! file_exists( $destination_dir ) && ! mkdir( $destination_dir, awpcp_directory_permissions(), true ) ) {
            throw new AWPCP_Exception( __( "Destination directory doesn't exists and couldn't be created.", 'AWPCP' ) );
        }

        $target_directories = array_merge( array( $destination_dir ), $related_directories );
        $unique_filename = awpcp_unique_filename( $file->get_path(), $file->get_real_name(), $target_directories );
        $destination_path = implode( DIRECTORY_SEPARATOR, array( $destination_dir, $unique_filename ) );

        if ( rename( $file->get_path(), $destination_path ) ) {
            $file->set_path( $destination_path );
            chmod( $destination_path, 0644 );
        } else {
            unlink( $file->get_path() );

            $message = _x( 'The file %s could not be copied to the destination directory.', 'upload files', 'AWPCP' );
            $message = sprintf( $message, $file->get_real_name() );

            throw new AWPCP_Exception( $message );
        }

        return $file;
    }

    public function move_file_with_thumbnail_to( $file, $relative_path ) {
        $thumbnails_dir = $this->get_path_for_relative_path( 'thumbs' );
        return $this->move_file_to( $file, $relative_path, array( $thumbnails_dir ) );
    }
}
