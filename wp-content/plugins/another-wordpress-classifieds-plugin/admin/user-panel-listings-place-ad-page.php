<?php

require_once(AWPCP_DIR . '/admin/admin-panel-listings-place-ad-page.php');


class AWPCP_UserListingsPlaceAd extends AWPCP_AdminListingsPlaceAd {

    public function __construct($page=false, $title=false) {
        $page = $page ? $page : 'awpcp-admin-listings-place-ad';

        if ( empty( $title ) ) {
            $title = __( '<blog-name> User Ad Management Panel - Listings - Place Ad', 'another-wordpress-classifieds-plugin' );
            $title = str_replace( '<blog-name>', get_bloginfo( 'name' ), $title );
        }

        parent::__construct($page, $title);
    }

    public function show_sidebar() {
        return false;
    }
}
