<?php

abstract class AWPCP_FileActionAjaxHandker extends AWPCP_AjaxHandler {
    protected $media;
    protected $files;
    protected $listings;
    protected $request;

    public function __construct( $media, $files, $listings, $request, $response ) {
        parent::__construct( $response );

        $this->media = $media;
        $this->files = $files;
        $this->listings = $listings;
        $this->request = $request;
    }

    public function ajax() {
        try {
            $this->try_to_do_file_action();
        } catch ( AWPCP_Exception $e ) {
            return $this->multiple_errors_response( $e->get_errors() );
        }
    }

    private function try_to_do_file_action() {
        $listing = $this->listings->get( $this->request->post( 'listing_id' ) );
        $file = $this->files->get( $this->request->post( 'file_id' ) );

        if ( $this->verify_user_is_allowed_to_perform_file_action( $file, $listing ) ) {
            if ( ! $this->do_file_action( $file, $listing ) ) {
                throw new AWPCP_Exception( __( 'There was an error trying to update the database.', 'AWPCP' ) );
            }
        }

        return $this->success();
    }

    protected function verify_user_is_allowed_to_perform_file_action( $file, $listing ) {
        $nonce = $this->request->post( 'nonce' );

        if ( ! wp_verify_nonce( $nonce, 'manage-listing-files-' . $listing->ad_id ) ) {
            throw new AWPCP_Exception( "You are not allowed to perform this action.", 'AWPCP' );
        }

        if ( $file->ad_id != $listing->ad_id ) {
            $message = __( "The specified file is not associated with Listing with ID %d.", 'AWPCP' );
            throw new AWPCP_Exception( sprintf( $message, $listing->ad_id ) );
        }

        return true;
    }

    protected abstract function do_file_action( $file, $listing );
}

function awpcp_set_image_as_primary_ajax_handler() {
    return new AWPCP_SetImageAsPrimaryAjaxHandler( awpcp_media_api(), awpcp_files_collection(), awpcp_listings_collection(), awpcp_request(), awpcp_ajax_response() );
}

class AWPCP_SetImageAsPrimaryAjaxHandler extends AWPCP_FileActionAjaxHandker {

    protected function do_file_action( $file, $listing ) {
        return $this->media->set_ad_primary_image( $listing, $file );
    }

    protected function verify_user_is_allowed_to_perform_file_action( $file, $listing ) {
        parent::verify_user_is_allowed_to_perform_file_action( $file, $listing );

        if ( ! $file->is_image() ) {
            throw new AWPCP_Exception( 'The selected file is not an image.', 'AWPCP' );
        }

        return true;
    }
}


function awpcp_update_file_enabled_status_ajax_handler() {
    return new AWPCP_UpdateFileEnabledStatusAjaxHandler( awpcp_media_api(), awpcp_files_collection(), awpcp_listings_collection(), awpcp_request(), awpcp_ajax_response() );
}

class AWPCP_UpdateFileEnabledStatusAjaxHandler extends AWPCP_FileActionAjaxHandker {

    protected function do_file_action( $file, $listing ) {
        $enabled_status = awpcp_parse_bool( $this->request->post( 'new_status' ) );

        if ( $enabled_status ) {
            return $this->media->enable( $file );
        } else {
            return $this->media->disable( $file );
        }
    }
}


function awpcp_delete_file_ajax_handler() {
    return new AWPCP_DeleteFileAjaxHandler( awpcp_media_api(), awpcp_files_collection(), awpcp_listings_collection(), awpcp_request(), awpcp_ajax_response() );
}

class AWPCP_DeleteFileAjaxHandler extends AWPCP_FileActionAjaxHandker {

    protected function do_file_action( $file, $listing ) {
        return $this->media->delete( $file );
    }
}
