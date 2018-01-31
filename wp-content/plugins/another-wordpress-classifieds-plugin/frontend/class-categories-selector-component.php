<?php

function awpcp_categories_selector_component() {
    return new AWPCP_Categories_Selector_Component(
        awpcp_query(),
        awpcp()->settings,
        awpcp_request()
    );
}

class AWPCP_Categories_Selector_Component {

    private $query;
    private $settings;
    private $request;

    public function __construct( $query, $settings, $request ) {
        $this->query = $query;
        $this->settings = $settings;
        $this->request = $request = $request;
    }

    public function render( $params = array() ) {
        if ( $this->query->is_browse_listings_page() || $this->query->is_browse_categories_page() ) {
            $action_url = awpcp_current_url();
        } else {
            $action_url = awpcp_get_browse_categories_page_url();
        }

        $category_id = (int) $this->request->param( 'category_id', -1 );
        $category_id = $category_id === -1 ? (int) $this->request->get_query_var( 'cid' ) : $category_id;

        $use_multiple_dropdowns = $this->settings->get_option( 'use-multiple-category-dropdowns', false );

        if ( $use_multiple_dropdowns && $category_id ) {
            $label = _x( 'Category: <category-name>', 'multiple dropdowns category selector', 'another-wordpress-classifieds-plugin' );
            $label = str_replace( '<category-name>', get_adcatname( $category_id ), $label );
        } else if ( $use_multiple_dropdowns ) {
            $label = false;
        } else {
            $label = _x( 'Category:', 'single dropdown category selector', 'another-wordpress-classifieds-plugin' );
        }

        if ( $use_multiple_dropdowns ) {
            $dropdown_container_class = 'awpcp-multiple-dropdown-category-selector-container';
        } else {
            $dropdown_container_class = 'awpcp-single-dropdown-category-selector-container';
        }

        $category_dropdown_params = wp_parse_args( $params, array(
            'label' => $label,
            'context' => 'search',
            'name' => 'category_id',
            'selected' => $category_id,
        ) );

        $hidden = array_filter( array(
            'results' => $this->request->param( 'results' ),
            'offset' => 0,
        ), 'strlen' );

        ob_start();
        include( AWPCP_DIR . '/templates/frontend/category-selector.tpl.php' );
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}
