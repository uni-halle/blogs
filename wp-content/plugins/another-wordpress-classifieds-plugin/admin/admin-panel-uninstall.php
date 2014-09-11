<?php

require_once(AWPCP_DIR . '/includes/helpers/admin-page.php');


class AWPCP_Admin_Uninstall extends AWPCP_AdminPage {

    public function AWPCP_Admin_Uninstall() {
        parent::__construct(
            'awpcp-admin-uninstall',
            __('Uninstall AWPCP Classifieds Management System', 'AWPCP'),
            __('Uninstall', 'AWPCP'));
    }

    public function scripts() {
    }

    public function dispatch() {
        global $awpcp, $message;

        $action = awpcp_request_param('action', 'confirm');
        $url = awpcp_current_url();
        $dirname = AWPCPUPLOADDIR;

        if (strcmp($action, 'uninstall') == 0) {
            $awpcp->installer->uninstall();
        }

        $template = AWPCP_DIR . '/admin/templates/admin-panel-uninstall.tpl.php';
        $params = compact('action', 'url', 'dirname');

        echo $this->render($template, $params);
    }
}
