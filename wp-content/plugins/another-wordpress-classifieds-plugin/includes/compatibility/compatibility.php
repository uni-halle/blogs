<?php

class AWPCP_Compatibility {

    public function load_plugin_integrations() {
        require_once(AWPCP_DIR . '/includes/compatibility/cryptx.php');

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

        } else if ( is_admin() ) {

        } else {
            $this->load_plugin_integration_used_in_frontend_screens();
        }

        $this->load_content_aware_sidebars_integration();
    }

    private function load_plugin_integration_used_in_frontend_screens() {
        add_filter( 'awpcp-should-generate-opengraph-tags', array( new AWPCP_FacebookPluginIntegration(), 'should_generate_opengraph_tags' ), 10, 2 );

        $all_in_one_seo_pack_plugin_integration = new AWPCP_AllInOneSEOPackPluginIntegration();
        add_filter( 'awpcp-should-generate-opengraph-tags', array( $all_in_one_seo_pack_plugin_integration, 'should_generate_opengraph_tags' ), 10, 2 );
        add_filter( 'awpcp-should-generate-rel-canonical', array( $all_in_one_seo_pack_plugin_integration, 'should_generate_rel_canonical' ), 10, 2 );

        $yoast_wordpress_seo_plugin_integration = new AWPCP_YoastWordPressSEOPluginIntegration();
        add_filter( 'awpcp-should-generate-opengraph-tags', array( $yoast_wordpress_seo_plugin_integration, 'should_generate_opengraph_tags' ), 10, 2 );
        add_filter( 'awpcp-should-generate-rel-canonical', array( $yoast_wordpress_seo_plugin_integration, 'should_generate_rel_canonical' ), 10, 2 );
        add_filter( 'awpcp-should-generate-title', array( $yoast_wordpress_seo_plugin_integration, 'should_generate_title' ), 10, 2 );
    }

    private function load_content_aware_sidebars_integration() {
        if ( class_exists( 'ContentAwareSidebars' ) && class_exists( 'CASModule' ) && class_exists( 'CAS_Walker_Checklist' ) ) {
            require_once( AWPCP_DIR . '/includes/compatibility/class-content-aware-sidebars-listings-categories-module.php' );
            require_once( AWPCP_DIR . '/includes/compatibility/class-content-aware-sidebars-categories-walker.php' );
            add_filter( 'cas-module-pre-deploy', 'awpcp_register_content_aware_sidebars_listings_categories_module' );
        }
    }
}
