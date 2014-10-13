<?php

function awpcp_meta() {
    return new AWPCP_Meta( awpcp_page_title_builder(), awpcp_request() );
}


class AWPCP_Meta {

    public $ad = null;
    public $properties = array();
    public $category_id = null;

    public $title_builder;
    private $request = null;

    private $meta_tags;

    private $doing_opengraph = false;

    public function __construct( $title_builder, $request ) {
        $this->title_builder = $title_builder;
        $this->request = $request;

        add_action( 'template_redirect', array( $this, 'configure' ) );
    }

    public function configure() {
        $this->find_current_listing();
        $this->find_current_category_id();

        $this->configure_rel_canonical();
        $this->configure_opengraph_meta_tags();
        $this->configure_title_generation();

        $this->title_builder->set_current_listing( $this->ad );
        $this->title_builder->set_current_category_id( $this->category_id );
    }

    private function find_current_listing() {
        $this->ad_id = absint( $this->request->get_ad_id() );

        if ( $this->ad_id === 0 ) {
            return;
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

    private function remove_filter( $filter, $class ) {
        global $wp_filter;

        if ( !isset( $wp_filter[ $filter ] ) ) return;

        if ( !class_exists( $class ) ) return;

        $id = false;
        foreach ( $wp_filter[ $filter ] as $priority => $functions ) {
            foreach ( $functions as  $idx => $item ) {
                if ( is_array( $item['function'] ) && $item['function'][0] instanceof $class) {
                    $id = $idx;
                    break;
                }
            }

            if ($id) break;
        }

        if ($id) {
            unset( $wp_filter[ $filter ][ $priority ][ $id ] );
        }
    }

    private function remove_wp_title_filter() {
        remove_filter( 'wp_title', array( $this->title_builder, 'build_title' ), 10, 3 );
    }

    // The function to add the page meta and Facebook meta to the header of the index page
    // https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2F108.166.84.26%2F%25253Fpage_id%25253D5%252526id%25253D3&t=Ad+in+Rackspace+1.8.9.4+(2)
    public function opengraph() {
        // http://wiki.whatwg.org/wiki/FAQ#Should_I_close_empty_elements_with_.2F.3E_or_.3E.3F
        $CLOSE = current_theme_supports('html5') ? '>' : ' />';

        $meta_tags = $this->get_meta_tags();

        // TODO: handle integration with other plugins
        echo $this->render_tag( 'meta', array( 'name' => 'title', 'content' => $meta_tags['http://ogp.me/ns#title'] ) );
        echo $this->render_tag( 'meta', array( 'name' => 'description', 'content' => $meta_tags['http://ogp.me/ns#description'] ) );

        echo $this->render_tag( 'meta', array( 'property' => 'og:type', 'content' => $meta_tags['http://ogp.me/ns#type'] ) );
        echo $this->render_tag( 'meta', array( 'property' => 'og:url', 'content' => $meta_tags['http://ogp.me/ns#url'] ) );
        echo $this->render_tag( 'meta', array( 'property' => 'og:title', 'content' => $meta_tags['http://ogp.me/ns#title'] ) );
        echo $this->render_tag( 'meta', array( 'property' => 'og:description', 'content' => $meta_tags['http://ogp.me/ns#description'] ) );

        echo $this->render_tag( 'meta', array( 'property' => 'article:published_time', 'content' => $meta_tags['http://ogp.me/ns/article#published_time'] ) );
        echo $this->render_tag( 'meta', array( 'property' => 'article:modified_time', 'content' => $meta_tags['http://ogp.me/ns/article#modified_time'] ) );

        foreach ( $meta_tags as $property => $content ) {
            if ( $property === 'http://ogp.me/ns#image' ) {
                echo $this->render_tag( 'meta', array( 'property' => 'og:image', 'content' => $content ) );
            }
        }

        if ( isset( $meta_tags['http://ogp.me/ns#image'] ) ) {
            // this helps Facebook determine which image to put next to the link
            echo $this->render_tag( 'link', array( 'rel' => 'image_src', 'href' => $meta_tags['http://ogp.me/ns#image'] ) );
        }
    }

    public function get_meta_tags() {
        if ( ! empty( $this->meta_tags ) ) {
            return $this->meta_tags;
        }

        $charset = get_bloginfo('charset');

        $this->meta_tags = array(
            'http://ogp.me/ns#type' => 'article',
            'http://ogp.me/ns#url' => $this->properties['url'],
            'http://ogp.me/ns#title' => $this->properties['title'],
            'http://ogp.me/ns#description' => htmlspecialchars( $this->properties['description'], ENT_QUOTES, $charset ),
            'http://ogp.me/ns/article#published_time' => $this->properties['published-time'],
            'http://ogp.me/ns/article#modified_time' => $this->properties['modified-time'],
        );

        foreach ( $this->properties['images'] as $k => $image ) {
            $this->meta_tags['http://ogp.me/ns#image'] = $image;
            break;
        }

        if ( empty( $this->properties['images'] ) ) {
            $this->meta_tags['http://ogp.me/ns#image'] = AWPCP_URL . '/resources/images/adhasnoimage.png';
        }

        return $this->meta_tags;
    }

    public function render_tag( $tag_name, $attributes ) {
        $pieces = array();

        foreach ( $attributes as $attribute_name => $attribute_value ) {
            $pieces[] = sprintf( '%s="%s"', $attribute_name, esc_attr( $attribute_value ) );
        }

        // http://wiki.whatwg.org/wiki/FAQ#Should_I_close_empty_elements_with_.2F.3E_or_.3E.3F
        return '<' . $tag_name . ' ' . implode( ' ', $pieces ) . ( current_theme_supports('html5') ? '>' : ' />') . PHP_EOL;
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
            $this->remove_filter( 'su_head', 'SU_OpenGraph' );
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
