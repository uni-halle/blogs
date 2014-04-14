<?php

/**
 * @since 3.0.2
 */
class AWPCP_MediaManager {

    public function dispatch( $page ) {
        $this->page = $page;

        $action = $page->get_current_action();

        $ad = AWPCP_Ad::find_by_id( awpcp_request_param( 'id', awpcp_request_param( 'adid', false ) ) );
        $media = awpcp_media_api()->find_by_id( awpcp_request_param( 'picid' ) );

        $current_user_id = get_current_user_id();
        $is_admin_user = awpcp_current_user_is_admin();
        $ad_belongs_to_user = AWPCP_Ad::belongs_to_user( $ad->ad_id, $current_user_id );

        if ( ! $is_admin_user && ! $ad_belongs_to_user ) {
            $message = _x( "You don't have sufficient permissions to modify that Ad's media", 'media manager', 'AWPCP' );
            return $this->page->render( 'content', awpcp_print_error( $message ) );
        }

        if ( ! is_null( $media ) && $ad->ad_id != $media->ad_id ) {
            $message = _x( 'The specified file does not belongs to the specified Ad. No action will be performed.', 'media managear', 'AWPCP' );
            awpcp_flash( $message, 'error' );

            $action = 'show_images';
        }

        $media_actions = array( 'deletepic', 'rejectpic', 'approvepic', 'set-primary-image' );
        if ( is_null( $media ) && in_array( $action, $media_actions ) ) {
            $message = _x( 'The specified file does not exists. No action will be performed.', 'media managear', 'AWPCP' );
            awpcp_flash( $message, 'error' );

            $action = 'show_images';
        }

        switch ( $action ) {
            case 'deletepic':
                return $this->delete_file( $ad, $media );

            case 'rejectpic':
                return $this->disable_picture( $ad, $media );

            case 'approvepic':
                return $this->enable_picture( $ad, $media );

            case 'set-primary-image':
                return $this->set_primary_image( $ad, $media );

            case 'add-image':
                return $this->add_image( $ad, $media );

            default:
                return $this->show_images( $ad );
        }
    }

    public function delete_file( $ad, $media ) {
        if ( awpcp_media_api()->delete( $media ) ) {
            awpcp_flash( _x( 'The file has been deleted.', 'media manager', 'AWPCP' ) );
        } else {
            awpcp_flash( _x( 'Unable to delete your file, please contact the administrator.', 'media manager', 'AWPCP' ) );
        }
        return $this->show_images( $ad );
    }

    public function disable_picture( $ad, $media ) {
        if ( awpcp_media_api()->disable( $media ) ) {
            awpcp_flash( _x( 'The file has been disabled and can no longer be viewed.', 'media manager', 'AWPCP' ) );
        } else {
            awpcp_flash( _x( 'There was an error trying to disable the file.', 'media manager', 'AWPCP' ) );
        }
        return $this->show_images( $ad );
    }

    public function enable_picture( $ad, $media ) {
        if ( awpcp_media_api()->enable( $media ) ) {
            awpcp_flash( _x( 'The Image has been enabled and can now be viewed.', 'media manager', 'AWPCP' ) );
        } else {
            awpcp_flash( _x( 'There was an error trying to enable the file.', 'media manager', 'AWPCP' ) );
        }
        return $this->show_images( $ad );
    }

    public function set_primary_image( $ad, $media ) {
        if ( ! $media->is_image() ) {
            $message = _x( 'The specified file is not an image. It cannot be set as the primary image.', 'media manager', 'AWPCP' );
            awpcp_flash( $message, 'error' );
        } else if ( awpcp_media_api()->set_ad_primary_image( $ad, $media ) ) {
            awpcp_flash( _x( 'The image has been set as primary image.', 'media manager', 'AWPCP' ) );
        } else {
            awpcp_flash( _x( 'There was an error trying to set the image as the primary image.', 'media manager', 'AWPCP' ) );
        }
        return $this->show_images( $ad );
    }

