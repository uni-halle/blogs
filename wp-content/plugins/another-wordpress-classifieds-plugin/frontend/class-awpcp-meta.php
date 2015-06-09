<?php

function awpcp_meta() {
    return new AWPCP_Meta( awpcp_page_title_builder(), awpcp_meta_tags_generator(), awpcp_request() );
}


class AWPCP_Meta {

    public $ad = null;
    public $properties = array();
    public $category_id = null;

    public $title_builder;
    private $meta_tags_genertor;
    private $request = null;

    private $doing_opengraph = false;

    public function __construct( $title_builder, $meta_tags_genertor, $request ) {
        $this->title_builder = $title_builder;
        $this->meta_tags_genertor = $meta_tags_genertor;
        $this->request = $request;

        add_action( 'template_redirect', array( $this, 'configure' ) );
    }

    public function configure() {
        $this->find_current_listing();
        $this->find_current_category_id();

        $this->configure_rel_canonical();
        $this->configure_opengraph_meta_tags();
        $this->configure_title_generation();
        $this->configure_page_dates();

        $this->title_builder->set_current_listing( $this->ad );
        $this->title_builder->set_current_category_id( $this->category_id );
    }

    private function find_current_listing() {
        $this->ad_id = absint( $this->request->get_ad_id() );

        if ( $this->ad_id === 0 ) {
            return null;
        }

        $this->ad = AWPCP_Ad::find_by_id( $this->ad_id );
        $this->properties = awpcp_get_ad_share_info( $this->ad_id );
    }

    private function find_current_category_id() {
        $this->category_id = $this->request->get_category_id();
    }

    private function configure_rel_canonical() {
        if ( apply_filters( 'awpcp-should-generate-rel-canonical', true, $this ) ) {
            remove_action( 'wp_head', 'rel_canonical' );
            add_action( 'wp_head', 'awpcp_rel_canonical' );
        }
    }

    private function configure_opengraph_meta_tags() {
        if ( ! $this->is_single_listing_page() ) {
            return;
        }

        if ( is_null( $this->ad ) || is_null( $this->properties ) ) {
            return;
        }

        if ( apply_filters( 'awpcp-should-generate-opengraph-tags', true, $this ) ) {
            add_action( 'wp_head', array( $this, 'opengraph' ) );
            $this->doing_opengraph = true;
        }
    }

    private function is_single_listing_page() {
        // we want't to use the original query but calling wp_reset_query
        // breaks things for Events Manager and maybe other plugins
        if ( ! isset( $GLOBALS['wp_the_query'] ) ) {
            return false;
        }

        $query = $GLOBALS['wp_the_query'];
        if ( ! $query->is_page( awpcp_get_page_id_by_ref( 'show-ads-page-name' ) ) ) {
            return false;
        }

        if ( is_null( $this->ad ) ) {
            return false;
        }

        return true;
    }

    private function configure_title_generation() {
        if ( ! $this->is_single_listing_page() && ! $this->is_browse_categories_page() ) {
            return;
        }

        if ( apply_filters( 'awpcp-should-generate-title', true, $this ) ) {
            add_action( 'wp_title', array( $this->title_builder, 'build_title' ), 10, 3 );
        }

        if ( apply_filters( 'awpcp-should-generate-single-post-title', true, $this ) ) {
            add_action( 'single_post_title', array( $this->title_builder, 'build_single_post_title' ) );
        }

        // SEO Ultimate
        if ( defined( 'SU_PLUGIN_NAME' ) ) {
            $this->seo_ultimate();
        }

        // All In One SEO Pack
        if ( class_exists( 'All_in_One_SEO_Pack' ) ) {
            $this->all_in_one_seo_pack();
        }

        // Jetpack >= 2.2.2 Integration
        if (function_exists('jetpack_og_tags')) {
            $this->jetpack();
        }
    }

    private function configure_page_dates() {
        if ( ! $this->is_single_listing_page() )
            return;

        add_filter( 'get_the_date', array( $this, 'get_the_date' ), 10, 2 );
        add_filter( 'get_the_modified_date', array( $this, 'get_the_modified_date' ), 10, 2 );
    }

