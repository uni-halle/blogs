<?php

add_action('admin_enqueue_scripts', 'coni_client_widget_scripts');

function coni_client_widget_scripts(){

    wp_enqueue_media();

    wp_enqueue_script( 'coni_client_widget_script', get_template_directory_uri() . '/js/widget.js', false, '1.0', true );

}


class coni_Client extends WP_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'client-widget', // Base ID
            esc_attr__( 'Coni - Client widget', 'coni' ), // Name
            array( 
                'description' => esc_attr__( "Display a Client's logo.", 'coni' ),
            )
        );
    }



    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ){


        echo $args['before_widget'];

        ?>            
            <?php if( !empty($instance['image_uri']) ): ?>
                <img src="<?php echo esc_url( $instance['image_uri'] ); ?>" alt="<?php if( !empty( $instance['title'] ) ): echo esc_attr( $instance['title'] ); endif; ?>" />
            <?php endif; ?>

        <?php

        echo $args['after_widget'];


    }





    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        $instance['title'] = strip_tags( $new_instance['title'] );

        $instance['image_uri'] = strip_tags( $new_instance['image_uri'] );

        return $instance;

    }






    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {

        ?>

        <p>

            <label for="<?php echo $this->get_field_id('image_uri'); ?>"><?php esc_html_e('Image', 'coni'); ?></label><br/>

            <?php
            if ( !empty($instance['image_uri']) ) :

                echo '<img class="custom_media_image" src="' . $instance['image_uri'] . '" style="margin:0;padding:0;max-width:100px;float:left;display:inline-block" /><br />';

            endif;
            ?>



            <input type="text" class="widefat custom_media_url" name="<?php echo $this->get_field_name( 'image_uri' ); ?>"
                   id="<?php echo $this->get_field_id('image_uri'); ?>" value="<?php if( !empty( $instance['image_uri'] ) ): echo $instance['image_uri']; endif; ?>"
                   style="margin-top:5px;">


            <input type="button" class="button button-primary custom_media_button" id="custom_media_button"
                   name="<?php echo $this->get_field_name('image_uri'); ?>" value="<?php esc_attr_e( 'Upload Image', 'coni' ); ?>"
                   style="margin-top:5px;"/>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e( 'Title', 'coni' ); ?></label><br/>

            <input type="text" name="<?php echo $this->get_field_name('title'); ?>"
                   id="<?php echo $this->get_field_id('title'); ?>" value="<?php if( !empty( $instance['title'] ) ): echo $instance['title']; endif; ?>"
                   class="widefat"/>
        </p>


    <?php

    }

}


register_widget( 'coni_Client' );
