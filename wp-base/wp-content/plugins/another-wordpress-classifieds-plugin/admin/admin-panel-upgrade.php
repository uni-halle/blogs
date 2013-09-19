<?php

require_once(AWPCP_DIR . '/classes/helpers/admin-page.php');


/**
 * @since 2.1.4
 */
class AWPCP_AdminUpgrade extends AWPCP_AdminPage {

    public function __construct($page=false, $title=false, $menu=false) {
        $page = $page ? $page : 'awpcp-admin-upgrade';
        $title = $title ? $title : _x('AWPCP Classifieds Management System - Manual Upgrade', 'awpcp admin menu', 'AWPCP');
        parent::__construct($page, $title, $menu);

        $this->upgrades = array(
            'awpcp-import-payment-transactions' => array($this, 'import_payment_transactions'),
        );

        add_action('wp_ajax_awpcp-import-payment-transaction', array($this, 'ajax_import_payment_transactions'));
    }

    private function has_pending_upgrades() {
        foreach ($this->upgrades as $upgrade => $callback) {
            if (get_option($upgrade)) {
                return true;
            }
        }

        return false;
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
        foreach ($this->upgrades as $upgrade => $callback) {
            if (get_option($upgrade)) {
                return call_user_func($callback);
            }
        }

        // seems like there are no pending upgrade, let's clear
        // the pending-manual-upgrade flag
        $this->update_pending_upgrades_status();

        // ... and tell to the user everything is ready
        $template = AWPCP_DIR . '/admin/templates/admin-panel-upgrade.tpl.php';
        return $this->render($template, array('url' => add_query_arg('page', 'awpcp.php')));
    }

    private function count_old_payment_transactions() {
        global $wpdb;

        $query = 'SELECT COUNT(option_name) FROM ' . $wpdb->options . ' ';
        $query.= "WHERE option_name LIKE 'awpcp-payment-transaction-%'";

        return (int) $wpdb->get_var($query);
    }

    private function import_payment_transactions() {
        if ($this->count_old_payment_transactions() === 0) {
            delete_option('awpcp-import-payment-transactions');
            return $this->dispatch();
        }

        $params = array(
            'url' => add_query_arg('page', 'awpcp.php'),
            'action' => 'awpcp-import-payment-transaction',
        );

        $template = AWPCP_DIR . '/admin/templates/admin-panel-upgrade-import-payment-transactions.tpl.php';
        return $this->render($template, $params);
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

            $data = get_option($option_name, null);

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
                $transaction->add_item($item->id, $item->name, '', 'money', $amount);
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

        $response = array('total' => $existing_transactions, 'remaining' => $remaining_transactions);

        header( "Content-Type: application/json" );
        echo json_encode($response);
        die();
    }
}
