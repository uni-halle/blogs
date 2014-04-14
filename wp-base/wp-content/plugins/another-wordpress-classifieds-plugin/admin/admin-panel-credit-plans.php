<?php

require_once(AWPCP_DIR . '/includes/helpers/admin-page.php');
require_once(AWPCP_DIR . '/admin/admin-panel-credit-plans-table.php');


/**
 * @since 2.1.4
 */
class AWPCP_AdminCreditPlans extends AWPCP_AdminPageWithTable {

    public function __construct() {
        $page = 'awpcp-admin-credit-plans';
        $title = __('AWPCP Classifieds Management System - Manage Credit Plans', 'AWPCP');
        $menu = __('Credit Plans', 'AWPCP');

        parent::__construct($page, $title, $menu);

        $this->table = null;

        add_action('wp_ajax_awpcp-credit-plans-add', array($this, 'ajax'));
        add_action('wp_ajax_awpcp-credit-plans-edit', array($this, 'ajax'));
        add_action('wp_ajax_awpcp-credit-plans-delete', array($this, 'ajax'));
    }

    /**
     * Handler for admin_print_styles hook associated to this page.
     */
    public function scripts() {
        wp_enqueue_script('awpcp-admin-credit-plans');
    }

    public function get_table() {
        if ( is_null( $this->table ) ) {
            $this->table = new AWPCP_CreditPlansTable( $this, array( 'screen' => 'classifieds_page_awpcp-admin-credit-plans' ) );
        }
        return $this->table;
    }

    public function actions($plan, $filter=false) {
        $actions = array();
        $actions['edit'] = array(__('Edit', 'AWPCP'), $this->url(array('action' => 'edit', 'id' => $plan->id)));
        $actions['trash'] = array(__('Delete', 'AWPCP'), $this->url(array('action' => 'delete', 'id' => $plan->id)));

        if (is_array($filter))
            $actions = array_intersect_key($actions, array_combine($filter, $filter));

        return $actions;
    }

    public function dispatch() {

        $action = $this->get_current_action();

        switch ($action) {
            case 'index':
                return $this->index();
                break;
            default:
                awpcp_flash("Unknown action: $action", 'error');
                return $this->index();
                break;
        }
    }

    public function index() {
        global $awpcp;

        $this->table->prepare_items();

        $template = AWPCP_DIR . '/admin/templates/admin-panel-credit-plans.tpl.php';
        $option = $awpcp->settings->option;

        echo $this->render( $template, array('table' => $this->table, 'option' => $option ) );
    }

    private function ajax_add($plan=null) {
        if (isset($_POST['save'])) {
            $errors = array();

            $plan = new AWPCP_CreditPlan($_POST);

            if ($plan->save($errors) === false) {
                $message = __('The form has errors', 'AWPCP');
                $response = array('status' => 'error', 'message' => $message, 'errors' => $errors);
            } else {
                if (is_null($this->table)) {
                    $args = array('screen' => 'classifieds_page_awpcp-admin-credit-plans');
                    $this->table = new AWPCP_CreditPlansTable($this, $args);
                }

                ob_start();
                    $this->table->single_row($plan);
                    $html = ob_get_contents();
                ob_end_clean();

                $response = array('status' => 'success', 'html' => $html);
            }

        } else {
            ob_start();
                include(AWPCP_DIR . '/admin/templates/admin-panel-credit-plans-entry-form.tpl.php');
                $html = ob_get_contents();
            ob_end_clean();
            $response = array('html' => $html);
        }

        return $response;
    }

    private function ajax_edit($id) {
        $plan = AWPCP_CreditPlan::find_by_id($id);
        if (is_null($plan)) {
            $message = _x("The specified Credit Plan doesn't exists.", 'credit plans ajax', 'AWPCP');
            $response = array('status' => 'error', 'message' => $message);
        } else {
            $response = $this->ajax_add($plan);
        }

        return $response;
    }

    private function ajax_delete($id) {
        $errors = array();

        if (is_null(AWPCP_CreditPlan::find_by_id($id))) {
            $message = _x("The specified Credit Plan doesn't exists.", 'credit plans ajax', 'AWPCP');
            $response = array('status' => 'error', 'message' => $message);
        } else if (isset($_POST['remove'])) {
            if (AWPCP_CreditPlan::delete($id, $errors)) {
                $response = array('status' => 'success');
            } else {
                $response = array('status' => 'error', 'message' => join('<br/>', $errors));
            }
        } else {
            $columns = 5;
            ob_start();
                include(AWPCP_DIR . '/admin/templates/delete_form.tpl.php');
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
        $action = str_replace('awpcp-credit-plans-', '', awpcp_post_param('action'));
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
