<?php

function awpcp_migrate_media_information_task_handler() {
    return new AWPCP_Migrate_Media_Information_Task_Handler( $GLOBALS['wpdb'] );
}

class AWPCP_Migrate_Media_Information_Task_Handler {

    private $db;

    public function __construct( $db ) {
        $this->db = $db;
    }

    /**
     * TODO: do this in the next version upgrade
     * $this->db->query( 'DROP TABLE ' . AWPCP_TABLE_ADPHOTOS );
     */
    public function run_task() {
        $mime_types = awpcp_mime_types();

        if ( ! $this->photos_table_exists() ) {
            return array( 0, 0 );
        }

        $cursor = get_option( 'awpcp-migrate-media-information-cursor', 0 );
        $total = $this->count_pending_images( $cursor );

        $sql = 'SELECT * FROM ' . AWPCP_TABLE_ADPHOTOS . ' ';
        $sql.= 'WHERE ad_id > %d ORDER BY key_id LIMIT 0, 100';

        $results = $this->db->get_results( $this->db->prepare( $sql, $cursor ) );

        $uploads = awpcp_setup_uploads_dir();
        $uploads = array_shift( $uploads );

        foreach ( $results as $image ) {
            $cursor = $image->ad_id;

            if ( file_exists( AWPCPUPLOADDIR . $image->image_name ) ) {
                $relative_path = $image->image_name;
            } else if ( file_exists( AWPCPUPLOADDIR . 'images/' . $image->image_name ) ) {
                $relative_path = 'images/' . $image->image_name;
            } else {
                continue;
            }

            $mime_type = $mime_types->get_file_mime_type( AWPCPUPLOADDIR . $relative_path );

            $entry = array(
                'ad_id' => $image->ad_id,
                'path' => $relative_path,
                'name' => $image->image_name,
                'mime_type' => strtolower( $mime_type ),
                'enabled' => ! $image->disabled,
                'is_primary' => $image->is_primary,
                'created' => awpcp_datetime(),
            );

            $this->db->insert( AWPCP_TABLE_MEDIA, $entry );
        }

        update_option( 'awpcp-migrate-media-information-cursor', $cursor );
        $remaining = $this->count_pending_images( $cursor );

        return array( $total, $remaining );
    }

    protected function photos_table_exists() {
        return awpcp_table_exists( AWPCP_TABLE_ADPHOTOS );
    }

    private function count_pending_images($cursor) {
        $sql = 'SELECT count(key_id) FROM ' . AWPCP_TABLE_ADPHOTOS . '  ';
        $sql.= 'WHERE ad_id > %d ORDER BY key_id LIMIT 0, 100';

        return intval( $this->db->get_var( $this->db->prepare( $sql, $cursor ) ) );
    }
}
