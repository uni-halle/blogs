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

        if ( ! wp_verify_nonce( $nonce, 'awpcp-manage-listing-media-' . $listing->ad_id ) ) {
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

function awpcp_set_file_as_primary_ajax_handler() {
    return new AWPCP_SetFileAsPrimaryAjaxHandler( awpcp_media_api(), awpcp_files_collection(), awpcp_listings_collection(), awpcp_request(), awpcp_ajax_response() );
}

class AWPCP_SetFileAsPrimaryAjaxHandler extends AWPCP_FileActionAjaxHandker {

    protected function do_file_action( $file, $listing ) {
        if ( $file->is_image() ) {
            return $this->media->set_ad_primary_image( $listing, $file );
        } else {
            return apply_filters( 'awpcp-set-file-as-primary', false, $file, $listing );
        }
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

function awpcp_update_file_status_ajax_handler() {
    return new AWPCP_UpdateFileStatusAjaxHandler(
        awpcp_media_api(),
        awpcp_files_collection(),
        awpcp_listings_collection(),
        awpcp_request(),
        awpcp_ajax_response()
    );
}

class AWPCP_UpdateFileStatusAjaxHandler extends AWPCP_FileActionAjaxHandker {

    protected function do_file_action( $file, $listing ) {
        if ( $this->request->param( 'action' ) == 'awpcp-approve-file' ) {
            return $this->media->approve( $file );
        } else {
            return $this->media->reject( $file );
        }
    }
}
