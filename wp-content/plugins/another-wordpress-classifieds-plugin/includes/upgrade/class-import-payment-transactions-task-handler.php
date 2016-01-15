<?php

function awpcp_import_payment_transactions_task_handler() {
    return new AWPCP_Import_Payment_Transactions_Task_Handler();
}

class AWPCP_Import_Payment_Transactions_Task_Handler {

    public function run_task() {
        global $wpdb;

        $existing_transactions = $this->count_old_payment_transactions();

        $query = 'SELECT option_name FROM ' . $wpdb->options . ' ';
        $query.= "WHERE option_name LIKE 'awpcp-payment-transaction-%' ";
        $query.= "LIMIT 0, 100";

        $transactions = $wpdb->get_col($query);

        foreach ($transactions as $option_name) {
            $option_name_parts = explode( '-', $option_name );
            $hash = end( $option_name_parts );
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

        return array( $existing_transactions, $remaining_transactions );
    }

    private function count_old_payment_transactions() {
        global $wpdb;

        $query = 'SELECT COUNT(option_name) FROM ' . $wpdb->options . ' ';
        $query.= "WHERE option_name LIKE 'awpcp-payment-transaction-%'";

        return (int) $wpdb->get_var($query);
    }
}
