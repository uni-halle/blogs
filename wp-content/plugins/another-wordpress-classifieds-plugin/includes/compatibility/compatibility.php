<?php

class AWPCP_Compatibility {

    public function load_plugin_integrations() {
        require_once(AWPCP_DIR . '/includes/compatibility/cryptx.php');

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

        } else if ( is_admin() ) {
            $this->load_plugin_integration_used_in_admin_screens();
        } else {
            $this->load_plugin_integration_used_in_frontend_screens();
        }

        $this->load_content_aware_sidebars_integration();
        $this->load_woocommerce_integration();
    }

    private function load_plugin_integration_used_in_admin_screens() {
        $facebookall_plugin_integration = awpcp_facebook_all_plugin_integration();
        add_action( 'init', array( $facebookall_plugin_integration, 'maybe_remove_userlogin_handler' ), 5 );
    }

    private function load_plugin_integration_used_in_frontend_screens() {
        add_filter( 'awpcp-should-generate-opengraph-tags', array( new AWPCP_FacebookPluginIntegration(), 'should_generate_opengraph_tags' ), 10, 2 );

        $integration = new AWPCP_AllInOneSEOPackPluginIntegration();
        add_filter( 'awpcp-should-generate-basic-meta-tags', array( $integration, 'should_generate_basic_meta_tags' ), 10, 2 );
        add_filter( 'awpcp-should-generate-opengraph-tags', array( $integration, 'should_generate_opengraph_tags' ), 10, 2 );
        add_filter( 'awpcp-should-generate-rel-canonical', array( $integration, 'should_generate_rel_canonical' ), 10, 2 );

        $integration = awpcp_yoast_wordpress_seo_plugin_integration();
        add_filter( 'awpcp-should-generate-basic-meta-tags', array( $integration, 'should_generate_basic_meta_tags' ), 10, 2 );
        add_filter( 'awpcp-should-generate-opengraph-tags', array( $integration, 'should_generate_opengraph_tags' ), 10, 2 );
        add_filter( 'awpcp-should-generate-rel-canonical', array( $integration, 'should_generate_rel_canonical' ), 10, 2 );
        add_filter( 'awpcp-should-generate-title', array( $integration, 'should_generate_title' ), 10, 2 );

        $integration = awpcp_add_meta_tags_plugin_integration();
        add_filter( 'awpcp-should-generate-basic-meta-tags', array( $integration, 'should_generate_basic_meta_tags' ), 10, 2 );
        add_filter( 'awpcp-should-generate-opengraph-tags', array( $integration, 'should_generate_opengraph_tags' ), 10, 2 );

        $integration = awpcp_facebook_button_plugin_integration();
        $integration->setup();
    }

    private function load_content_aware_sidebars_integration() {
        if ( class_exists( 'ContentAwareSidebars' ) && class_exists( 'CASModule' ) && class_exists( 'CAS_Walker_Checklist' ) ) {
            require_once( AWPCP_DIR . '/includes/compatibility/class-content-aware-sidebars-listings-categories-module.php' );
            require_once( AWPCP_DIR . '/includes/compatibility/class-content-aware-sidebars-categories-walker.php' );
            add_filter( 'cas-module-pre-deploy', 'awpcp_register_content_aware_sidebars_listings_categories_module' );
        }
    }

    private function load_woocommerce_integration() {
        $woocommerce_integration = awpcp_woocommerce_plugin_integration();
        add_filter( 'woocommerce_prevent_admin_access', array( $woocommerce_integration, 'filter_prevent_admin_access' ) );

        if ( ! is_admin() ) {
            add_filter( 'woocommerce_unforce_ssl_checkout', array( $woocommerce_integration, 'filter_unforce_ssl_checkout' ) );
        }
    }

    public function load_plugin_integrations_on_init() {
        if ( !is_user_logged_in() ) {
            $this->load_plugin_integrations_for_anonymous_users();
        }
    }

    private function load_plugin_integrations_for_anonymous_users() {
        $integration = awpcp_wp_members_plugin_integration();
        add_filter( 'awpcp-login-form-implementation', array( $integration, 'get_login_form_implementation' ) );

        $integration = awpcp_profile_builder_plugin_integration();
        add_filter( 'awpcp-login-form-implementation', array( $integration, 'get_login_form_implementation' ) );
    }
}
