<?php

class AWPCP_Media {

    public $metadata;

    const STATUS_AWAITING_APPROVAL = 'Awaiting-Approval';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECTED = 'Rejected';

    public function __construct( $id, $ad_id, $name, $path, $mime_type, $enabled, $status, $is_primary, $metadata, $created ) {
        $this->id = $id;
        $this->ad_id = $ad_id;
        $this->name = $name;
        $this->path = $path;
        $this->mime_type = $mime_type;
        $this->enabled = $enabled;
        $this->status = $status;
        $this->is_primary = $is_primary;
        $this->metadata = $metadata;
        $this->created = $created;
    }

    public static function create_from_object( $object ) {
        if ( isset( $object->metadata ) ) {
            $metadata = maybe_unserialize( $object->metadata );
        } else {
            $metadata = array();
        }

        if ( ! is_array( $metadata ) ) {
            $metadata = array();
        }

        return new AWPCP_Media(
            $object->id,
            $object->ad_id,
            $object->name,
            $object->path,
            $object->mime_type,
            $object->enabled,
            $object->status,
            $object->is_primary,
            $metadata,
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

    public function get_associated_paths() {
        $info = awpcp_utf8_pathinfo( AWPCPUPLOADDIR . $this->name );

        $filenames = apply_filters( 'awpcp-file-associated-paths', array(
            AWPCPUPLOADDIR . "{$info['basename']}",
            AWPCPUPLOADDIR . "{$info['filename']}-large.{$info['extension']}",
            AWPCPUPLOADDIR . "{$this->path}",
            AWPCPUPLOADDIR . str_replace( $info['filename'], "{$info['filename']}-large", $this->path ),
            AWPCPTHUMBSUPLOADDIR . "{$info['basename']}",
            AWPCPTHUMBSUPLOADDIR . "{$info['filename']}-primary.{$info['extension']}",
        ), $this );

        return $filenames;
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
        return $this->get_url_from_path( $this->get_original_file_path() );
    }

    private function get_url_from_path( $path ) {
        $url = $path ? $this->sanitize_url( str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $path ) ) : false;
        return apply_filters( 'awpcp-media-url-from-path', $url, $path, $this );
    }

    private function sanitize_url( $url ) {
        $windows_directory_separator = '\\';

        $url = str_replace( ' ', '%20', $url );
        $url = str_replace( $windows_directory_separator, '/', $url );

        return $url;
    }

    public function get_original_file_path() {
        return trailingslashit( AWPCPUPLOADDIR ) . $this->path;
    }

    public function get_large_image_url() {
        return $this->get_url_from_path( $this->get_large_image_path() );
    }

    public function get_large_image_path() {
        $file_path = $this->get_original_file_path();

        $alternatives = array(
            $this->get_path_with_suffix( $file_path, 'large' ),
            $file_path
        );

        return $this->get_path_from_alternatives( $alternatives );
    }

    private function get_path_with_suffix( $path, $suffix ) {
        $extension = awpcp_get_file_extension( $path );
        return str_replace( ".{$extension}", "-{$suffix}.{$extension}", $path );
    }

    private function get_path_from_alternatives( $alternatives ) {
        foreach ( $alternatives as $path ) {
            if ( file_exists( $path ) ) {
                return $path;
            }
        }

        return false;
    }

    public function get_primary_thumbnail_url() {
        return $this->get_url_from_path( $this->get_primary_thumbnail_path() );
    }

    public function get_primary_thumbnail_path() {
        $thumbnail_path = $this->get_thumbnail_path();

        $alternatives = array(
            $this->get_path_with_suffix( $thumbnail_path, 'primary' ),
            $thumbnail_path,
            $this->get_original_file_path(),
        );

        return $this->get_path_from_alternatives( $alternatives );
    }

    public function get_thumbnail_url() {
        return $this->get_url_from_path( $this->get_thumbnail_path() );
    }

    public function get_thumbnail_path() {
        $alternatives = apply_filters( 'awpcp-get-file-thumbnail-url-alternatives', array(
            trailingslashit( AWPCPTHUMBSUPLOADDIR ) . $this->name,
        ), $this );

        return $this->get_path_from_alternatives( $alternatives );
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
            $message = __( 'No file was found with id: %d', 'another-wordpress-classifieds-plugin' );
            throw new AWPCP_Exception( sprintf( $message, $file_id ) );
        }

        return $file;
    }
}
