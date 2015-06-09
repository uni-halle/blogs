<?php

require_once(AWPCP_DIR . '/includes/helpers/admin-page.php');


class AWPCP_AdminHome extends AWPCP_AdminPage {

    public function AWPCP_AdminHome() {
        parent::__construct( 'awpcp.php', awpcp_admin_page_title(), __( 'Classifieds', 'AWPCP' ) );
    }

    public function scripts() {
    }

    public function dispatch() {
        global $awpcp_db_version;
        global $hasextrafieldsmodule, $extrafieldsversioncompatibility;
        global $message;

        $template = AWPCP_DIR . '/admin/templates/admin-panel-home.tpl.php';
        $params = compact('awpcp_db_version',
                          'hasextrafieldsmodule',
                          'extrafieldsversioncompatibility',
                          'message');

        echo $this->render($template, $params);
    }
}
