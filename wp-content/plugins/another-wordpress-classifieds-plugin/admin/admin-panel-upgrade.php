<?php

require_once(AWPCP_DIR . '/includes/helpers/admin-page.php');


/**
 * @since 2.1.4
 */
class AWPCP_AdminUpgrade extends AWPCP_AdminPage {

    public function __construct($page=false, $title=false, $menu=false) {
        $page = $page ? $page : 'awpcp-admin-upgrade';
        $title = $title ? $title : _x('AWPCP Classifieds Management System - Manual Upgrade', 'awpcp admin menu', 'AWPCP');
        parent::__construct($page, $title, $menu);

        $this->upgrades = array(
            'awpcp-import-payment-transactions' => array(
                'name' => 'Import Payment Transactions',
            ),
            'awpcp-migrate-regions-information' => array(
                'name' => 'Migrate Regions Information',
            ),
            'awpcp-migrate-media-information' => array(
                'name' => 'Migrate Media Information',
            ),
            'awpcp-update-media-status' => array(
                'name' => 'Update Image/Attachments Status',
            ),
        );

        add_action( 'wp_ajax_awpcp-import-payment-transactions', array( $this, 'ajax_import_payment_transactions' ) );
        add_action('wp_ajax_awpcp-migrate-regions-information', array($this, 'ajax_migrate_regions_information'));
        add_action( 'wp_ajax_awpcp-migrate-media-information', array( $this, 'ajax_migrate_media_information' ) );
        add_action( 'wp_ajax_awpcp-update-media-status', array( $this, 'ajax_update_media_status' ) );
    }

    private function has_pending_upgrades() {
        foreach ($this->upgrades as $upgrade => $data) {
            if (get_option($upgrade)) {
                return true;
            }
        }

        return false;
    }

    private function count_pending_upgrades() {
        return count( $this->get_pending_upgrades() );
    }

    private function get_pending_upgrades() {
        $pending_upgrades = array();

        foreach ($this->upgrades as $upgrade => $data) {
            if (get_option($upgrade)) {
                $pending_upgrades[$upgrade] = $data;
            }
        }

        return $pending_upgrades;
    }

    private function update_pending_upgrades_status() {
        if (!$this->has_pending_upgrades()) {
            delete_option('awpcp-pending-manual-upgrade');
        }
    }

    public function dispatch() {
        echo $this->_dispatch();
    }

    private function _dispatch() {
        $pending_upgrades = $this->get_pending_upgrades();

        $tasks = array();
        foreach ( $pending_upgrades as $action => $data ) {
            $tasks[] = array('name' => $data['name'], 'action' => $action);
        }

        $messages = array(
            'introduction' => _x( 'Before you can use AWPCP again we need to upgrade your database. This operation may take a few minutes, depending on the amount of information stored. Please press the Upgrade button shown below to start the process.', 'awpcp upgrade', 'AWPCP' ),
            'success' => sprintf( _x( 'Congratulations. AWPCP has been successfully upgraded. You can now access all features. <a href="%s">Click here to Continue</a>.', 'awpcp upgrade', 'AWPCP' ), add_query_arg( 'page', 'awpcp.php' ) ),
            'button' => _x( 'Upgrade', 'awpcp upgrade', 'AWPCP' ),
        );

        $tasks = new AWPCP_AsynchronousTasksComponent( $tasks, $messages );

        return $this->render( 'content', $tasks->render() );
    }

    /**
     * ------------------------------------------------------------------------
     * Import Pyment Transactions
     */

    private function count_old_payment_transactions() {
        global $wpdb;

        $query = 'SELECT COUNT(option_name) FROM ' . $wpdb->options . ' ';
        $query.= "WHERE option_name LIKE 'awpcp-payment-transaction-%'";

        return (int) $wpdb->get_var($query);
    }

