<?php

function awpcp_upload_generated_thumbnail_ajax_handler() {
    return new AWPCP_UploadGeneratedThumbnailAjaxHandler(
        awpcp_image_resizer(),
        awpcp_media_api(),
        awpcp_listings_collection(),
        awpcp()->settings,
        awpcp_request(),
        awpcp_ajax_response()
    );
}

class AWPCP_UploadGeneratedThumbnailAjaxHandler extends AWPCP_AjaxHandler {

    private $image_resizer;
    private $media;
    private $listings;
    private $settings;
    private $request;

    public function __construct( $image_resizer, $media, $listings, $settings, $request, $response ) {
        parent::__construct( $response );

        $this->image_resizer = $image_resizer;
        $this->listings = $listings;
        $this->media = $media;
        $this->settings = $settings;
        $this->request = $request;
    }

    public function ajax() {
        try {
            $this->try_to_process_uploaded_thumbnail();
        } catch ( AWPCP_Exception $e ) {
            return $this->multiple_errors_response( $e->get_errors() );
        }
    }

    private function try_to_process_uploaded_thumbnail() {
        $media = $this->media->find_by_id( $this->request->post( 'file' ) );

        if ( is_null( $media ) ) {
            throw new AWPCP_Exception( __( 'Trying to upload a thumbnail for an unknown file.', 'AWPCP' ) );
        }

        $listing = $this->listings->get( $media->ad_id );

        if ( ! $this->is_user_authorized_to_upload_thumbnails_to_listing( $listing ) ) {
            throw new AWPCP_Exception( __( 'You are not authorized to upload thumbnails.' ) );
        }

        $this->process_uploaded_thumbnail( $listing, $media );
    }

    private function is_user_authorized_to_upload_thumbnails_to_listing( $listing ) {
        if ( ! wp_verify_nonce( $this->request->post( 'nonce' ), 'awpcp-upload-generated-thumbnail-for-listing-' . $listing->ad_id ) ) {
            return false;
        }

        return true;
    }

    private function process_uploaded_thumbnail( $listing, $media ) {
        $temporary_thumbnail_file = $this->get_uploaded_thumbnail_path();
        $was_thumbnail_created = $this->image_resizer->create_thumbnail_for_media( $media, $temporary_thumbnail_file );
        unlink( $temporary_thumbnail_file );

        if ( $was_thumbnail_created ) {
            return $this->success( array( 'thumbnailUrl' => $media->get_thumbnail_url() ) );
        } else {
            $message = __( 'There was an error trying to store the uploaded thumbnail for file <filename>.', 'AWPCP' );
            $message = str_replace( '<filename>', $media->name, $message );
            return $this->multiple_errors_response( $message );
        }
    }

    private function get_uploaded_thumbnail_path() {
        $thumbnail = $this->request->post( 'thumbnail' );

        if ( ! preg_match( '/data:([^;]*);base64,(.*)/', $thumbnail, $matches ) ) {
            throw new AWPCP_Exception( __( 'No thumbnail data found.', 'AWPCP' ) );
        }

        $uploads_dir = $this->settings->get_runtime_option( 'awpcp-uploads-dir' );
        $filename = wp_unique_filename( $uploads_dir, 'uploaded-thumbnail.png' );
        $uploaded_thumbnail_path = $uploads_dir . DIRECTORY_SEPARATOR . $filename;

        file_put_contents( $uploaded_thumbnail_path, base64_decode( $matches[2] ) );

        return $uploaded_thumbnail_path;
    }
}
