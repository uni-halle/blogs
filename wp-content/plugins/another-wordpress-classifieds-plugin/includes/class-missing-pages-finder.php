<?php

function awpcp_missing_pages_finder() {
    return new AWPCP_Missing_Pages_Finder( $GLOBALS['wpdb'] );
}

class AWPCP_Missing_Pages_Finder {

    private $db;

    public function __construct( $db ) {
        $this->db = $db;
    }

    public function find_missing_pages() {
        $shortcodes = awpcp_pages();

        $registered_pages = array_keys( $shortcodes );
        $referenced_pages = $this->db->get_col( 'SELECT page FROM ' . AWPCP_TABLE_PAGES );

        // pages that are registered in the code but no referenced in the DB
        $pages_not_referenced = array_diff( $registered_pages, $referenced_pages );
        // pages that are referenced but no longer registered in the code
        $pages_not_registered = array_diff( $referenced_pages, $registered_pages );
        $excluded_pages = array_merge( array( 'view-categories-page-name'), $pages_not_registered );

        $query = 'SELECT pages.page, pages.id, posts.ID post, posts.post_status status ';
        $query.= 'FROM ' . AWPCP_TABLE_PAGES . ' AS pages ';
        $query.= 'LEFT JOIN ' . $this->db->posts . ' AS posts ON (posts.ID = pages.id) ';
        $query.= "WHERE posts.ID IS NULL OR posts.post_status != 'publish' ";
        $query.= "AND pages.page NOT IN ('" . join( "','", $excluded_pages ) . "')";

        $missing_pages = array();

        foreach ( $this->db->get_results( $query ) as $page ) {
            if ( is_null( $page->status ) ) {
                $missing_pages['not-found'][] = $page;
            } else {
                $missing_pages['not-published'][] = $page;
            }
        }

        // if a page is registered in the code but there is no reference of it
        // in the database, include a dummy object to represent it.
        foreach ( $pages_not_referenced as $page ) {
            $item = new stdClass();
            $item->page = $page;
            $item->id = null;
            $item->post = null;
            $item->status = null;

            $missing_pages['not-referenced'][] = $item;
        }

        return $missing_pages;
    }
}
