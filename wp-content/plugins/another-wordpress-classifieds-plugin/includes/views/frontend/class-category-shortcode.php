<?php

function awpcp_category_shortcode() {
    return new AWPCP_CategoryShortcode( $GLOBALS['wpdb'], awpcp_request() );
}

class AWPCP_CategoryShortcode {

    private $db;
    private $request;

    public function __construct( $db, $request ) {
        $this->db = $db;
        $this->request = $request;
    }

    public function render( $attrs ) {
        $default = array( 'id' => 0, 'children' => true, 'items_per_page' => 10 );
        $attrs = shortcode_atts( $default, $attrs );

        $output = apply_filters( 'awpcp-category-shortcode-content-replacement', null, $attrs );

        if ( is_null( $output ) ) {
            return $this->render_shortcode_content( $attrs );
        } else {
            return $output;
        }
    }

    private function render_shortcode_content( $attrs ) {
        extract( $attrs );

        // request param overrides shortcode param
        $items_per_page = $this->request->param( 'results', $items_per_page );
        // set the number of items per page, to make sure both the shortcode handler
        // and the awpcp_display_ads function are using the same value
        $_REQUEST['results'] = $_GET['results'] = $items_per_page;

        $category = $id > 0 ? AWPCP_Category::find_by_id( $id ) : null;
        $children = awpcp_parse_bool( $children );

        if ( is_null( $category ) ) {
            return __('Category ID must be valid for Ads to display.', 'category shortcode', 'AWPCP');
        }

        if ( $children ) {
            $before = awpcp_categories_list_renderer()->render( array( 'parent_category_id' => $category->id, 'show_listings_count' => true ) );
        } else {
            $before = '';
        }

        if ( $children ) {
            $where = '( ad_category_id=%1$d OR ad_category_parent_id = %1$d ) AND disabled = 0';
        } else {
            $where = 'ad_category_id=%1$d AND disabled = 0';
        }
        $where = $this->db->prepare( $where, $category->id );

        $order = get_awpcp_option( 'groupbrowseadsby' );

        // required so awpcp_display_ads shows the name of the current category
        $_REQUEST['category_id'] = $category->id;

        $base_url = sprintf( 'custom:%s', awpcp_current_url() );

        return awpcp_display_ads( $where, '', '', $order, $base_url, $before );
    }
}
