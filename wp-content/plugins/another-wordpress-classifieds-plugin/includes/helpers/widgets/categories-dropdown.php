<?php

/**
 * @since 3.3
 */
function awpcp_categories_dropdown() {
    return new AWPCP_CategoriesDropdown();
}

class AWPCP_CategoriesDropdown {

    public function render($params) {
        extract( $params = wp_parse_args( $params, array(
            'context' => 'default',
            'name' => 'category',
            'label' => __( 'Ad Category', 'AWPCP' ),
            'required' => true,
            'selected' => null,
            'placeholders' => array(),
        ) ) );

        if ( $context == 'search' ) {
            $placeholders = array_merge( array(
                'default-option-first-level' => __( 'All Categories', 'AWPCP' ),
                'default-option-second-level' => __( 'All Sub-categories', 'AWPCP' ),
            ), $placeholders );
        } else {
            if ( get_awpcp_option( 'noadsinparentcat' ) ) {
                $second_level_option_placeholder = __( 'Select a Sub-category', 'AWPCP' );
            } else {
                $second_level_option_placeholder = __( 'Select a Sub-category (optional)', 'AWPCP' );
            }

            $placeholders = array_merge( array(
                'default-option-first-level' => __( 'Select a Category', 'AWPCP' ),
                'default-option-second-level' => $second_level_option_placeholder
            ), $placeholders );
        }

        $categories = awpcp_categories_collection()->get_all();
        $categories_hierarchy = awpcp_build_categories_hierarchy( $categories );
        $chain = $this->get_category_parents( $selected, $categories );

        $use_multiple_dropdowns = get_awpcp_option( 'use-multiple-category-dropdowns' );

        // export categories list to JavaScript, but don't replace an existing categories list
        awpcp()->js->set( 'categories', $categories_hierarchy, false );

        ob_start();
        include( AWPCP_DIR . '/frontend/templates/html-widget-category-dropdown.tpl.php' );
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    private function get_all_categories() {
        return AWPCP_Category::query( array(
            'orderby' => 'category_parent_id ASC, category_order ASC, category_name',
            'order' => 'ASC'
        ) );
    }

    private function get_category_parents( $category_id, &$categories ) {
        if ( empty( $category_id ) ) {
            return array();
        }

        $categories_parents = array();

        foreach ( $categories as $item ) {
            $categories_parents[ $item->id ] = $item->parent;
        }

        $category_ancestors = array();
        $parent_id = $category_id;

        do {
            $category_ancestors[] = $parent_id;
            $parent_id = $categories_parents[ $parent_id ];
        } while ( $parent_id != 0 );

        return array_reverse( $category_ancestors );
    }
}

function awpcp_render_category_selector( $params = array() ) {
    $browse_categories_page_url = get_permalink( awpcp_get_page_id_by_ref( 'browse-categories-page-name' ) );

    $category_id = (int) awpcp_request_param( 'category_id', -1 );
    $category_id = $category_id === -1 ? (int) get_query_var( 'cid' ) : $category_id;

    $category_dropdown_params = wp_parse_args( $params, array(
        'context' => 'search',
        'name' => 'category_id',
        'selected' => $category_id,
    ) );

    $hidden = array(
        'a' => 'browsecat',
        'results' => awpcp_request_param( 'results' ),
        'offset' => awpcp_request_param( 'offset' ),
    );

    ob_start();
    include( AWPCP_DIR . '/templates/frontend/category-selector.tpl.php' );
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
