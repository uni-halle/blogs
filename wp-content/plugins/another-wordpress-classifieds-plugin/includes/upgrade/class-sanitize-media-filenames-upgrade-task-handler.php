<?php

function awpcp_sanitize_media_filenames_upgrade_task_handler() {
    return new AWPCP_Sanitize_Media_Filenames_Upgrade_Task_Handler(
        awpcp_media_api(),
        $GLOBALS['wpdb']
    );
}

class AWPCP_Sanitize_Media_Filenames_Upgrade_Task_Handler {

    private $media_api;
    private $db;

    public function __construct( $media_api, $db ) {
        $this->media_api = $media_api;
        $this->db = $db;
    }

    public function run_task() {
        $last_file_id = get_option( 'awpcp-smfuth-last-file-id' );

        $pending_files_count = $this->count_pending_files( $last_file_id );
        $pending_files = $this->get_pending_files( $last_file_id );

        foreach ( $pending_files as $file ) {
            if ( $this->filename_needs_correction( $file->name ) ) {
                $this->sanitize_media_filenames( $file );
            }

            $last_file_id = $file->id;
        }

        update_option( 'awpcp-smfuth-last-file-id', $last_file_id );
        $remaining_files_count = $this->count_pending_files( $last_file_id );

        return array( $pending_files_count, $remaining_files_count );
    }

    private function count_pending_files( $last_file_id ) {
        $query = 'SELECT COUNT(id) FROM ' . AWPCP_TABLE_MEDIA . ' WHERE id > %d';
        return intval( $this->db->get_var( $this->db->prepare( $query, intval( $last_file_id ) ) ) );
    }

    private function get_pending_files( $last_file_id ) {
        $query = 'SELECT * FROM ' . AWPCP_TABLE_MEDIA . ' WHERE id > %d LIMIT 0, 100';
        return $this->db->get_results( $this->db->prepare( $query, intval( $last_file_id ) ) );
    }

    private function filename_needs_correction( $filename ) {
        $test_url = esc_url( sprintf( "http://example.com/%s", $filename ) );
        $escaped_filename = str_replace( 'http://example.com/', '', $test_url );

        return strcmp( $escaped_filename, $filename ) != 0;
    }

    private function sanitize_media_filenames( $file ) {
        $media = AWPCP_Media::create_from_object( $file );

        $assocaited_paths = $media->get_associated_paths();
        $target_directories = $this->extract_directories_from_paths( $assocaited_paths );

        $original_filename = awpcp_utf8_pathinfo( $media->name, PATHINFO_FILENAME );

        $sanitized_filename = awpcp_unique_filename( $media->get_original_file_path(), $media->name, $target_directories );
        $sanitized_filename = awpcp_utf8_pathinfo( $sanitized_filename, PATHINFO_FILENAME );

        foreach( $media->get_associated_paths() as $i => $path ) {
            if ( ! file_exists( $path ) ) {
                continue;
            }

            $file_was_renamed = $this->rename_file( $path, $original_filename, $sanitized_filename );

            if ( ! $file_was_renamed ) {
                add_option( "awpcp-smfuth-failure-{$file->id}-{$i}", array(
                    'path' => $path,
                    'old_filename' => $media->name,
                    'new_filename' => $sanitized_filename,
                ) );
            }
        }

        $media->path = str_replace( $original_filename, $sanitized_filename, $media->path );
        $media->name = str_replace( $original_filename, $sanitized_filename, $media->name );

        $this->media_api->save( $media );
    }

    private function extract_directories_from_paths( $paths ) {
        $directories = array();

        foreach ( $paths as $path ) {
            $directories[] = awpcp_utf8_pathinfo( $path, PATHINFO_DIRNAME );
        }

        return $directories;
    }

    private function rename_file( $path, $old_filename, $new_filename ) {
        return rename( $path, str_replace( $old_filename, $new_filename, $path ) );
    }
}
