<?php

require_once(AWPCP_DIR . '/admin/admin-panel-listings-place-ad-page.php');


class AWPCP_UserListingsPlaceAd extends AWPCP_AdminListingsPlaceAd {

    public function __construct($page=false, $title=false) {
        $page = $page ? $page : 'awpcp-admin-listings-place-ad';
        $title = $title ? $title : __('AWPCP Ad Management Panel - Listings - Place Ad', 'AWPCP');
        parent::__construct($page, $title);
    }

    public function show_sidebar() {
        return false;
    }
}
