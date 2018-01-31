<?php

require_once( AWPCP_DIR . '/includes/helpers/page.php' );

class AWPCP_BrowseAdsPage extends AWPCP_Page {

    private $request;

    public function __construct($page='awpcp-browse-ads', $title=null) {
        $title = is_null($title) ? __( 'Browse Ads', 'another-wordpress-classifieds-plugin' ) : $title;
        parent::__construct( $page, $title );

        $this->request = awpcp_request();
    }

    public function url($params=array()) {
        $url = awpcp_get_page_url('browse-ads-page-name');
        return add_query_arg( urlencode_deep( $params ), $url );
    }

    public function dispatch() {
        return $this->_dispatch();
    }

    protected function _dispatch() {
        awpcp_enqueue_main_script();

        $category_id = absint( $this->request->param( 'category_id', get_query_var( 'cid' ) ) );

        $output = apply_filters( 'awpcp-browse-listings-content-replacement', null, $category_id );

        if ( is_null( $output ) && $category_id ) {
            return $this->render_listings_from_category( $category_id );
        } elseif ( is_null( $output ) ) {
            return $this->render_all_listings( $category_id );
        } else {
            return $output;
        }
    }

    private function render_listings_from_category( $category_id ) {
        $query = array(
            'context' => 'public-listings',
            'category_id' => $category_id,
            'limit' => absint( awpcp_request_param( 'results', get_awpcp_option( 'adresultsperpage', 10 ) ) ),
            'offset' => absint( awpcp_request_param( 'offset', 0 ) ),
            'orderby' => get_awpcp_option( 'groupbrowseadsby' ),
        );

        if ( $category_id == -1 ) {
            $message = __( "No specific category was selected for browsing so you are viewing listings from all categories." , 'another-wordpress-classifieds-plugin' );

            $output = awpcp_print_message( $message );
            $output.= $this->render_listings_in_page( $query );
        } else {
            $output = $this->render_listings_in_page( $query );
        }

        return $output;
    }

    protected function render_listings_in_page( $query ) {
        $options = array( 'page' => $this->page );

        return awpcp_display_listings_in_page( $query, 'browse-listings', $options );
    }

    protected function render_all_listings() {
        $query = array(
            'context' => 'public-listings',
            'limit' => absint( awpcp_request_param( 'results', get_awpcp_option( 'adresultsperpage', 10 ) ) ),
            'offset' => absint( awpcp_request_param( 'offset', 0 ) ),
            'orderby' => get_awpcp_option( 'groupbrowseadsby' ),
        );

        return $this->render_listings_in_page( $query );
    }
}
