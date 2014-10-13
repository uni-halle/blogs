<?php

class AWPCP_CategoriesWidget extends WP_Widget {

    public function __construct() {
        $description = __('Displays a list of Ad categories.', 'AWPCP');
        parent::__construct( 'awpcp-categories', __( 'AWPCP Categories', 'AWPCP' ), array('description' => $description));
    }

    protected function defaults() {
        $defaults = array(
            'title' => __('Ad Categories', 'AWPCP'),
            'hide-empty' => 0,
            'show-parents-only' => 0,
            'show-ad-count' => get_awpcp_option( 'showadcount' ),
        );

        return $defaults;
    }

    public function widget($args, $instance) {
        extract($args);

        echo $before_widget;

        // do not show empty titles
        $title = apply_filters('widget_title', $instance['title']);
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $params = array(
            'show_empty_categories' => $instance['hide-empty'] ? false : true,
            'show_children_categories' => $instance['show-parents-only'] ? false : true,
            'show_listings_count' => $instance['show-ad-count'],
        );
        echo awpcp_categories_list_renderer()->render( $params );

        echo $after_widget;
    }

    public function form($instance) {
        $instance = array_merge($this->defaults(), $instance);
        include(AWPCP_DIR . '/frontend/templates/widget-categories-form.tpl.php');
    }

    public function update($new_instance, $old_instance) {
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['hide-empty'] = intval( $new_instance['hide-empty'] );
        $instance['show-parents-only'] = intval( $new_instance['show-parents-only'] );
        $instance['show-ad-count'] = intval( $new_instance['show-ad-count'] );
        return $instance;
    }
}
