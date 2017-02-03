<?php

function awpcp_categories_selector_component() {
    return new AWPCP_Categories_Selector_Component(
        awpcp_query(),
        awpcp_request()
    );
}

class AWPCP_Categories_Selector_Component {

    private $query;
    private $request;

    public function __construct( $query, $request ) {
        $this->query = $query;
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

        $category_dropdown_params = wp_parse_args( $params, array(
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
