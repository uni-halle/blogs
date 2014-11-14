<?php

function awpcp_categories_list_renderer() {
    return new AWPCP_CategoriesRenderer( awpcp_categories_collection(), new AWPCP_CategoriesListWalker() );
}

function awpcp_categories_checkbox_list_renderer() {
    return new AWPCP_CategoriesRenderer( awpcp_categories_collection(), new AWPCP_CategoriesCheckboxListWalker() );
}

class AWPCP_CategoriesRenderer {

    private $categories;
    private $walker;

    public function __construct( $categories, $walker ) {
        $this->categories = $categories;
        $this->walker = $walker;
    }

    public function render( $params = array() ) {
        awpcp_enqueue_main_script();

        $params = $this->merge_params( $params );
        $transient_key = $this->generate_transient_key( $params );

        try {
            return $this->render_from_cache( $transient_key );
        } catch ( AWPCP_Exception $e ) {
            return $this->render_categories_and_update_cache( $params, $transient_key );
        }
    }

    private function merge_params( $params ) {
        return wp_parse_args( $params, array(
            'parent_category_id' => null,
            'show_empty_categories' => true,
            'show_children_categories' => true,
            'show_listings_count' => true,
        ) );
    }

    private function generate_transient_key( $params ) {
        $params = array_merge( $params, array( 'walker' => get_class( $this->walker ) ) );
        $transient_key_params = apply_filters( 'awpcp-categories-list-transient-key-params', $params );
        $transient_key = 'awpcp-categories-list-cache-' . hash( 'crc32b', maybe_serialize( $transient_key_params ) );

        return $transient_key;
    }

    private function render_from_cache( $transient_key ) {
        $transient_keys = get_option( 'awpcp-categories-list-cache-keys', array() );

        if ( in_array( $transient_key, $transient_keys, true ) ) {
            $output = get_transient( $transient_key );

            if ( false !== $output )
                return $output;
        }


        throw new AWPCP_Exception( 'No cache entry was found.' );
    }

    private function render_categories_and_update_cache( $params, $transient_key ) {
        $categories = $this->get_categories( $params );
        $max_depth = $params['show_children_categories'] ? 0 : 1;

        if ( $this->walker->configure( $params ) ) {
            $output = $this->walker->walk( $categories, $max_depth );
            $this->update_cache( $transient_key, $output );
        } else {
            $output = '';
        }

        return $output;
    }

    private function get_categories( $params ) {
        $selected_categories = array();

        if ( is_null( $params['parent_category_id'] ) && $params['show_children_categories'] ) {
            $categories_found = $this->categories->get_all();
        } else if ( is_null( $params['parent_category_id'] ) ) {
            $categories_found = $this->categories->find_by_parent_id( 0 );
        } else {
            $categories_found = $this->categories->find_by_parent_id( $params['parent_category_id'] );
        }

        foreach ( $categories_found as $category ) {
            $category->listings_count = total_ads_in_cat( $category->id );
            if ( $params['show_empty_categories'] || $category->listings_count > 0 ) {
                $selected_categories[] = $category;
            }
        }

        return $selected_categories;
    }

    private function update_cache( $transient_key, $output ) {
        if ( set_transient( $transient_key, $output, YEAR_IN_SECONDS ) ) {
            $transient_keys = get_option( 'awpcp-categories-list-cache-keys' );
            if ( $transient_keys === false ) {
                add_option( 'awpcp-categories-list-cache-keys', array( $transient_key ), '', 'no' );
            } else {
                array_push( $transient_keys, $transient_key );
                update_option( 'awpcp-categories-list-cache-keys', $transient_keys );
            }
        }
    }
}