    public function ajax_import_payment_transactions() {
        global $wpdb;

        $existing_transactions = $this->count_old_payment_transactions();

        $query = 'SELECT option_name FROM ' . $wpdb->options . ' ';
        $query.= "WHERE option_name LIKE 'awpcp-payment-transaction-%' ";
        $query.= "LIMIT 0, 100";

        $transactions = $wpdb->get_col($query);

        foreach ($transactions as $option_name) {
            $hash = end(explode('-', $option_name));
            $transaction_errors = array();

            $transaction = AWPCP_Payment_Transaction::find_by_id($hash);
            if (is_null($transaction)) {
                $transaction = new AWPCP_Payment_Transaction(array('id' => $hash));
            }

            $data = maybe_unserialize( get_option( $option_name, null ) );

            // can't process this transaction, skip and delete old data
            if ( !is_array( $data ) ) {
                delete_option($option_name);
                continue;
            }

            $errors = awpcp_array_data('__errors__', array(), $data);
            $user_id = awpcp_array_data('user-id', null, $data);
            $amount = awpcp_array_data('amount', 0.0, $data);
            $items = awpcp_array_data('__items__', array(), $data);
            $created = awpcp_array_data('__created__', current_time('mysql'), $data);
            $updated = awpcp_array_data('__updated__', current_time('mysql'), $data);

            if ($type = awpcp_array_data('payment-term-type', false, $data)) {
                if (strcmp($type, 'ad-term-fee') === 0) {
                    $data['payment-term-type'] = 'fee';
                }
            }

            foreach ($data as $name => $value) {
                $transaction->set($name, $value);
            }

            foreach ($items as $item) {
                $transaction->add_item($item->id, $item->name, '', AWPCP_Payment_Transaction::PAYMENT_TYPE_MONEY, $amount);
                // at the time of this upgrade, only one item was supported.
                break;
            }

            if (awpcp_array_data('free', false, $data)) {
                $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_NOT_REQUIRED;
            }

            $totals = $transaction->get_totals();
            if ($totals['money'] === 0 || $transaction->get('payment-method', false) === '') {
                $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_NOT_REQUIRED;
            }

            if ($totals['money'] > 0 && $transaction->get('payment-method', false)) {
                $transaction->_set_status(AWPCP_Payment_Transaction::STATUS_PAYMENT);
            }

            if ($completed = awpcp_array_data('completed', null, $data)) {
                $transaction->completed = $completed;
                $transaction->payment_status = AWPCP_Payment_Transaction::PAYMENT_STATUS_COMPLETED;
                $transaction->_set_status(AWPCP_Payment_Transaction::STATUS_COMPLETED);
            }

            unset($data['__errors__']);
            unset($data['__items__']);
            unset($data['__created__']);
            unset($data['__updated__']);
            unset($data['user-id']);
            unset($data['completed']);
            unset($data['free']);

            $transaction->user_id = $user_id;
            $transaction->created = $created;
            $transaction->updated = $updated;
            $transaction->errors = $errors;
            $transaction->version = 1;

            // remove entries from wp_options table
            if ($transaction->save()) {
                delete_option($option_name);
            }
        }

        $remaining_transactions = $this->count_old_payment_transactions();

        // we are done here, let the plugin know so othrer upgrades
        // can be initiated or the plugin features can be enabled again.
        if ($remaining_transactions === 0) {
            delete_option('awpcp-import-payment-transactions');
            $this->update_pending_upgrades_status();
        }

        return $this->ajax_response( $existing_transactions, $remaining_transactions );
    }

    /**
     * ------------------------------------------------------------------------
     * Migrate Regions Information
     */

    private function count_ads_pending_region_information_migration($cursor) {
        global $wpdb;

        $sql = 'SELECT COUNT(ad_id) FROM ' . AWPCP_TABLE_ADS . ' ';
        $sql.= 'WHERE ad_id > %d';

        return intval( $wpdb->get_var( $wpdb->prepare( $sql, $cursor ) ) );
    }

