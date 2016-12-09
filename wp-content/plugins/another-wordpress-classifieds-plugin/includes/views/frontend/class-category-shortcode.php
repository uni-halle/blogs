<?php

function awpcp_category_shortcode() {
    return new AWPCP_CategoryShortcode( awpcp_categories_renderer_factory(), awpcp_request() );
}

class AWPCP_CategoryShortcode {

    private $categories_renderer_factory;
    private $request;

    public function __construct( $categories_renderer_factory, $request ) {
        $this->categories_renderer_factory = $categories_renderer_factory;
        $this->request = $request;
    }

    public function render( $attrs ) {
        $attrs = $this->get_shortcode_attrs( $attrs );

        $output = apply_filters( 'awpcp-category-shortcode-content-replacement', null, $attrs );

        if ( is_null( $output ) ) {
            return $this->render_shortcode_content( $attrs );
        } else {
            return $output;
        }
    }

    private function get_shortcode_attrs( $attrs ) {
        if ( ! isset( $attrs['show_categories_list'] ) && isset( $attrs['children'] ) ) {
            $attrs['show_categories_list'] = $attrs['children'];
        }

        $attrs = shortcode_atts( array(
            'id' => 0,
            'children' => true,
            'items_per_page' => get_awpcp_option( 'adresultsperpage', 10 ),
            'show_categories_list' => true,
        ), $attrs );

        $attrs['children'] = awpcp_parse_bool( $attrs['children'] );
        $attrs['show_categories_list'] = awpcp_parse_bool( $attrs['show_categories_list'] );

        if ( strpos( $attrs['id'], ',' ) ){
            $attrs['id'] = explode( ',', $attrs['id'] );
        } else {
            $attrs['id'] = array( $attrs['id'] );
        }

        return $attrs;
    }

    private function render_shortcode_content( $attrs ) {
        if ( $attrs['show_categories_list'] ) {
            $options = array(
                'before_pagination' => array(
                    10 => array(
                        'categories-list' => $this->render_categories_list( $attrs['id'] ),
                    ),
                ),
            );
        } else {
            $options = array();
        }

        $query = array(
            'context' => 'public-listings',
            'category_id' => $attrs['id'],
            'include_listings_in_children_categories' => $attrs['children'],
            'limit' => absint( $this->request->param( 'results', $attrs['items_per_page'] ) ),
            'offset' => absint( $this->request->param( 'offset', 0 ) ),
            'orderby' => get_awpcp_option( 'groupbrowseadsby' ),
        );


        // required so awpcp_display_ads shows the name of the current category
        if ( count( $attrs['id'] ) === 1 ) {
            $_REQUEST['category_id'] = $attrs['id'][0];
        }

        return awpcp_display_listings_in_page( $query, 'category-shortcode', $options );
    }

    private function render_categories_list( $categories_ids ) {
        $categories_list_params = array( 'show_listings_count' => true, );

        if ( count( $categories_ids ) == 1 ) {
            $categories_list_params['parent_category_id'] = $categories_ids[0];
        } else {
            $categories_list_params['category_id'] = $categories_ids;
        }

        $categories_renderer = $this->categories_renderer_factory->create_list_renderer();

        return $categories_renderer->render( $categories_list_params );
    }
}