    public function add_image( $ad, $media ) {
        $action = awpcp_post_param( 'awpcp_action', false );

        $errors = array();

        if ( $action !== 'add_image' || is_null( $ad ) ) {
            return $this->show_images( $ad );
        }

        if ( $_FILES['awpcp_add_file']['error'] !== 0 ) {
            $message = awpcp_uploaded_file_error( $_FILES['awpcp_add_file'] );
            awpcp_flash( end( $message ), 'error' );
        } else if ( wp_verify_nonce( $_POST['_wpnonce'], 'awpcp_upload_image' ) ) {
            $files = array( 'awpcp_add_file' => $_FILES['awpcp_add_file'] );
            $uploaded = awpcp_upload_files( $ad, $files, $errors );

            if ( empty( $uploaded ) ) {
                $message = _x( 'There was an error trying to upload your file.', 'media manager', 'AWPCP' );
                awpcp_flash( awpcp_array_data( 'awpcp_add_file', $message, $errors ), 'error' );
            } else {
                $admin_must_approve = get_awpcp_option( 'imagesapprove' );
                $is_admin_user = awpcp_current_user_is_admin();

                if ( ! $is_admin_user && $admin_must_approve ) {
                    awpcp_ad_awaiting_approval_email( $ad, false, true );
                }

                awpcp_flash( _x( 'The file was properly uploaded.', 'media manager', 'AWPCP' ) );
            }
        }

        return $this->show_images( $ad );
    }

    private function get_files( $ad ) {
        $allowed_mime_types = awpcp_get_allowed_mime_types();
        $image_mime_types = awpcp_get_image_mime_types();

        $files = awpcp_media_api()->find_by_ad_id( $ad->ad_id, array(
            'order' => array( 'mime_type ASC', 'id ASC' ),
        ) );

        $groups = array();
        foreach ( $files as $file ) {
            $extension = strtolower( awpcp_get_file_extension( $file->name ) );
            $mime_type = $file->mime_type;

            if ( ! in_array( $mime_type, $allowed_mime_types ) ) {
                continue;
            } else if ( in_array( $mime_type, $image_mime_types ) ) {
                $groups[ 'images' ][] = $file;
            } else {
                $groups[ $extension ][] = $file;
            }
        }

        return $groups;
    }

    public function show_images( $ad ) {
        $title = __( 'AWPCP Classifieds Management System - Manage Images', 'AWPCP' );

        $this->page->title = apply_filters( 'awpcp-media-manager-page-title', $title );
        $this->page->page = 'awpcp-admin-images';

        $urls = array(
            'endpoint' => $this->page->url( array( 'action' => 'manage-images') ),
            'view-listing' => $this->page->url( array( 'action' => 'view', 'id' => $ad->ad_id ) ),
            'listings' => $this->page->url( array( 'id' => null ) ),
        );

        $hidden = array( 'adid' => $ad->ad_id, );
        $groups = $this->get_files( $ad );

        $actions = array(
            'deletepic' => _x( 'Delete', 'media manager', 'AWPCP' ),
            'approvepic' => _x( 'Enable', 'media manager', 'AWPCP' ),
            'rejectpic' => _x( 'Disable', 'media manager', 'AWPCP' ),
            'set-primary-image' =>_x( 'Set as primary', 'media manager', 'AWPCP' ),
        );

        if ( ! awpcp_current_user_is_admin() && get_awpcp_option( 'imagesapprove' ) ) {
            unset( $actions['approvepic'] );
            unset( $actions['rejectpic'] );
        }

        ob_start();
            include( AWPCP_DIR . '/admin/templates/admin-panel-media-manager.tpl.php' );
            $content = ob_get_contents();
        ob_end_clean();

        return $this->page->render( 'content', $content );
    }
}

function awpcp_media_manager() {
    return new AWPCP_MediaManager();
}
