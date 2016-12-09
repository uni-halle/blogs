<?php

function awpcp_store_phone_number_digits_upgrade_task_handler() {
    return new AWPCP_Store_Phone_Number_Digits_Upgrade_Task_Handler(
        $GLOBALS['wpdb']
    );
}

class AWPCP_Store_Phone_Number_Digits_Upgrade_Task_Handler {

    public function __construct( $db ) {
        $this->db = $db;
    }

    public function run_task() {
        $last_item_id = get_option( 'awpcp-spnd-last-file-id' );

        $pending_items_count = $this->count_pending_items( $last_item_id );
        $pending_items = $this->get_pending_items( $last_item_id );

        foreach ( $pending_items as $item ) {
            $phone_number_digits = awpcp_get_digits_from_string( $item->ad_contact_phone );

            $this->db->update(
                AWPCP_TABLE_ADS,
                array( 'phone_number_digits' => $phone_number_digits ),
                array( 'ad_id' => $item->ad_id )
            );

            $last_item_id = $item->ad_id;
        }

        update_option( 'awpcp-spnd-last-file-id', $last_item_id, false );

        $remaining_items_count = $this->count_pending_items( $last_item_id );

        return array( $pending_items_count, $remaining_items_count );
    }

    private function count_pending_items( $last_item_id ) {
        $query = 'SELECT COUNT(ad_id) FROM ' . AWPCP_TABLE_ADS . ' WHERE ad_id > %d';
        return intval( $this->db->get_var( $this->db->prepare( $query, intval( $last_item_id ) ) ) );
    }

    private function get_pending_items( $last_item_id ) {
        $query = 'SELECT * FROM ' . AWPCP_TABLE_ADS . ' WHERE ad_id > %d ORDER BY ad_id LIMIT 0, 10';
        return $this->db->get_results( $this->db->prepare( $query, intval( $last_item_id ) ) );
    }
}
