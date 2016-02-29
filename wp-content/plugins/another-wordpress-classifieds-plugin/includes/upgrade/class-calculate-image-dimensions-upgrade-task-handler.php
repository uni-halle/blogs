<?php

function awpcp_calculate_image_dimensions_upgrade_task_handler() {
    return new AWPCP_Calculate_Image_Dimensions_Upgrade_Task_Handler(
        awpcp_image_dimensions_generator(),
        $GLOBALS['wpdb']
    );
}

class AWPCP_Calculate_Image_Dimensions_Upgrade_Task_Handler {

    public function __construct( $image_dimensions_generator, $db ) {
        $this->image_dimensions_generator = $image_dimensions_generator;
        $this->db = $db;
    }

    public function run_task() {
        $last_file_id = get_option( 'awpcp-ciduth-last-file-id' );

        $pending_files_count = $this->count_pending_files( $last_file_id );
        $pending_files = $this->get_pending_files( $last_file_id );

        foreach ( $pending_files as $file ) {
            $image = AWPCP_Media::create_from_object( $file );
            $this->image_dimensions_generator->set_image_dimensions( $image );

            $last_file_id = $file->id;
        }

        update_option( 'awpcp-ciduth-last-file-id', $last_file_id );
        $remaining_files_count = $this->count_pending_files( $last_file_id );

        return array( $pending_files_count, $remaining_files_count );
    }

    private function count_pending_files( $last_file_id ) {
        $query = 'SELECT COUNT(id) FROM ' . AWPCP_TABLE_MEDIA . ' WHERE id > %d';
        return intval( $this->db->get_var( $this->db->prepare( $query, intval( $last_file_id ) ) ) );
    }

    private function get_pending_files( $last_file_id ) {
        $query = 'SELECT * FROM ' . AWPCP_TABLE_MEDIA . ' WHERE id > %d LIMIT 0, 10';
        return $this->db->get_results( $this->db->prepare( $query, intval( $last_file_id ) ) );
    }
}
