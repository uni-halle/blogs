<?php

define( 'AWPCP_CAS_LISTINGS_CATEGORIES_MODULE', 'awpcp_listings_categories' );

if ( class_exists( 'CASModule' ) ) {

function awpcp_register_content_aware_sidebars_listings_categories_module( $modules ) {
    $modules[ AWPCP_CAS_LISTINGS_CATEGORIES_MODULE ] = 'AWPCP_ContentAwareSidebarsListingsCategoriesModule';
    return $modules;
}

class AWPCP_ContentAwareSidebarsListingsCategoriesModule extends CASModule {

    private $categories;
    private $listings;
    private $walker;
    private $request;

    public function __construct( $categories = null, $listings = null, $walker = null, $request = null ) {
        parent::__construct( AWPCP_CAS_LISTINGS_CATEGORIES_MODULE, __( 'Categories (AWPCP)', 'AWPCP' ) );

        if ( is_null( $categories ) ) {
            $this->categories = awpcp_categories_collection();
        } else {
            $this->categories = $categories;
        }

        if ( is_null( $listings ) ) {
            $this->listings = awpcp_listings_collection();
        } else {
            $this->listings = $listings;
        }

        if ( is_null( $walker ) ) {
            $this->walker = awpcp_content_aware_sidebars_categories_walker( $this->id );
        } else {
            $this->walker = $walker;
        }

        if ( is_null( $request ) ) {
            $this->request = awpcp_request();
        } else {
            $this->request = $request;
        }
    }

    protected function _get_content( $args = array() ) {
        $categories = $this->get_categories( $args );

        $control_items = array();
        foreach ( $categories as $category ) {
            $control_items[ $category->id ] = $category->name;
        }

        return $control_items;
    }

    private function get_categories( $args = array() ) {
        $args = wp_parse_args( $args, array( 'include' => '' ) );

        if ( empty( $args['include'] ) ) {
            $categories = $this->categories->get_all();
        } else {
            $categories = $this->categories->find( array( 'id' => $args['include'] ) );
        }

        return $categories;
    }

    public function in_context() {
        $category_id = $this->request->get_category_id();
        $ad_id = $this->request->get_ad_id();

        return $category_id > 0 || $ad_id > 0;
    }

    public function get_context_data() {
        $category_id = $this->request->get_category_id();

        if ( $category_id > 0 ) {
            return array( $category_id );
        }

        $ad_id = $this->request->get_ad_id();

        if ( $ad_id > 0 ) {
            $ad = $this->listings->find_by_id( $ad_id );
            return is_null( $ad ) ? array() : array( $ad->ad_category_id );
        }

        return array();
    }

    public function meta_box_content() {
        $categories = $this->get_categories();

        if ( empty( $categories ) ) {
            return;
        }

        echo '<li class="control-section accordion-section">';
        echo '<h3 class="accordion-section-title title="' . $this->name . '" tabindex="0">' . $this->name . '</h3>';
        echo '<div class="accordion-section-content cas-rule-content" data-cas-module="' . $this->id . '" id="cas-' . $this->id . '">';

        $tabs = array(
            'all' => array(
                'title' => __( 'View All' ),
                'status' => true,
                'content' => $this->walker->walk( $categories, 0 ),
            ),
        );

        echo $this->create_tab_panels( $this->id, $tabs );

        echo '<p class="button-controls">';

        echo '<span class="add-to-group"><input data-cas-condition="' . $this->id . '" data-cas-module="' . $this->id . '" type="button" name="cas-condition-add" class="js-cas-condition-add button" value="' . __('Add to Group', ContentAwareSidebars::DOMAIN ) . '"></span>';

        echo '</p>';

        echo '</div>';
        echo '</li>';
    }
}

}
