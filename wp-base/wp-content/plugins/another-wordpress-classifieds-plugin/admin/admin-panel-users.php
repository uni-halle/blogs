<?php


/**
 * @since 2.1.4
 */
class AWPCP_AdminUsers {

    const USERS_SCREEN = 'users';

    private $table = null;

    public function __construct() {
        add_filter('manage_' . self::USERS_SCREEN . '_columns', array($this, 'get_columns'), 20);
        add_filter('manage_users_custom_column', array($this, 'custom_column'), 10, 3);
        add_action('load-users.php', array($this, 'scripts'));

        add_action('wp_ajax_awpcp-users-credit', array($this, 'ajax'));
        add_action('wp_ajax_awpcp-users-debit', array($this, 'ajax'));
    }

    private function get_table() {
        if (is_null($this->table)) {
            if (!get_current_screen())
                set_current_screen(self::USERS_SCREEN);
            $this->table = _get_list_table('WP_Users_List_Table');
        }

        return $this->table;
    }

    public function scripts() {
        wp_enqueue_script('awpcp-admin-users');
    }

    public function get_columns($columns) {
        $columns['balance'] = _x('Account Balance', 'credit system on users table', 'AWPCP');
        return $columns;
    }

    public function custom_column($value, $column, $user_id) {
        switch ($column) {
            case 'balance':
                $balance = awpcp_payments_api()->format_account_balance($user_id);
                $actions = array();

                if (awpcp_current_user_is_admin()) {
                    $url = add_query_arg('action', 'credit', awpcp_current_url());
                    $actions['credit'] = "<a class='credit' href='" . $url . "'>" . __('Add Credit', 'AWPCP') . "</a>";

                    $url = add_query_arg('action', 'debit', awpcp_current_url());
                    $actions['debit'] = "<a class='debit' href='" . $url . "'>" . __('Remove Credit', 'AWPCP') . "</a>";
                }

                $table = $this->get_table();
                $value = '<span class="balance">' . $balance . '</span>' . $table->row_actions($actions);
        }

        return $value;
    }

    public function ajax_edit_balance($user_id, $action) {
        $user = get_user_by('id', $user_id);

        if (is_null($user)) {
            $message = __("The specified User doesn't exists.", 'AWPCP');
            $response = array('status' => 'error', 'message' => $message);
        }

        if (isset($_POST['save'])) {
            $payments = awpcp_payments_api();
            $amount = (int) awpcp_post_param('amount', 0);

            if ($action == 'debit')
                $payments->remove_credit($user->ID, $amount);
            else
                $payments->add_credit($user->ID, $amount);

            $balance = $payments->format_account_balance($user->ID);

            $response = array('status' => 'success', 'balance' => $balance);
        } else {
            // load the table so the get_columns methods is properly called
            // when attempt to find out the number of columns in the table
            $table = $this->get_table();
            $columns = count(get_column_headers(self::USERS_SCREEN));

            ob_start();
                include(AWPCP_DIR . '/admin/templates/admin-panel-users-balance-form.tpl.php');
                $html = ob_get_contents();
            ob_end_clean();
            $response = array('html' => $html);
        }

        return $response;
    }

    public function ajax() {
        if (!awpcp_current_user_is_admin()) {
            return false;
        }

        $user_id = awpcp_post_param('user', 0);
        $action = str_replace('awpcp-users-', '', awpcp_post_param('action'));

        switch ($action) {
            case 'debit':
            case 'credit':
                $response = $this->ajax_edit_balance($user_id, $action);
                break;
            default:
                $response = array();
                break;
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}
