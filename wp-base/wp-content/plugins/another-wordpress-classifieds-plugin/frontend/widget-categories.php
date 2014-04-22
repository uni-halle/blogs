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

        echo awpcp_render_categories( 0, array(
            'columns' => 1,
            'hide_empty' => $instance['hide-empty'],
            'show_children' => !$instance['show-parents-only'],
            'show_ad_count' => $instance['show-ad-count'],
            'show_sidebar' => false,
        ) );

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
