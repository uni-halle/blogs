<?php

require_once(AWPCP_DIR . '/frontend/page-renew-ad.php');


class AWPCP_AdminListingsRenewAd extends AWPCP_RenewAdPage {

    protected $template = 'admin/templates/admin-page.tpl.php';

    public $menu;

    public function __construct($page=false, $title=false) {
        parent::__construct();

        $default_title = awpcp_admin_page_title( __( 'Renew Ad', 'AWPCP' ), __( 'Manage Listings', 'AWPCP' ) );

        $this->page = $page ? $page : 'awpcp-admin-listings-renew-ad';
        $this->title = $title ? $title : $default_title;
    }

    public function show_sidebar() {
        return awpcp_current_user_is_admin();;
    }

    protected function get_panel_url() {
        return awpcp_get_admin_listings_url();
    }
}
