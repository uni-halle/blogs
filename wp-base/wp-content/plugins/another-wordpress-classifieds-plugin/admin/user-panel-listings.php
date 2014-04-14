<?php

require_once(AWPCP_DIR . '/admin/admin-panel-listings.php');
require_once(AWPCP_DIR . '/admin/user-panel-listings-place-ad-page.php');
require_once(AWPCP_DIR . '/admin/user-panel-listings-edit-ad-page.php');
require_once(AWPCP_DIR . '/admin/user-panel-listings-renew-ad-page.php');


class AWPCP_UserListings extends AWPCP_Admin_Listings {

    public function __construct($page=false, $title=false) {
        $page = $page ? $page : 'awpcp-admin-listings';
        $title = $title ? $title : __('AWPCP Ad Management Panel - Listings', 'AWPCP');
        parent::__construct($page, $title);
    }

    public function show_sidebar() {
        return false;
    }

    public function place_ad() {
        $page = new AWPCP_UserListingsPlaceAd();
        return $page->dispatch();
    }

    public function edit_ad() {
        $page = new AWPCP_UserListingsEditAd();
        return $page->dispatch('details');
    }

    public function renew_ad() {
        $page = new AWPCP_UserListingsRenewAd();
        return $page->dispatch('renew');
    }
}
