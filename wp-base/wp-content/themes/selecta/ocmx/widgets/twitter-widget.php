<?php
class ocmx_twitter_widget extends WP_Widget {
    /** constructor */
    function ocmx_twitter_widget() {
        parent::WP_Widget(false, $name = 'OCMX Twitter Stream', $widget_options = 'Display your latest Flickr Photos.', $control_options ="TESTTESTETETETETE");	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $twitter_key = $instance["twitter_id"];
        $twitter_count = $instance["twitter_count"];
        $twitter_src  = "http://twitter.com/statuses/user_timeline/".$twitter_key.".json?callback=twitterCallback2&amp;count=".$twitter_count;
        ?>
			<?php echo $before_widget; ?>
				<?php echo $before_title?>
	                <a href="http://twitter.com/<?php echo $twitter_key; ?>" target="_blank" rel="nofollow">
						<?php echo $instance['title']; ?>
					</a>
				<?php echo $after_title; ?>
                <ul id="twitter_update_list" class="rc_list">
                	<li>...loading Twitter Stream</li>
                </ul>
                <script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
                <script type="text/javascript" src="<?php echo $twitter_src ?>"></script>
			<?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance["title"]);
        $twitter_id = esc_attr($instance["twitter_id"]);
        $twitter_count = esc_attr($instance["twitter_count"]);
		
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>">Title<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('twitter_id'); ?>">Twitter ID<input class="widefat" id="<?php echo $this->get_field_id('twitter_id'); ?>" name="<?php echo $this->get_field_name('twitter_id'); ?>" type="text" value="<?php echo $twitter_id; ?>" /></label></p>
			<p>
            	<label for="<?php echo $this->get_field_id('twitter_count'); ?>">Tweet Count
                <select size="1" class="widefat" id="<?php echo $this->get_field_id('twitter_count'); ?>" name="<?php echo $this->get_field_name('twitter_count'); ?>">
                    <option <?php if($twitter_count == "1") : ?>selected="selected"<?php endif; ?> value="1">1</option>
                    <option <?php if($twitter_count == "2") : ?>selected="selected"<?php endif; ?> value="2">2</option>
                    <option <?php if($twitter_count == "4") : ?>selected="selected"<?php endif; ?> value="4">4</option>
                    <option <?php if($twitter_count == "6") : ?>selected="selected"<?php endif; ?> value="6">6</option>
                    <option <?php if($twitter_count == "8") : ?>selected="selected"<?php endif; ?> value="8">8</option>
                    <option <?php if($twitter_count == "10") : ?>selected="selected"<?php endif; ?> value="10">10</option>
                </select>
			</p>
        <?php 
    }

} // class FooWidget

//This sample widget can then be registered in the widgets_init hook:

// register FooWidget widget
add_action('widgets_init', create_function('', 'return register_widget("ocmx_twitter_widget");'));

?>