<?php

require_once(AWPCP_DIR . '/admin/admin-panel-listings-edit-ad-page.php');


class AWPCP_UserListingsEditAd extends AWPCP_AdminListingsEditAd {

    public function __construct($page=false, $title=false) {
        $page = $page ? $page : 'awpcp-admin-listings-edit-ad';

        if ( empty( $title ) ) {
            $title = __( '<blog-name> User Ad Management Panel - Listings - Edit Ad', 'another-wordpress-classifieds-plugin' );
            $title = str_replace( '<blog-name>', get_bloginfo( 'name' ), $title );
        }

        parent::__construct($page, $title);
    }

    public function show_sidebar() {
        return false;
    }
}
