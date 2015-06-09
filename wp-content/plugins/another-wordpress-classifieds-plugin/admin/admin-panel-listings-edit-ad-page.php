<?php

require_once(AWPCP_DIR . '/frontend/page-edit-ad.php');


class AWPCP_AdminListingsEditAd extends AWPCP_EditAdPage {

    protected $template = 'admin/templates/admin-page.tpl.php';

    public $menu;

    public function __construct($page=false, $title=false) {
        parent::__construct();

        $default_title = awpcp_admin_page_title( __( 'Edit Ad', 'AWPCP' ), __( 'Manage Listings', 'AWPCP' ) );

        $this->title = $title ? $title : $default_title;
        $this->page = $page ? $page : 'awpcp-admin-listings-edit-ad';
    }

    public function show_sidebar() {
        return awpcp_current_user_is_admin();;
    }
}
