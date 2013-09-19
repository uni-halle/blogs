<?php

require_once(AWPCP_DIR . '/admin/admin-panel-listings-edit-ad-page.php');


class AWPCP_UserListingsEditAd extends AWPCP_AdminListingsEditAd {

    public function __construct($page=false, $title=false) {
        $page = $page ? $page : 'awpcp-admin-listings-edit-ad';
        $title = $title ? $title : __('AWPCP Ad Management Panel - Listings - Edit Ad', 'AWPCP');
        parent::__construct($page, $title);

        $this->sidebar = false;
    }
}