    public function ajax_migrate_regions_information() {
        global $wpdb;

        if ( awpcp_column_exists( AWPCP_TABLE_ADS, 'ad_country' ) ) {
            $cursor = get_option( 'awpcp-migrate-regions-info-cursor', 0 );
            $total = $this->count_ads_pending_region_information_migration( $cursor );

            $sql = 'SELECT ad_id, ad_country, ad_state, ad_city, ad_county_village ';
            $sql.= 'FROM ' . AWPCP_TABLE_ADS . ' ';
            $sql.= 'WHERE ad_id > %d ORDER BY ad_id LIMIT 0, 100';

            $results = $wpdb->get_results( $wpdb->prepare( $sql, $cursor ) );

            $regions = awpcp_basic_regions_api();
            foreach ( $results as $ad ) {
                $region = array();
                if ( ! empty( $ad->ad_country ) ) {
                    $region['country'] = $ad->ad_country;
                }
                if ( ! empty( $ad->ad_county_village ) ) {
                    $region['county'] = $ad->ad_county_village;
                }
                if ( ! empty( $ad->ad_state ) ) {
                    $region['state'] = $ad->ad_state;
                }
                if ( ! empty( $ad->ad_city ) ) {
                    $region['city'] = $ad->ad_city;
                }

                if ( ! empty( $region ) ) {
                    // remove old data first
                    $regions->delete_by_ad_id( $ad->ad_id );
                    $regions->save( array_merge( array( 'ad_id' => $ad->ad_id ), $region ) );
                }

                $cursor = $ad->ad_id;
            }

            update_option( 'awpcp-migrate-regions-info-cursor', $cursor );
            $remaining = $this->count_ads_pending_region_information_migration( $cursor );

            if ( 0 === $remaining ) {
                // TODO: do this in the next version
                // $columns = array( 'ad_country, ad_state, ad_city, ad_county_village' );
                // foreach ( $columns as $column ) {
                //     $wpdb->query( sprintf( 'ALTER TABLE %s DROP COLUMN', AWPCP_TABLE_ADS, $column ) );
                // }

                // TODO: delete region_id column in a future upgrade and remove
                // all rows from ad_regions table that have no data in the country, county, state
                // and city columns.

                delete_option( 'awpcp-migrate-regions-information' );
                $this->update_pending_upgrades_status();
            }
        } else {
            $total = 0;
            $remaining = 0;
        }

        return $this->ajax_response( $total, $remaining );
    }

    /**
     * ------------------------------------------------------------------------
     * Migrate Media Information
     */

    public function ajax_migrate_media_information() {
        global $wpdb;

        if ( ! awpcp_table_exists( AWPCP_TABLE_ADPHOTOS ) ) {
            return $this->ajax_response( 0, 0 );
        }

        $cursor = get_option( 'awpcp-migrate-media-information-cursor', 0 );
        $total = $this->count_pending_images( $cursor );

        $sql = 'SELECT * FROM ' . AWPCP_TABLE_ADPHOTOS . ' ';
        $sql.= 'WHERE ad_id > %d ORDER BY key_id LIMIT 0, 100';

        $results = $wpdb->get_results( $wpdb->prepare( $sql, $cursor ) );

        $uploads = awpcp_setup_uploads_dir();
        $uploads = array_shift( $uploads );

        foreach ( $results as $image ) {
            $cursor = $image->ad_id;

            $filename = awpcp_get_image_url( $image->image_name );

            if ( empty( $filename ) ) continue;

            $path = str_replace( AWPCPUPLOADURL, $uploads, $filename );

            if ( function_exists( 'mime_content_type' ) ) {
                $mime_type = mime_content_type( $path );
            } else {
                $extension = awpcp_get_file_extension( $image->image_name );
                $mime_type = sprintf( 'image/%s', $extension );
            }

            $entry = array(
                'ad_id' => $image->ad_id,
                'path' => $image->image_name,
                'name' => $image->image_name,
                'mime_type' => strtolower( $mime_type ),
                'enabled' => ! $image->disabled,
                'is_primary' => $image->is_primary,
                'created' => awpcp_datetime(),
            );

            $wpdb->insert( AWPCP_TABLE_MEDIA, $entry );
        }

        update_option( 'awpcp-migrate-media-information-cursor', $cursor );
        $remaining = $this->count_pending_images( $cursor );

        if ( 0 === $remaining ) {
            // TODO: do this in the next version upgrade
            // $wpdb->query( 'DROP TABLE ' . AWPCP_TABLE_ADPHOTOS );

            delete_option( 'awpcp-migrate-media-information' );
            $this->update_pending_upgrades_status();
        }

        return $this->ajax_response( $total, $remaining );
    }

    private function count_pending_images($cursor) {
        global $wpdb;

        $sql = 'SELECT count(key_id) FROM ' . AWPCP_TABLE_ADPHOTOS . '  ';
        $sql.= 'WHERE ad_id > %d ORDER BY key_id LIMIT 0, 100';

        return intval( $wpdb->get_var( $wpdb->prepare( $sql, $cursor ) ) );
    }

    public function ajax_update_media_status() {
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

        delete_option( 'awpcp-update-media-status' );
        $this->update_pending_upgrades_status();

        return $this->ajax_response( 1, 0 );
    }

    /**
     * ------------------------------------------------------------------------
     * Ajax Response
     */

    private function ajax_response( $total, $remaining ) {
        $response = array(
            'status' => 'ok',
            'recordsCount' => $total,
            'recordsLeft' => $remaining
        );

        header( "Content-Type: application/json" );
        echo json_encode( $response );
        die();
    }
}
