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

    /**
     * Returns true if this file is a video file.
     * TODO: implement me!
     *
     * @since 3.4
     */
    public function is_video() {
        return false;
    }

    public function is_primary() {
        return (bool) $this->is_primary;
    }

    public function get_url( $size = 'thumbnail' ) {
        if ( $size == 'original' ) {
            return $this->get_original_file_url();
        } else if ( $size == 'large' ) {
            return $this->get_large_image_url();
        } else if ( $size == 'primary' ) {
            return $this->get_primary_thumbnail_url();
        } else {
            return $this->get_thumbnail_url();
        }
    }

    public function get_original_file_url() {
        return trailingslashit( AWPCPUPLOADURL ) . $this->path;
    }

    public function get_large_image_url() {
        $original_file_url = $this->get_original_file_url();

        $alternatives = array(
            $this->get_url_with_suffix( $original_file_url, 'large' ),
            $original_file_url
        );

        return $this->get_url_from_alternatives( $alternatives );
    }

    private function get_url_with_suffix( $base_url, $suffix ) {
        $extension = awpcp_get_file_extension( $this->get_original_file_url() );
        return str_replace( ".{$extension}", "-{$suffix}.{$extension}", $base_url );
    }

    private function get_url_from_alternatives( $alternatives ) {
        $home_url = get_site_url();
        $abs_path = rtrim( ABSPATH, '/' );

        foreach ( $alternatives as $path ) {
            if ( file_exists( str_replace( $home_url, $abs_path, $path ) ) ) {
                return $path;
            }
        }

        return false;
    }

    public function get_primary_thumbnail_url() {
        $thumbnail_url = $this->get_thumbnail_url();

        $alternatives = array(
            $this->get_url_with_suffix( $thumbnail_url, 'primary' ),
            $thumbnail_url,
            $this->get_original_file_url()
        );

        return $this->get_url_from_alternatives( $alternatives );
    }

    public function get_thumbnail_url() {
        $alternatives = apply_filters( 'awpcp-get-file-thumbnail-url-alternatives', array(
            trailingslashit( AWPCPTHUMBSUPLOADURL ) . $this->name,
        ), $this );

        return $this->get_url_from_alternatives( $alternatives );
    }

    public function get_icon_url() {
        $icon_url = AWPCP_URL . '/resources/images/page_white_picture.png';
        return apply_filters( 'awpcp-get-file-icon-url', $icon_url, $this );
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
