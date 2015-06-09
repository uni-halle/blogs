<?php

require_once(AWPCP_DIR . '/frontend/page-place-ad.php');


class AWPCP_AdminListingsPlaceAd extends AWPCP_Place_Ad_Page {

    protected $template = 'admin/templates/admin-page.tpl.php';

    public $menu;

    public function __construct($page=false, $title=false) {
        parent::__construct();

        $default_title = awpcp_admin_page_title( __( 'Place Ad', 'AWPCP' ), __( 'Manage Listings', 'AWPCP' ) );

        $this->page = $page ? $page : 'awpcp-admin-listings-place-ad';
        $this->title = $title ? $title : $default_title;
    }

    public function show_sidebar() {
        return awpcp_current_user_is_admin();;
    }
}
