<?php

/**
 * @since 3.3
 */
class AWPCP_PageTitleBuilder {

    private $listing;
    private $category_id;

    /**
     * @since 3.3
     */
    public function set_current_listing( $listing ) {
        $this->listing = $listing;
    }

    /**
     * @since 3.3
     */
    public function set_current_category_id( $category_id ) {
        $this->category_id = $category_id;
    }

    /**
     * TODO: test that titles are not generated twice
     * TODO: test that generated title is set after this function finish
     */
    public function build_title( $title, $separator='-', $seplocation='left' ) {
        $original_title = $title;

        if ( ! $this->is_properly_configured() ) {
            return $title;
        }

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

        if ( $this->title_already_includes_page_title( $original_title, $page_title ) ) {
            return $original_title;
        }

        $title = trim($title, " $sep");

        if ($seplocation == 'right') {
            $title = sprintf( "%s %s %s%s%s", $page_title, $sep, $title, $name, $appendix );
        } else {
            $title = sprintf( "%s%s%s %s %s", $appendix, $name, $title, $sep, $page_title );
        }

        return $title;
    }

    private function is_properly_configured() {
        if ( is_null( $this->listing ) && $this->category_id <= 0 ) {
            return false;
        }

        return true;
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

    private function get_separator( $fallback_separator = '-' ) {
        $separator = get_awpcp_option( 'awpcptitleseparator' );
        return empty( $separator ) ? $fallback_separator : $separator;
    }

    private function get_page_title( $fallback_separator = '-', $seplocation = 'right' ) {
        $separator = $this->get_separator( $fallback_separator );

        $parts = array();

        if ( ! empty( $this->category_id ) ) {
            $parts[] = get_adcatname( $this->category_id );

        } else if ( ! is_null( $this->listing ) ) {
            $regions = $this->listing->get_regions();
            if ( count( $regions ) > 0 ) {
                $region = $regions[0];
            } else {
                $region = array();
            }

            if ( get_awpcp_option( 'showcategoryinpagetitle' ) ) {
                $parts[] = get_adcatname( $this->listing->ad_category_id );
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

            $parts[] = $this->listing->get_title();
        }

        $parts = array_filter( $parts );
        $parts = $seplocation === 'right' ? array_reverse( $parts ) : $parts;

        return implode( " $separator ", $parts );
    }

    private function title_already_includes_page_title( $original_title, $page_title ) {
        $texturized_title = wptexturize( $page_title );
        $escaped_texturized_title = esc_html( $texturized_title );

        if ( strpos( $original_title, $page_title ) !== false ) {
            return true;
        } else if ( strpos( $original_title, $texturized_title ) !== false ) {
            return true;
        } else if ( strpos( $original_title, $escaped_texturized_title ) !== false ) {
            return true;
        }

        return false;
    }

    /**
     * TODO: test that titles are not generated twice
     * TODO: test that generated title is set after this function finish
     */
    public function build_single_post_title( $post_title ) {
        if ( ! $this->is_properly_configured() ) {
            return $post_title;
        }

        $page_title = $this->get_page_title();

        if ( $this->title_already_includes_page_title( $post_title, $page_title ) ) {
            return $post_title;
        }

        return $page_title;
    }
}
