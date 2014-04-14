<?php

require_once(AWPCP_DIR . '/frontend/page-edit-ad.php');


class AWPCP_AdminListingsEditAd extends AWPCP_EditAdPage {

    protected $template = 'admin/templates/admin-page.tpl.php';

    public $menu;

    public function __construct($page=false, $title=false) {
        parent::__construct();

        $this->page = $page ? $page : 'awpcp-admin-listings-edit-ad';
        $this->title = $title ? $title : __('AWPCP Classifieds Management System - Manage Ad Listings - Edit Ad', 'AWPCP');
    }

    public function show_sidebar() {
        return awpcp_current_user_is_admin();;
    }
}
