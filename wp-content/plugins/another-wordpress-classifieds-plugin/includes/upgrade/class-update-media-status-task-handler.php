<?php

function awpcp_update_media_status_task_handler() {
    return new AWPCP_Update_Media_Status_Task_Handler();
}

class AWPCP_Update_Media_Status_Task_Handler {

    public function run_task() {
        global $wpdb;

        if ( get_awpcp_option( 'imagesapprove' ) ) {
            $query = 'UPDATE ' . AWPCP_TABLE_MEDIA . ' SET `status` = %s WHERE enabled = 1';
            $wpdb->query( $wpdb->prepare( $query, AWPCP_Media::STATUS_APPROVED ) );

            $query = 'UPDATE ' . AWPCP_TABLE_MEDIA . ' SET `status` = %s WHERE enabled = 0';
            $wpdb->query( $wpdb->prepare( $query, AWPCP_Media::STATUS_REJECTED ) );
        } else {
            $query = 'UPDATE ' . AWPCP_TABLE_MEDIA . ' SET `status` = %s';
            $wpdb->query( $wpdb->prepare( $query, AWPCP_Media::STATUS_APPROVED ) );
        }

        if ( get_awpcp_option( 'adapprove' ) && get_awpcp_option( 'imagesapprove' ) ) {
            $query = 'UPDATE ' . AWPCP_TABLE_MEDIA . ' m INNER JOIN ' . AWPCP_TABLE_ADS . ' a ';
            $query.= 'ON (m.ad_id = a.ad_id AND a.disabled = 1 AND a.disabled_date IS NULL) ';
            $query.= 'SET m.status = %s';
            $query.= 'WHERE m.enabled != 1';

            $query = $wpdb->prepare( $query, AWPCP_Media::STATUS_AWAITING_APPROVAL );

            $wpdb->query( $query );
        }

        return array( 1, 0 );
    }
}
