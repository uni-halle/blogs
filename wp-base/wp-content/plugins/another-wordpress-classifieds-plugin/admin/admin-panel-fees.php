<?php

require_once(AWPCP_DIR . '/classes/helpers/admin-page.php');
require_once(AWPCP_DIR . '/admin/admin-panel-fees-table.php');


/**
 * @since 2.1.4
 */
class AWPCP_AdminFees extends AWPCP_AdminPageWithTable {

    public function __construct() {
        $page = 'awpcp-admin-fees';
        $title = __('AWPCP Classifieds Management System - Listing Fees Management', 'AWPCP');
        parent::__construct($page, $title, __('Fees', 'AWPCP'));

        add_action('wp_ajax_awpcp-fees-add', array($this, 'ajax'));
        add_action('wp_ajax_awpcp-fees-edit', array($this, 'ajax'));
        add_action('wp_ajax_awpcp-fees-delete', array($this, 'ajax'));
    }

    public function get_table() {
        if (!is_null($this->table))
            return $this->table;

        $this->table = new AWPCP_FeesTable($this, array('screen' => 'classifieds_page_awpcp-admin-fees'));

        return $this->table;
    }

    public function page_url($params=array()) {
        $base = add_query_arg('page', $this->page, admin_url('admin.php'));
        return $this->url($params, $base);
    }

    /**
     * Handler for admin_print_styles hook associated to this page.
     */
    public function scripts() {
        wp_enqueue_script('awpcp-admin-fees');
    }

    public function actions($fee, $filter=false) {
        $actions = array();
        $actions['edit'] = array(__('Edit', 'AWPCP'), $this->url(array('action' => 'edit', 'id' => $fee->id)));
        $actions['trash'] = array(__('Delete', 'AWPCP'), $this->url(array('action' => 'delete', 'id' => $fee->id)));

        if (is_array($filter))
            $actions = array_intersect_key($actions, array_combine($filter, $filter));

        return $actions;
    }

    public function dispatch() {
        $this->get_table();

        $action = $this->get_current_action();

        switch ($action) {
            case 'delete':
                return $this->delete();
                break;
            case 'transfer':
                return $this->transfer();
            case 'index':
                return $this->index();
                break;
            default:
                awpcp_flash("Unknown action: $action", 'error');
                return $this->index();
                break;
        }
    }

    public function transfer() {
        $fee = AWPCP_Fee::find_by_id(awpcp_request_param('id', 0));
        if (is_null($fee)) {
            awpcp_flash(__("The specified Fee doesn't exists.", 'AWPCP'), 'error');
            return $this->index();
        }

        $recipient = AWPCP_Fee::find_by_id(awpcp_request_param('payment_term', 0));
        if (is_null($recipient)) {
            awpcp_flash(__("The selected Fee doesn't exists.", 'AWPCP'), 'error');
            return $this->index();
        }

        if (isset($_POST['transfer'])) {
            $errors = array();
            if ($fee->transfer_ads_to($recipient->id, $errors)) {
                $message = __('All Ads associated to Fee %s have been associated with Fee %s.', 'AWPCP');
                $message = sprintf($message, '<strong>' . $fee->name . '</strong>', '<strong>' . $recipient->name . '</strong>');
                awpcp_flash($message);
            } else {
                foreach ($errors as $error) awpcp_flash($error, 'error');
            }
            return $this->index();

        } else if (isset($_POST['cancel'])) {
            return $this->index();

        } else {
            $params = array('fee' => $fee, 'fees' => AWPCP_Fee::query());
            $template = AWPCP_DIR . '/admin/templates/admin-panel-fees-delete.tpl.php';
            echo $this->render($template, $params);
        }
    }

