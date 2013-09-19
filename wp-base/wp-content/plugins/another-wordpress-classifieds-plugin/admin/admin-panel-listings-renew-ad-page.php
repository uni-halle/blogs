<?php

require_once(AWPCP_DIR . '/frontend/page-renew-ad.php');


class AWPCP_AdminListingsRenewAd extends AWPCP_RenewAdPage {

    protected $template = 'admin/templates/admin-page.tpl.php';
    protected $sidebar = true;

    public $menu;

    public function __construct($page=false, $title=false) {
        parent::__construct();

        $this->page = $page ? $page : 'awpcp-admin-listings-renew-ad';
        $this->title = $title ? $title : __('AWPCP Classifieds Management System - Manage Ad Listings - Renew Ad', 'AWPCP');

        $this->sidebar = awpcp_current_user_is_admin();
    }

    protected function get_panel_url() {
        return awpcp_get_admin_listings_url();
    }
}
