<?php

function awpcp_meta() {
    return new AWPCP_Meta( awpcp_request() );
}


class AWPCP_Meta {

    public $ad = null;
    public $properties = array();
    public $category_id = null;

    private $request = null;

    private $doing_opengraph = false;
    private $meta_tags;

    public function __construct( /*AWPCP_Request*/ $request = null ) {
        $this->request = $request;

        add_action( 'template_redirect', array( $this, 'configure' ) );
    }

    public function configure() {
        $this->configure_rel_canonical();
        $this->configure_opengraph_meta_tags();
        $this->configure_title_generation();
    }

    private function configure_rel_canonical() {
        if ( apply_filters( 'awpcp-should-generate-rel-canonical', true, $this ) ) {
            remove_action( 'wp_head', 'rel_canonical' );
            add_action( 'wp_head', 'awpcp_rel_canonical' );
        }
    }

    private function configure_opengraph_meta_tags() {
        $this->ad_id = absint( $this->request->get_ad_id() );

        if ( $this->ad_id === 0 ) {
            return;
        }

        $this->ad = AWPCP_Ad::find_by_id( $this->ad_id );
        $this->properties = awpcp_get_ad_share_info( $this->ad_id );

        if ( is_null( $this->ad ) || is_null( $this->properties ) ) {
            return;
        }

        if ( apply_filters( 'awpcp-should-generate-opengraph-tags', true, $this ) ) {
            add_action( 'wp_head', array( $this, 'opengraph' ) );
            $this->doing_opengraph = true;
        }
    }

