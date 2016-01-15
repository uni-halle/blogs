<?php

function AWPCP_Categories_Renderer_Factory() {
    return new AWPCP_Categories_Renderer_Factory( awpcp_categories_collection() );
}

class AWPCP_Categories_Renderer_Factory {

    private $categories;

    public function __construct( $categories ) {
        $this->categories = $categories;
    }

    public function create_list_renderer() {
        return new AWPCP_CategoriesRenderer( $this->categories, new AWPCP_CategoriesListWalker() );
    }
}