    public function delete() {
        $id = awpcp_request_param('id', 0);
        $fee = AWPCP_Fee::find_by_id($id);

        if (is_null($fee)) {
            awpcp_flash(__("The specified Fee doesn't exists.", 'AWPCP'), 'error');
            return $this->index();
        }

        $errors = array();

        if (AWPCP_Fee::delete($fee->id, $errors)) {
            awpcp_flash(__('The Fee was successfully deleted.', 'AWPCP'));
        } else {
            $where = sprintf("adterm_id = %d AND payment_term_type = 'fee'", $fee->id);
            $ads = AWPCP_Ad::find($where);

            if (empty($ads)) {
                foreach ($errors as $error) awpcp_flash($error, 'error');
            } else {
                $fees = AWPCP_Fee::query();

                if (count($fees) > 1) {
                    $message = __("The Fee couldn't be deleted because there are active Ads in the system that are associated with the Fee ID. You need to switch the Ads to a different Fee before you can delete the plan.", "AWPCP");
                    awpcp_flash($message, 'error');

                    $params = array(
                        'fee' => $fee,
                        'fees' => $fees
                    );

                    $template = AWPCP_DIR . '/admin/templates/admin-panel-fees-delete.tpl.php';

                    echo $this->render($template, $params);
                    return;
                } else {
                    $message = __("The Fee couldn't be deleted because there are active Ads in the system that are associated with the Fee ID. Please create a new Fee and try the delete operation again. AWPCP will help you to switch existing Ads to the new fee.", "AWPCP");
                    awpcp_flash($message, 'error');
                }
            }
        }

        return $this->index();
    }

    public function index() {
        $this->table->prepare_items();

        $template = AWPCP_DIR . '/admin/templates/admin-panel-fees.tpl.php';

        echo $this->render($template, array('table' => $this->table));
    }

    private function ajax_add($fee=null) {
        if (isset($_POST['save'])) {
            $errors = array();

            if (is_null($fee)) {
                $fee = new AWPCP_Fee($_POST);
            } else {
                $fee->update($_POST);
            }

            if ($fee->save($errors) === false) {
                $message = __('The form has errors', 'AWPCP');
                $response = array('status' => 'error', 'message' => $message, 'errors' => $errors);
            } else {
                $this->get_table();

                ob_start();
                    $this->table->single_row($fee);
                    $html = ob_get_contents();
                ob_end_clean();

                $response = array('status' => 'success', 'html' => $html);
            }

        } else {
            $this->get_table();
            $columns = count($this->table->get_columns());
            ob_start();
                include(AWPCP_DIR . '/admin/templates/admin-panel-fees-entry-form.tpl.php');
                $html = ob_get_contents();
            ob_end_clean();
            $response = array('html' => $html);
        }

        return $response;
    }

    private function ajax_edit($id) {
        $fee = AWPCP_Fee::find_by_id($id);
        if (is_null($fee)) {
            $message = __("The specified Fee doesn't exists.", 'AWPCP');
            $response = array('status' => 'error', 'message' => $message);
        } else {
            $response = $this->ajax_add($fee);
        }

        return $response;
    }

    private function ajax_delete($id) {
        $errors = array();

        if (is_null(AWPCP_Fee::find_by_id($id))) {
            $message = _x("The specified Credit Plan doesn't exists.", 'credit plans ajax', 'AWPCP');
            $response = array('status' => 'error', 'message' => $message);
        } else {
            $this->get_table();
            $columns = count($this->table->get_columns());
            ob_start();
                include(AWPCP_DIR . '/admin/templates/admin-panel-fees-delete-form.tpl.php');
                $html = ob_get_contents();
            ob_end_clean();
            $response = array('status' => 'success', 'html' => $html);
        }

        return $response;
    }

    public function ajax() {
        if (!awpcp_current_user_is_admin()) {
            return false;
        }

        $id = awpcp_post_param('id', 0);
        $action = str_replace('awpcp-fees-', '', awpcp_post_param('action'));
        $response = array();

        switch ($action) {
            case 'add':
                $response = $this->ajax_add();
                break;
            case 'edit':
                $response = $this->ajax_edit($id);
                break;
            case 'delete':
                $response = $this->ajax_delete($id);
                break;
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}