    private function configure_title_generation() {
        $this->category_id = $this->request->get_category_id();

        if ( apply_filters( 'awpcp-should-generate-title', true, $this ) ) {
            add_action( 'wp_title', array( $this, 'title' ), 10, 3 );
        }

        if ( apply_filters( 'awpcp-should-generate-single-post-title', true, $this ) ) {
            add_action( 'single_post_title', array( $this, 'page_title' ), 10, 2 );
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

    private function seplocation($title, $sep) {
        $name = awpcp_get_blog_name( $decode_html = false );
        $regex = false;
        $seplocation = false;

        $left = '/^' . preg_quote($name, '/') . '\s*' . preg_quote(trim($sep), '/') . '\s*/';
        $right = '/' . '\s*' . preg_quote(trim($sep), '/') . '\s*' . preg_quote($name, '/') . '/';

        $seplocation = '';
        if (preg_match($left, $title, $matches)) {
            $seplocation = 'left';
            $regex = $left;
        } else if (preg_match($right, $title, $matches)) {
            $seplocation = 'right';
            $regex = $right;
        }

        if ($regex) {
            $title = preg_replace($regex, '', $title);
            $name = $matches[0];
        } else {
            $name = '';
        }

        return array($title, $name, $seplocation);
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

    public function title($title, $separator='-', $seplocation='left') {
        if ( ! $this->is_browse_categories_or_single_ad_page() )
            return $title;

        // We want to strip separators characters from each side of
        // the title. WordPress uses wptexturize to replace some characters
        // with HTML entities, we need to do the same in case the separator
        // is one of those characters.
        $regex = '(\s(?:' . preg_quote($separator, '/') . '|' . preg_quote(trim(wptexturize(" $separator ")), '/') . ')\s*)';
        if (preg_match('/^' . $regex . '/', $title, $matches)) {
            $title = preg_replace('/^' . $regex . '/', '', $title);
            $appendix = ($matches[0]);
        } else if (preg_match('/' . $regex . '$/', $title, $matches)) {
            $title = preg_replace('/' . $regex . '$/', '', $title);
            $appendix = ($matches[0]);
        } else {
            $appendix = '';
        }
        // $title = trim($title, " $separator" . trim(wptexturize(" $separator ")));

        // if $seplocation is empty we are probably being called from one of
        // the SEO plugin's integration functions. We need to strip the
        // blog's name from the title and add it again at the end of the proceess
        if (empty($seplocation)) {
            list($title, $name, $seplocation) = $this->seplocation($title, $separator);
        } else {
            $name = '';
        }

        $sep = $this->get_separator( $separator );
        $page_title = $this->get_page_title( $sep, $seplocation );

        $title = trim($title, " $sep");
        if ($seplocation == 'right') {
            $title = sprintf( "%s %s %s%s%s", $page_title, $sep, $title, $name, $appendix );
        } else {
            $title = sprintf( "%s%s%s %s %s", $appendix, $name, $title, $sep, $page_title );
        }

        return $title;
    }

    private function is_browse_categories_or_single_ad_page() {
        // we want't to use the original query but calling wp_reset_query
        // breaks things for Events Manager and maybe other plugins
        $query = $GLOBALS['wp_the_query'];

        if ( ! isset( $query ) ) return false;

        $show_ad_page = awpcp_get_page_id_by_ref( 'show-ads-page-name' );
        $browse_cats_page = awpcp_get_page_id_by_ref( 'browse-categories-page-name' );

        $is_show_ad_page = $query->is_page( $show_ad_page );
        $is_browse_ads_page = $query->is_page( $browse_cats_page );

        // only change title in the Show Ad and Browse Categories pages
        if ( ! $is_show_ad_page && ! $is_browse_ads_page )
            return false;
        if ( $is_show_ad_page && is_null( $this->ad ) )
            return false;
        if ( $is_browse_ads_page && empty( $this->category_id ) )
            return false;

        return true;
    }

    private function get_separator( $fallback_separator = '-' ) {
        $separator = get_awpcp_option( 'awpcptitleseparator' );
        return empty( $separator ) ? $fallback_separator : $separator;
    }

    private function get_page_title( $fallback_separator = '-', $seplocation = 'right' ) {
        $separator = $this->get_separator( $fallback_separator );

        $parts = array();

        if ( ! empty( $this->category_id ) ) {
            $parts[] = get_adcatname( $this->category_id );

        } else if ( ! is_null( $this->ad ) ) {
            $regions = $this->ad->get_regions();
            if ( count( $regions ) > 0 ) {
                $region = $regions[0];
            } else {
                $region = array();
            }

            if ( get_awpcp_option( 'showcategoryinpagetitle' ) ) {
                $parts[] = get_adcatname( $this->ad->ad_category_id );
            }

            if ( get_awpcp_option( 'showcountryinpagetitle' ) ) {
                $parts[] = awpcp_array_data( 'country', '', $region );
            }

            if ( get_awpcp_option( 'showstateinpagetitle' ) ) {
                $parts[] = awpcp_array_data( 'state', '', $region );
            }

            if ( get_awpcp_option( 'showcityinpagetitle' ) ) {
                $parts[] = awpcp_array_data( 'city', '', $region );
            }

            if ( get_awpcp_option( 'showcountyvillageinpagetitle' ) ) {
                $parts[] = awpcp_array_data( 'county', '', $region );
            }

            $parts[] = $this->ad->get_title();
        }

        $parts = array_filter( $parts );
        $parts = $seplocation === 'right' ? array_reverse( $parts ) : $parts;

        return implode( " $separator ", $parts );
    }

    public function page_title( $post_title, $post ) {
        if ( ! $this->is_browse_categories_or_single_ad_page() )
            return $post_title;
        return $this->get_page_title();
    }

    // The function to add the page meta and Facebook meta to the header of the index page
    // https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2F108.166.84.26%2F%25253Fpage_id%25253D5%252526id%25253D3&t=Ad+in+Rackspace+1.8.9.4+(2)
    public function opengraph() {
        // http://wiki.whatwg.org/wiki/FAQ#Should_I_close_empty_elements_with_.2F.3E_or_.3E.3F
        $CLOSE = current_theme_supports('html5') ? '>' : ' />';

        $meta_tags = $this->get_meta_tags();

        // TODO: handle integration with other plugins
        echo '<meta name="title" content="' . $meta_tags['http://ogp.me/ns#title'] . '"' . $CLOSE . PHP_EOL;
        echo '<meta name="description" content="' . $meta_tags['http://ogp.me/ns#description'] . '"' . $CLOSE . PHP_EOL;

        echo '<meta property="og:type" content="' . $meta_tags['http://ogp.me/ns#type'] . '"' . $CLOSE . PHP_EOL;
        echo '<meta property="og:url" content="' . $meta_tags['http://ogp.me/ns#url'] . '"' . $CLOSE . PHP_EOL;
        echo '<meta property="og:title" content="' . $meta_tags['http://ogp.me/ns#title'] . '"' . $CLOSE . PHP_EOL;
        echo '<meta property="og:description" content="' . $meta_tags['http://ogp.me/ns#description'] . '"' . $CLOSE . PHP_EOL;

        echo '<meta property="article:published_time" content="' . $meta_tags['http://ogp.me/ns/article#published_time'] . '"' . $CLOSE . PHP_EOL;
        echo '<meta property="article:modified_time" content="' . $meta_tags['http://ogp.me/ns/article#modified_time'] . '"' . $CLOSE . PHP_EOL;

        foreach ( $meta_tags as $property => $content ) {
            if ( $property === 'http://ogp.me/ns#image' ) {
                echo '<meta property="og:image" content="' . $content . '"' . $CLOSE . PHP_EOL;
            }
        }

        if ( isset( $meta_tags['http://ogp.me/ns#image'] ) ) {
            // this helps Facebook determine which image to put next to the link
            echo '<link rel="image_src" href="' . $meta_tags['http://ogp.me/ns#image'] . '"' . $CLOSE . PHP_EOL;
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
            $this->meta_tags['http://ogp.me/ns#image'] = AWPCP_URL . '/resources/images/adhasnoimage.gif';
        }

        return $this->meta_tags;
    }

    /**
     * Integration with SEO Ultimate.
     */
    public function seo_ultimate() {
        // overwrite title
        add_filter( 'single_post_title', array( $this, 'seo_ultimate_title' ) );
        remove_filter('wp_title', array($this, 'title'), 10, 3);

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

        return $this->title( $title, '', $seplocation );
    }

    /**
     * Integration with All In One SEO Pack
     */
    public function all_in_one_seo_pack() {
        add_filter( 'aioseop_title', array( $this, 'all_in_one_seo_pack_title' ) );
        remove_filter( 'wp_title', array( $this, 'title' ), 10, 3 );
    }

    public function all_in_one_seo_pack_title($title) {
        global $aioseop_options;

        $title_format = awpcp_array_data( 'aiosp_page_title_format', '', $aioseop_options );

        if ( string_starts_with( $title_format, '%page_title%' ) ) {
            $seplocation = 'right';
        } else {
            $seplocation = 'left';
        }

        return $this->title( $title, '', $seplocation );
    }

    /**
     * Jetpack Integration
     */
    public function jetpack() {
        if (!$this->doing_opengraph) return;

        remove_action('wp_head', 'jetpack_og_tags');
    }
}
