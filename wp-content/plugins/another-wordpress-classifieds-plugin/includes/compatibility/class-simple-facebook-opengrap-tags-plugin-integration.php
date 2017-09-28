<?php
/**
 * @package AWPCP\Compatibility\PluginIntegrations
 */

/**
 * Constructor function for AWPCP_Simple_Facebook_OpenGraph_Tags_Plugin_Integration class.
 */
function awpcp_simple_facebook_opengraph_tags_plugin_integration() {
    return new AWPCP_Simple_Facebook_OpenGraph_Tags_Plugin_Integration();
}

/**
 * Plugin integration for Simple Facebook Open Grpah Tags.
 */
class AWPCP_Simple_Facebook_OpenGraph_Tags_Plugin_Integration implements AWPCP_Plugin_Integration {

    /**
     * @var object Instance of Meta.
     */
    private $metadata;

    /**
     * Executed during init when this integration is active.
     */
    public function load() {
        $callback = array( $this, 'should_generate_opengraph_tags' );
        add_filter( 'awpcp-should-generate-opengraph-tags', $callback, 10, 2 );

    }

    /**
     * Disables AWPCP's code to generate OpenGraph meta tags and adds handlers
     * for Simple Facebook Open Grap Tags filters to insert the listing's
     * information.
     *
     * @param bool   $should    Whether or not AWPCP should generate OpenGraph
     *                          meta tags.
     * @param object $metadata  Instance of Meta.
     */
    public function should_generate_opengraph_tags( $should, $metadata ) {
        if ( ! class_exists( 'Webdados_FB_Public' ) ) {
            return $should;
        }

        $this->metadata = $metadata;

        add_filter( 'fb_og_title', array( $this, 'get_title' ) );
        add_filter( 'fb_og_url', array( $this, 'get_url' ) );
        add_filter( 'fb_og_type', array( $this, 'get_content_type' ) );
        add_filter( 'fb_og_desc', array( $this, 'get_description' ) );
        add_filter( 'fb_og_image', array( $this, 'get_image' ) );
        add_filter( 'fb_og_image_additional', array( $this, 'get_additional_images' ) );

        return false;
    }

    /**
     * Replaces the page's title with the title of the listing.
     *
     * @param string $title The title of the page.
     */
    public function get_title( $title ) {
        return $this->metadata->get_listing_metadata_property( 'http://ogp.me/ns#title', $title );
    }

    /**
     * Replaces the page's URL with the one of the listing.
     *
     * @param string $url The URL of the page.
     */
    public function get_url( $url ) {
        return $this->metadata->get_listing_metadata_property( 'http://ogp.me/ns#url', $url );
    }

    /**
     * Replaces the OpenGraph type set for the page with the type
     * set for the listing.
     *
     * @param string $content_type The content type used for the page.
     */
    public function get_content_type( $content_type ) {
        return $this->metadata->get_listing_metadata_property( 'http://ogp.me/ns#type', $content_type );
    }

    /**
     * Replaces the description of the page with the description
     * of the listing.
     *
     * @param string $description The description of the page.
     */
    public function get_description( $description ) {
        return $this->metadata->get_listing_metadata_property( 'http://ogp.me/ns#description', $description );
    }

    /**
     * Replaces the URL to the image set for the page with the
     * URL to the image set for the listing.
     *
     * @param string $image The URL of the image being used for the page.
     */
    public function get_image( $image ) {
        return $this->metadata->get_listing_metadata_property( 'http://ogp.me/ns#image', $image );
    }

    /**
     * Make sure we don't show meta tags for additional images.
     *
     * @param array $additional_images An array of information for additional
     *                                 images of the page.
     */
    public function get_additional_images( $additional_images ) {
        return array();
    }
}

