<?php

function awpcp_fix_empty_media_mime_type_upgrade_routine() {
    return new AWPCP_FixEmptyMediaMimeTypeUpgradeRoutine( awpcp_media_api(), awpcp()->settings, $GLOBALS['wpdb'] );
}

class AWPCP_FixEmptyMediaMimeTypeUpgradeRoutine {

    private $media;
    private $settings;
    private $db;

    public function __construct( $media, $settings, $db ) {
        $this->media = $media;
        $this->settings = $settings;
        $this->db = $db;
    }

    public function run() {
        $uploads_dir = $this->settings->get_option( 'uploadfoldername' );
        $files = $this->db->get_results( 'SELECT * FROM ' . AWPCP_TABLE_MEDIA . " WHERE mime_type = '' LIMIT 0, 100" );

        foreach ( $files as $file ) {
            $path = implode( DIRECTORY_SEPARATOR, array( WP_CONTENT_DIR, $uploads_dir, 'awpcp', $file->path ) );

            if ( function_exists( 'mime_content_type' ) ) {
                $file->mime_type = mime_content_type( $path );
            }

            if ( empty( $file->mime_type ) ) {
                $file->mime_type = sprintf( 'image/%s', awpcp_get_file_extension( $file->path ) );
            }

            $this->media->save( $file );
        }

        if ( count( $files ) == 0 ) {
            delete_option( 'awpcp-enable-fix-media-mime-type-upgrde' );
        }
    }
}
