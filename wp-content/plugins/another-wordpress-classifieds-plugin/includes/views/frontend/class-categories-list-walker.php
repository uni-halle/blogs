<?php

if ( ! class_exists( 'Walker' ) ) {
  require_once( ABSPATH . '/wp-includes/class-wp-walker.php' );
}

if ( class_exists( 'Walker' ) ) {

class AWPCP_CategoriesListWalker extends Walker {

    protected $options = array();
    protected $all_elements_count = 0;
    protected $top_level_elements_count = 0;
    protected $elements_count = 0;

    public function __construct() {
        $this->db_fields = array( 'id' => 'id', 'parent' => 'parent' );
    }

    public function configure( $options = array() ) {
        $this->options = wp_parse_args( $options, array(
            'show_in_columns' => 1,
            'show_listings_count' => true,
            'collapsible_categories' => get_awpcp_option( 'collapse-categories-columns' ),

            'first_level_ul_class' => 'top-level-categories showcategoriesmainlist clearfix',
            'second_level_ul_class' => 'sub-categories showcategoriessublist clearfix',
            'first_level_element_wrapper' => 'p',
            'first_level_element_wrapper_class' => 'top-level-category maincategoryclass',
            'second_level_element_wrapper' => false,
            'second_level_element_wrapper_class' => false,
        ) );

        return true;
    }

    public function walk( $elements, $max_depth = 0 ) {
        $this->all_elements_count = count( $elements );
        return str_replace( '[categories-list]', parent::walk( $elements, $max_depth ), $this->list_container() );
    }

    protected function list_container() {
        $container = '<div id="awpcpcatlayout" class="awpcp-categories-list">[categories-list]</div><div class="fixfloat"></div>';
        return apply_filters( 'awpcp-categories-list-container', $container, $this->options );
    }

    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        if ( $this->options['collapsible_categories'] ) {
            $element_start = '<ul %s data-collapsible="true">';
        } else {
            $element_start = '<ul %s>';
        }

        $class = $this->options[ 'second_level_ul_class' ];

        if ( ! empty( $class ) ) {
            $output .= sprintf( $element_start, 'class="' . $class . '"' );
        } else {
            $output .= sprintf( $element_start, '' );
        }
    }

    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= '</ul>';
    }

    public function start_el( &$output, $category, $depth = 0, $args = array(), $current_object_id = 0 ) {
        if ( $this->is_first_element_in_row( $depth ) ) {
            $output .= $this->first_level_ul_start();
        }

        if ( $depth == 0 ) {
            $output .= sprintf( '<li class="columns-%d">', $this->options['show_in_columns'] );
            $output .= $this->first_level_element_wrapper_start();
        } else {
            $output .= '<li>';
            $output .= $this->second_level_element_wrapper_start();
        }

        $output .= $this->element( $category, $depth, $args, $current_object_id );

        if ( $depth == 0 ) {
            $output .= $this->first_level_element_wrapper_end();
        } else {
            $output .= $this->second_level_element_wrapper_end();
        }

        $this->update_elements_count( $depth );
    }

    private function is_first_element_in_row( $depth ) {
        if ( $depth != 0 ) {
            return false;
        }

        if ( $this->top_level_elements_count == 0 ) {
            return true;
        }

        if ( $this->options['show_in_columns'] > 1 && $this->top_level_elements_count % $this->options['show_in_columns'] == 0 ) {
            return true;
        }

        return false;
    }

    private function first_level_ul_start() {
        if ( ! empty( $this->options[ 'first_level_ul_class' ] ) ) {
            return sprintf( '<ul class="%s">', $this->options[ 'first_level_ul_class' ] );
        } else {
            return '<ul>';
        }
    }

    private function first_level_element_wrapper_start() {
        $tag = $this->options['first_level_element_wrapper'];
        $class = $this->options['first_level_element_wrapper_class'];
        return $this->element_wrapper_start( $tag, $class );
    }

    private function element_wrapper_start( $tag, $class ) {
        if ( ! empty( $tag ) && ! empty( $class ) ) {
            return sprintf( '<%s class="%s">', $tag, $class );
        } else if ( ! empty( $tag ) ) {
            return sprintf( '<%s>', $tag );
        } else {
            return '';
        }
    }

    private function second_level_element_wrapper_start() {
        $tag = $this->options['second_level_element_wrapper'];
        $class = $this->options['second_level_element_wrapper_class'];
        return $this->element_wrapper_start( $tag, $class );
    }

    protected function element( $category, $depth, $args, $current_object_id ) {
        $element = '[category-icon]<a class="[category-class]" href="[category-url]">[category-name]</a> [listings-count][js-handler]';
        $element = str_replace( '[category-icon]', $this->render_category_icon( $category ), $element );
        $element = str_replace( '[category-class]', $depth == 0 ? 'toplevelitem' : '', $element );
        $element = str_replace( '[category-url]', esc_attr( url_browsecategory( $category->id ) ), $element );
        $element = str_replace( '[category-name]', esc_attr( $category->name ), $element );
        $element = str_replace( '[listings-count]', $this->render_listings_count( $category ), $element );
        $element = str_replace( '[js-handler]', $this->render_js_handler( $depth ), $element );

        return $element;
    }

    private function first_level_element_wrapper_end() {
        return $this->element_wrapper_end( $this->options['first_level_element_wrapper'] );
    }

    private function element_wrapper_end( $tag ) {
        if ( $tag ) {
            return '</' . $tag . '>';
        } else {
            return '';
        }
    }

    private function second_level_element_wrapper_end() {
        return $this->element_wrapper_end( $this->options['second_level_element_wrapper'] );
    }

    private function render_category_icon( $category ) {
        if ( ! function_exists( 'get_category_icon' ) || ! function_exists( 'awpcp_category_icon_url' ) ) {
            return '';
        }

        $category_icon_filename = get_category_icon( $category->id );

        if ( empty( $category_icon_filename ) ) {
            return '';
        }

        $category_icon_url = awpcp_category_icon_url( $category_icon_filename );

        $category_icon = '<a href="[category-url]"><img class="categoryicon" src="[category-icon-url]" alt="[category-name]" border="0" /></a>';
        $category_icon = str_replace( '[category-icon-url]', $category_icon_url, $category_icon );

        return $category_icon;
    }

    private function render_listings_count( $category ) {
        return $this->options['show_listings_count'] ? '(' . $category->listings_count . ')' : '';
    }

    private function render_js_handler( $depth ) {
        if ( $this->options['collapsible_categories'] && $depth == 0 ) {
            return '<a class="js-handler" href="#"><span></span></a>';
        } else {
            return '';
        }
    }

    private function update_elements_count( $depth ) {
        if ( $depth == 0 ) {
            $this->top_level_elements_count = $this->top_level_elements_count + 1;
        }
        $this->elements_count = $this->elements_count + 1;
    }

    public function end_el( &$output, $object, $depth = 0, $args = array() ) {
        $output .= '</li>';

        if ( $this->is_last_element_in_row( $depth ) ) {
            $output .= '</ul>';
        }
    }

    private function is_last_element_in_row( $depth ) {
        if ( $depth != 0 ) {
            return false;
        }

        if ( $this->options['show_in_columns'] > 1 && $this->top_level_elements_count % $this->options['show_in_columns'] == 0 ) {
            return true;
        }

        if ( $this->elements_count == $this->all_elements_count ) {
            return true;
        }

        return false;
    }
}

}
