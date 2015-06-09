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

        $category = $id > 0 ? AWPCP_Category::find_by_id( $id ) : null;
        $children = awpcp_parse_bool( $children );

        if ( is_null( $category ) ) {
            return __('Category ID must be valid for Ads to display.', 'category shortcode', 'AWPCP');
        }

        if ( $children ) {
            $categories_list = awpcp_categories_list_renderer()->render( array(
                'parent_category_id' => $category->id,
                'show_listings_count' => true,
            ) );

            $options = array(
                'before_pagination' => array(
                    10 => array(
                        'categories-list' => $categories_list,
                    ),
                ),
            );
        } else {
            $options = array();
        }

        $query = array(
            'category_id' => $category->id,
            'include_listings_in_children_categories' => $children,
            'limit' => absint( $this->request->param( 'results', $items_per_page ) ),
            'offset' => absint( $this->request->param( 'offset', 0 ) ),
            'orderby' => get_awpcp_option( 'groupbrowseadsby' ),
        );

        // required so awpcp_display_ads shows the name of the current category
        $_REQUEST['category_id'] = $category->id;

        return awpcp_display_listings_in_page( $query, 'category-shortcode', $options );
    }
}
