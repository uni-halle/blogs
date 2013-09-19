<?php

require_once(AWPCP_DIR . '/admin/admin-panel-listings-renew-ad-page.php');


class AWPCP_UserListingsRenewAd extends AWPCP_AdminListingsRenewAd {

    public function __construct($page=false, $title=false) {
        $page = $page ? $page : 'awpcp-admin-listings-renew-ad';
        $title = $title ? $title : __('AWPCP Ad Management Panel - Listings - Renew Ad', 'AWPCP');
        parent::__construct($page, $title);

        $this->sidebar = false;
    }

    protected function get_panel_url() {
        return awpcp_get_user_panel_url();
    }
}
