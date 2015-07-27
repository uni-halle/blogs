<?php

class DF_FAQ_Widget extends WP_Widget {
    
    public $options;
    
    //Constructor
    public function __construct() {
        parent::__construct('faq_widget', __('FAQ Widget', 'df'), array( 'description' => __( 'A FAQ Widget', 'df' ), ));
        $this->options = get_option('faq_options');
    }
    
    /*
     * 
     * Widget Initialization
     * 
     */
    public function widget( $args, $instance ) {
        $catid = isset( $instance[ 'catid' ] ) ? $instance[ 'catid' ] : __( 'New ID', 'faq' );
        $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Frequently Asked Questions', 'faq' );
        $before_title = $args['before_widget'];
        $after_widget = $args['after_widget'];
        if ( ! empty( $catid ) ) {
            $cat = get_term( $catid, 'faq_categories' );
            $html = '';
            $data = $this->options;
            if(!isset($data['theme'])) $data['theme'] = 'theme-1';
            if(!isset($data['expand'])) $data['expand'] = 'false';
            if(!isset($data['faq_speed']) || $data['faq_speed'] == '') $data['faq_speed'] = 500;
            include DF_FILES_DIR . '/templates/widget_view.php';
            echo $before_title;
            echo $html;
            echo $after_widget;
        }
    }
    
    /*
     * 
     * Widget Front End
     * 
     */
    public function form( $instance ) {
        $catid = isset( $instance[ 'catid' ] ) ? $instance[ 'catid' ] : __( 'New ID', 'faq' );
        $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Frequently Asked Questions', 'faq' );
        ?>
        <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:', 'df' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_name( 'catid' ); ?>"><?php _e( 'Category ID:', 'df' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'catid' ); ?>" name="<?php echo $this->get_field_name( 'catid' ); ?>" type="text" value="<?php echo esc_attr( $catid ); ?>" />
        </p>
        <?php 
    }
       
    /*
     * 
     * Widget Update
     * 
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['catid'] = ( ! empty( $new_instance['catid'] ) ) ? strip_tags( $new_instance['catid'] ) : '';
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }

}

function df_register_widgets() {
    register_widget( 'DF_FAQ_Widget' );
}

add_action( 'widgets_init', 'df_register_widgets' );

