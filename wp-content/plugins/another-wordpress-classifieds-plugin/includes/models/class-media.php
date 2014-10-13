<?php

class AWPCP_Media {

    const STATUS_AWAITING_APPROVAL = 'Awaiting-Approval';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECTED = 'Rejected';

    public function __construct( $id, $ad_id, $name, $path, $mime_type, $enabled, $status, $is_primary, $created ) {
        $this->id = $id;
        $this->ad_id = $ad_id;
        $this->name = $name;
        $this->path = $path;
        $this->mime_type = $mime_type;
        $this->enabled = $enabled;
        $this->status = $status;
        $this->is_primary = $is_primary;
        $this->created = $created;
    }

    public static function create_from_object( $object ) {
        return new AWPCP_Media(
            $object->id,
            $object->ad_id,
            $object->name,
            $object->path,
            $object->mime_type,
            $object->enabled,
            $object->status,
            $object->is_primary,
            $object->created
        );
    }

    public function is_image() {
        return in_array( $this->mime_type, awpcp_get_image_mime_types() );
    }

    public function is_primary() {
        return (bool) $this->is_primary;
    }

    public function get_url( $size = 'thumbnail' ) {
        $uploads_directories = awpcp_get_uploads_directories();
        $files_dir = $uploads_directories['files_dir'];

        $images = trailingslashit( AWPCPUPLOADURL );
        $thumbnails = trailingslashit( AWPCPTHUMBSUPLOADURL );

        $basename = $this->name;

        $original = $images . $basename;
        $thumbnail = $thumbnails . $basename;
        $suffix = empty( $size ) ? '.' : "-$size.";

        $info = awpcp_utf8_pathinfo( $original );

        if ( $size == 'original' ) {
            $alternatives = array( $original );
        } else if ( $size == 'large' ) {
            $alternatives = array(
                str_replace( ".{$info['extension']}", "$suffix{$info['extension']}", $original ),
                $original
            );
        } else {
            $alternatives = array(
                str_replace( ".{$info['extension']}", "$suffix{$info['extension']}", $thumbnail ),
                $thumbnail,
                $original
            );
        }

        foreach ( $alternatives as $path ) {
            if ( file_exists( str_replace( AWPCPUPLOADURL, $files_dir, $path ) ) ) {
                return $path;
            }
        }

        return false;
    }

    public function get_icon_url() {
        $url = AWPCP_URL . '/resources/images/page_white_picture.png';
        return apply_filters( 'awpcp-get-file-icon-url', $url, $this );
    }

    public function is_awaiting_approval() {
        return $this->status == self::STATUS_AWAITING_APPROVAL;
    }

    public function is_rejected() {
        return $this->status == self::STATUS_REJECTED;
    }

    public function is_approved() {
        return $this->status == self::STATUS_APPROVED;
    }
}

function awpcp_files_collection() {
    return new AWPCP_FilesCollection();
}

class AWPCP_FilesCollection {

    public function get( $file_id ) {
        $file = awpcp_media_api()->find_by_id( $file_id );

        if ( is_null( $file ) ) {
            $message = __( 'No file was found with id: %d', 'AWPCP' );
            throw new AWPCP_Exception( sprintf( $message, $file_id ) );
        }

        return $file;
    }
}
