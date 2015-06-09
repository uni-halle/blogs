<?php

require_once( AWPCP_DIR . '/frontend/page-browse-ads.php' );

class AWPCP_BrowseCategoriesPage extends AWPCP_BrowseAdsPage {

    public function __construct($page='awpcp-browse-categories', $title=null) {
        $title = is_null($title) ? __( 'Browse Categories', 'AWPCP' ) : $title;
        parent::__construct( $page, $title );
    }

    public function get_current_action($default='browsecat') {
        return awpcp_request_param('a', $default);
    }

    public function url($params=array()) {
        $url = awpcp_get_page_url('browse-categories-page-name');
        return add_query_arg( urlencode_deep( $params ), $url );
    }
}
