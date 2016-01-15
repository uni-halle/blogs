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
            return $original_title;
        }

        $default_page_title = $this->get_page_title();

        if ( empty( $default_page_title ) ) {
            return $default_page_title;
        }

        if ( $this->title_already_includes_page_title( $original_title, $default_page_title ) ) {
            return $original_title;
        }

        // We want to strip separators characters from each side of
        // the title. WordPress uses wptexturize to replace some characters
        // with HTML entities, we need to do the same in case the separator
        // is one of those characters.
        $regex = '(\s' . $this->build_separator_regex( $separator ) . '\s*)';
        if (preg_match('/^' . $regex . '/', $title, $matches)) {
            $title = preg_replace('/^' . $regex . '/', '', $title);
            $appendix = ($matches[0]);
        } else if (preg_match('/' . $regex . '$/', $title, $matches)) {
            $title = preg_replace('/' . $regex . '$/', '', $title);
            $appendix = ($matches[0]);
        } else {
            $appendix = '';
        }

        $sep = $this->get_separator( $separator );

        // if $seplocation is empty we are probably being called from one of
        // the SEO plugin's integration functions. We need to strip the
        // blog's name from the title and add it again at the end of the proceess
        if (empty($seplocation)) {
            $result = $this->find_seplocation( $title, $sep );

            $title = $result['page_title'];
            $name = $result['blog_name'];
            $seplocation = $result['seplocation'];
        } else {
            $name = '';
        }

        $page_title = $this->get_page_title( $sep, $seplocation );

        if ( empty( $page_title ) ) {
            return $original_title;
        }

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

    private function build_separator_regex( $separator ) {
        return '(?:' . preg_quote($separator, '/') . '|' . preg_quote(trim(wptexturize(" $separator ")), '/') . ')';
    }

    private function get_queried_object_name() {
        if ( ! empty( $this->category_id ) ) {
            return get_adcatname( $this->category_id );
        } else if ( ! is_null( $this->listing ) ) {
            return $this->listing->get_title();
        }

        return '';
    }

    private function find_seplocation( $title, $separator ) {
        $title_info = $this->find_seplocation_using_blog_name( $title, $separator );

        if ( ! empty( $title_info ) ) {
            return $title_info;
        }

        $title_info = $this->find_seplocation_using_queried_object_name( $title, $separator );

        if ( ! empty( $title_info ) ) {
            return $title_info;
        }

        return array( 'page_title' => $title, 'blog_name' => '', 'seplocation' => '' );
    }

    private function find_seplocation_using_blog_name( $title, $separator ) {
        $blog_name = awpcp_get_blog_name( $decode_html = false );

        $result = $this->find_name_position_in_title( $title, $blog_name, $separator );

        if ( ! isset( $result['regex'] ) || empty( $result['regex'] ) ) {
            return false;
        }

        $page_title = preg_replace( $result['regex'], '', $title );
        $blog_name = $result['matches'][0];

        return array(
            'page_title' => $page_title,
            'blog_name' => $blog_name,
            'seplocation' => $result['name_position']
        );
    }

    private function find_name_position_in_title( $title, $name, $sep ) {
        $separator_regex = $this->build_separator_regex( trim( $sep ) );

        $left = '/^' . preg_quote($name, '/') . '\s*' . $separator_regex . '\s*/';
        $right = '/' . '\s*' . $separator_regex . '\s*' . preg_quote($name, '/') . '/';

        if (preg_match($left, $title, $matches)) {
            $name_position = 'left';
            $regex = $left;
        } else if (preg_match($right, $title, $matches)) {
            $name_position = 'right';
            $regex = $right;
        } else {
            $name_position = false;
            $regex = false;
        }

        return compact( 'name_position', 'regex', 'matches' );
    }

    private function find_seplocation_using_queried_object_name( $title, $separator ) {
        $object_name = $this->get_queried_object_name();

        $result = $this->find_name_position_in_title( $title, $object_name, $separator );

        if ( ! isset( $result['regex'] ) || empty( $result['regex'] ) ) {
            return false;
        }

        return array(
            'page_title' => $title,
            'blog_name' => '',
            'seplocation' => $result['name_position'] === 'left' ? 'right' : 'left',
        );
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

        if ( empty( $page_title ) ) {
            return $post_title;
        }

        if ( $this->title_already_includes_page_title( $post_title, $page_title ) ) {
            return $post_title;
        }

        return $page_title;
    }
}
