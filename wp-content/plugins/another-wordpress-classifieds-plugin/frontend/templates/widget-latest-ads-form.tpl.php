<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e('Title', 'AWPCP'); ?>:</label>
    <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
</p>

<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php _e('Number of Items to Show', 'AWPCP'); ?>:</label>
    <input type="text" size="2" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" value="<?php echo esc_attr( $instance['limit'] ); ?>" />
</p>

<p>
    <input type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'show-title' ) ); ?>" value="0" />
    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show-title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show-title' ) ); ?>" value="1" <?php echo $instance['show-title'] ? 'checked="true"' : ''; ?> />
    <label for="<?php echo esc_attr( $this->get_field_id( 'show-title' ) ); ?>"><?php _e('Show Ad title', 'AWPCP'); ?></label>
    <br/>
    <input type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'show-excerpt' ) ); ?>" value="0" />
    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show-excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show-excerpt' ) ); ?>" value="1" <?php echo $instance['show-excerpt'] ? 'checked="true"' : ''; ?> />
    <label for="<?php echo esc_attr( $this->get_field_id( 'show-excerpt' ) ); ?>"><?php _e('Show Ad excerpt', 'AWPCP'); ?></label>
    <br/>
    <input type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'show-images' ) ); ?>" value="0" />
    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show-images' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show-images' ) ); ?>" value="1" <?php echo $instance['show-images'] ? 'checked="true"' : ''; ?> />
    <label for="<?php echo esc_attr( $this->get_field_id( 'show-images' ) ); ?>"><?php _e('Show Thumbnails in Widget', 'AWPCP'); ?></label>
    <br/>
    <input type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'show-blank' ) ); ?>" value="0" />
    <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show-blank' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show-blank' ) ); ?>" value="1" <?php echo $instance['show-blank'] ? 'checked="true"' : ''; ?>/>
    <label for="<?php echo esc_attr( $this->get_field_id( 'show-blank' ) ); ?>"><?php _e('Show "No Image" PNG when Ad has no picture. Improves layout.', 'AWPCP'); ?></label>
</p>
