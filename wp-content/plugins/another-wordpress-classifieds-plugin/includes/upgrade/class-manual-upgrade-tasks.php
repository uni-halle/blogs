<?php

function awpcp_manual_upgrade_tasks() {
    return new AWPCP_Manual_Upgrade_Tasks(
        awpcp_manual_upgrade_tasks_manager(),
        awpcp_upgrade_task_ajax_handler_factory()
    );
}

class AWPCP_Manual_Upgrade_Tasks {

    private $upgrade_tasks;
    private $task_handlers;

    public function __construct( $upgrade_tasks, $task_handlers ) {
        $this->upgrade_tasks = $upgrade_tasks;
        $this->task_handlers = $task_handlers;
    }

    public function register_upgrade_tasks() {
        $this->upgrade_tasks->register_upgrade_task(
            'awpcp-import-payment-transactions', __( 'Import Payment Transactions', 'another-wordpress-classifieds-plugin' ),
            'awpcp_import_payment_transactions_task_handler'
        );

        $this->upgrade_tasks->register_upgrade_task(
            'awpcp-migrate-regions-information', __( 'Migrate Regions Information', 'another-wordpress-classifieds-plugin' ),
            'awpcp_migrate_regions_information_task_handler'
        );

        $this->upgrade_tasks->register_upgrade_task(
            'awpcp-migrate-media-information', __( 'Migrate Media Information', 'another-wordpress-classifieds-plugin' ),
            'awpcp_migrate_media_information_task_handler'
        );

        $this->upgrade_tasks->register_upgrade_task(
            'awpcp-update-media-status', __( 'Update Image/Attachments Status', 'another-wordpress-classifieds-plugin' ),
            'awpcp_update_media_status_task_handler'
        );

        $this->upgrade_tasks->register_upgrade_task(
            'awpcp-sanitize-media-filenames',  __( 'Remove invalid characters from media filenames', 'another-wordpress-classifieds-plugin' ),
            'awpcp_sanitize_media_filenames_upgrade_task_handler'
        );

        $this->upgrade_tasks->register_upgrade_task(
            'awpcp-calculate-image-dimensions',
            __( 'Calculate image dimensions', 'another-wordpress-classifieds-plugin' ),
            'awpcp_calculate_image_dimensions_upgrade_task_handler'
        );

        $this->upgrade_tasks->register_upgrade_task(
            'awpcp-store-phone-number-digits',
            __( 'Store phone number digits', 'another-wordpress-classifieds-plugin' ),
            'awpcp_store_phone_number_digits_upgrade_task_handler'
        );
    }

    public function register_upgrade_task_handlers() {
        $task_handler = $this->task_handlers->create_upgrade_task_ajax_handler( $this->upgrade_tasks );

        foreach ( $this->upgrade_tasks->get_pending_tasks() as $slug => $task ) {
            add_action( "wp_ajax_$slug", array( $task_handler, 'ajax' ) );
        }
    }

    public function has_pending_tasks() {
        if ( ! get_option( 'awpcp-pending-manual-upgrade' ) ) {
            return false;
        }

        if ( ! $this->upgrade_tasks->has_pending_tasks() ) {
            delete_option( 'awpcp-pending-manual-upgrade' );
            return false;
        }

        return true;
    }

    public function get_pending_tasks() {
        return $this->upgrade_tasks->get_pending_tasks();
    }

    public function enable_upgrade_task( $name ) {
        $this->upgrade_tasks->enable_upgrade_task( $name );
        update_option( 'awpcp-pending-manual-upgrade', true );
    }
}
