<?php

function awpcp_image_file_processor() {
    return new AWPCP_ImageFileProcessor( awpcp()->settings );
}

class AWPCP_ImageFileProcessor {

    private $settings;

    public function __construct( $settings ) {
        $this->settings = $settings;
    }

    public function process_file( $listing, $file ) {
        $this->try_to_fix_image_rotation( $file );
        $this->resize_original_image( $file );
        $this->create_image_versions( $file );
    }

    private function try_to_fix_image_rotation( $file ) {
        if ( ! function_exists( 'exif_read_data' ) ) {
            return;
        }

        $exif_data = @exif_read_data( $filepath );

        $orientation = isset( $exif_data['Orientation'] ) ? $exif_data['Orientation'] : 0;
        $mime_type = isset( $exif_data['MimeType'] ) ? $exif_data['MimeType'] : '';

        $rotation_angle = 0;
        if ( 6 == $orientation ) {
            $rotation_angle = 90;
        } else if ( 3 == $orientation ) {
            $rotation_angle = 180;
        } else if ( 8 == $orientation ) {
            $rotation_angle = 270;
        }

        if ( $rotation_angle > 0 ) {
            awpcp_rotate_image( $filepath, $mime_type, $rotation_angle );
        }
    }

    private function resize_original_image( $file ) {
        $image_dir = $file->get_parent_directory();

        $width = $this->settings->get_option( 'imgmaxwidth' );
        $height = $this->settings->get_option( 'imgmaxheight' );

        return $this->make_intermediate_image_size( $file, $image_dir, $width, $height, false, 'large' );
    }

    private function make_intermediate_image_size( $file, $destination_dir, $width, $height, $crop = false, $suffix='' ) {
        if ( ! file_exists( $destination_dir ) && ! mkdir( $destination_dir, awpcp_directory_permissions(), true ) ) {
            throw new AWPCP_Exception( __( "Destination directory doesn't exists and couldn't be created.", 'AWPCP' ) );
        }

        $image = image_make_intermediate_size( $file->get_path(), $width, $height, $crop );

        $safe_suffix = empty( $suffix ) ? '.' : "-$suffix.";
        $image_name = $file->get_file_name() . $safe_suffix . $file->get_extension();
        $image_path = implode( DIRECTORY_SEPARATOR, array( $destination_dir, $image_name ) );

        if ( is_array( $image ) ) {
            $source_path = implode( DIRECTORY_SEPARATOR, array( $file->get_parent_directory(), $image['file'] ) );
            $result = rename( $source_path, $image_path );
        }

        if ( ! isset( $result ) || $result === false ) {
            $result = copy( $file->get_path(), $image_path );
        }

        chmod( $image_path, 0644 );

        return $result;
    }

    private function create_image_versions( $file ) {
        $thumbnails_dir = implode( DIRECTORY_SEPARATOR, array( $this->settings->get_runtime_option( 'awpcp-uploads-dir' ), 'thumbs' ) );

        // create thumbnail
        $width = $this->settings->get_option( 'imgthumbwidth' );
        $height = $this->settings->get_option( 'imgthumbheight' );
        $crop = $this->settings->get_option( 'crop-thumbnails' );
        $thumbnail_created = $this->make_intermediate_image_size( $file, $thumbnails_dir, $width, $height, $crop );

        // create primary image thumbnail
        $width = $this->settings->get_option( 'primary-image-thumbnail-width' );
        $height = $this->settings->get_option( 'primary-image-thumbnail-height' );
        $crop = $this->settings->get_option( 'crop-primary-image-thumbnails' );
        $primary_image_created = $this->make_intermediate_image_size( $file, $thumbnails_dir, $width, $height, $crop, 'primary' );

        return $thumbnail_created && $primary_image_created;
    }
}
