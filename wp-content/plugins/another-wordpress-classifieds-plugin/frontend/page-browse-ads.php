<?php

require_once( AWPCP_DIR . '/includes/helpers/page.php' );

class AWPCP_BrowseAdsPage extends AWPCP_Page {

    public function __construct($page='awpcp-browse-ads', $title=null) {
        $title = is_null($title) ? __( 'Browse Ads', 'AWPCP' ) : $title;
        parent::__construct( $page, $title );
    }

    public function get_current_action($default='browseads') {
        return awpcp_request_param('a', $default);
    }

    public function url($params=array()) {
        $url = awpcp_get_page_url('browse-ads-page-name');
        return add_query_arg($params, $url);
    }

    public function dispatch() {
        return $this->_dispatch();
    }

    protected function _dispatch() {
        awpcp_enqueue_main_script();

        $action = $this->get_current_action();

        switch ($action) {
            case 'browsecat':
                return $this->browse_listings( 'render_listings_from_category' );
            case 'browseads':
            default:
                return $this->browse_listings( 'render_all_listings' );
        }
    }

    protected function browse_listings( $callback ) {
        $category_id = intval(awpcp_request_param('category_id', get_query_var('cid')));
        $output = apply_filters( 'awpcp-browse-listings-content-replacement', null, $category_id );

        if ( is_null( $output ) ) {
            return $this->$callback( $category_id );
        } else {
            return $output;
        }
    }

    private function render_listings_from_category( $category_id ) {
        global $wpdb;

        if ($category_id == -1 || empty($category_id)) {
            $conditions = array();
        } else {
            $sql = '( ad_category_id = %1$d OR ad_category_parent_id = %1$d )';
            $conditions[] = $wpdb->prepare( $sql,  $category_id );
            $conditions[] = 'disabled = 0';
        }

        $order = get_awpcp_option('groupbrowseadsby');

        if ( $category_id == -1 ) {
            $message = __( "No specific category was selected for browsing so you are viewing listings from all categories." , "AWPCP" );
            $output = awpcp_print_message( $message ) . awpcp_display_ads( join( ' AND ', $conditions ), '', '', $order, 'cat');
        } else {
            $output = awpcp_display_ads( join( ' AND ', $conditions ), '', '', $order, 'cat');
        }

        return $output;
    }

    protected function render_all_listings() {
        $order = get_awpcp_option( 'groupbrowseadsby' );
        return awpcp_display_ads( '', '', '', $order, 'ad');
    }
}