    private function is_browse_categories_page() {
        // we want't to use the original query but calling wp_reset_query
        // breaks things for Events Manager and maybe other plugins
        if ( ! isset( $GLOBALS['wp_the_query'] ) ) {
            return false;
        }

        $query = $GLOBALS['wp_the_query'];
        if ( ! $query->is_page( awpcp_get_page_id_by_ref( 'browse-categories-page-name' ) ) ) {
            return false;
        }

        if ( empty( $this->category_id ) ) {
            return false;
        }

        return true;
    }

    private function remove_wp_title_filter() {
        remove_filter( 'wp_title', array( $this->title_builder, 'build_title' ), 10, 3 );
    }

    // The function to add the page meta and Facebook meta to the header of the index page
    // https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2F108.166.84.26%2F%25253Fpage_id%25253D5%252526id%25253D3&t=Ad+in+Rackspace+1.8.9.4+(2)
    public function opengraph() {
        $metadata = $this->get_listing_metadata();

        $meta_tags = array_merge(
            $this->meta_tags_genertor->generate_basic_meta_tags( $metadata ),
            $this->meta_tags_genertor->generate_opengraph_meta_tags( $metadata )
        );

        foreach ( $meta_tags as $tag ) {
            echo $tag . PHP_EOL;
        }
    }

    public function get_listing_metadata() {
        $metadata = array(
            'http://ogp.me/ns#type' => 'article',
            'http://ogp.me/ns#url' => $this->properties['url'],
            'http://ogp.me/ns#title' => $this->properties['title'],
            'http://ogp.me/ns#description' => htmlspecialchars( $this->properties['description'], ENT_QUOTES, get_bloginfo('charset') ),
            'http://ogp.me/ns/article#published_time' => awpcp_datetime( 'c', $this->properties['published-time'] ),
            'http://ogp.me/ns/article#modified_time' => awpcp_datetime( 'c', $this->properties['modified-time'] ),
        );

        foreach ( $this->properties['images'] as $k => $image ) {
            $metadata['http://ogp.me/ns#image'] = $image;
            break;
        }

        if ( empty( $this->properties['images'] ) ) {
            $metadata['http://ogp.me/ns#image'] = AWPCP_URL . '/resources/images/adhasnoimage.png';
        }

        return $metadata;
    }

    public function get_the_date( $the_date, $d = '' ) {
        if ( ! $d )
            $d = get_option( 'date_format' );

        return mysql2date( $d, $this->properties['published-time'] );
    }

    public function get_the_modified_date( $the_date, $d ) {
        if ( ! $d )
            $d = get_option( 'date_format' );

        return mysql2date( $d, $this->properties['modified-time'] );
    }

    /**
     * Integration with SEO Ultimate.
     */
    public function seo_ultimate() {
        // overwrite title
        add_filter( 'single_post_title', array( $this, 'seo_ultimate_title' ) );
        $this->remove_wp_title_filter();

        // disable OpenGraph meta tags in Show Ad page
        if ($this->doing_opengraph) {
            awpcp_remove_filter( 'su_head', 'SU_OpenGraph' );
        }
    }

    public function seo_ultimate_title($title) {
        $settings = get_option( 'seo_ultimate_module_titles' );
        $title_format = awpcp_array_data( 'title_page', '', $settings );

        if ( string_starts_with( $title_format, '{blog}' ) ) {
            $seplocation = 'left';
        } else {
            $seplocation = 'right';
        }

        return $this->title_builder->build_title( $title, '', $seplocation );
    }

    /**
     * Integration with All In One SEO Pack
     */
    public function all_in_one_seo_pack() {
        add_filter( 'aioseop_title', array( $this, 'all_in_one_seo_pack_title' ) );
        $this->remove_wp_title_filter();
    }

    public function all_in_one_seo_pack_title($title) {
        global $aioseop_options;

        $title_format = awpcp_array_data( 'aiosp_page_title_format', '', $aioseop_options );

        if ( string_starts_with( $title_format, '%page_title%' ) ) {
            $seplocation = 'right';
        } else {
            $seplocation = 'left';
        }

        return $this->title_builder->build_title( $title, '', $seplocation );
    }

    /**
     * Jetpack Integration
     */
    public function jetpack() {
        if (!$this->doing_opengraph) return;

        remove_action('wp_head', 'jetpack_og_tags');
    }
}

function awpcp_page_title_builder() {
    return new AWPCP_PageTitleBuilder();
}
