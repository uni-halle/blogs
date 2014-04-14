<?php

require_once(AWPCP_DIR . '/frontend/page-place-ad.php');


class AWPCP_AdminListingsPlaceAd extends AWPCP_Place_Ad_Page {

    protected $template = 'admin/templates/admin-page.tpl.php';

    public $menu;

    public function __construct($page=false, $title=false) {
        parent::__construct();

        $this->page = $page ? $page : 'awpcp-admin-listings-place-ad';
        $this->title = $title ? $title : __('AWPCP Classifieds Management System - Manage Ad Listings - Place Ad', 'AWPCP');
    }

    public function show_sidebar() {
        return awpcp_current_user_is_admin();;
    }
}
