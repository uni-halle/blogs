<?php
class ocmx_comment_widget extends WP_Widget {
    /** constructor */
    function ocmx_comment_widget() {
        parent::WP_Widget(false, $name = "OCMX Latest Comments", array("description" => "Display the Latest Comments"));	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
		global $wpdb;
        $comment_count = esc_attr($instance["comment_count"]);
		$latest_comments = $wpdb->get_results($wpdb->prepare( "SELECT * FROM $wpdb->comments WHERE comment_approved = 1 ORDER BY comment_date DESC LIMIT ".$comment_count, "ARRAY_A") );
?>      
		<li class="widget recent-comments">
			<div class="container-header-dark-normal"><span></span></div>
			<ul class="container-dark">
				<?php foreach($latest_comments as $latest_comment) : 
					$this_comment = get_comment($latest_comment->comment_ID);
					$use_id = $this_comment->comment_post_ID;
					$this_post = get_post($use_id); 
					$post_title = $this_post->post_title;
					$post_link = get_permalink($post->ID);
				?>
					<li class="clearfix">
						<a href="<?php echo get_comment_link($latest_comment->comment_ID); ?>" class="detail-image"><?php echo get_avatar($this_comment, 40 ); ?></a>
						<a href="<?php echo get_comment_link($latest_comment->comment_ID); ?>" class="detail-link">
							<span class="date"><?php echo date('F d Y', strtotime($latest_comment->comment_date)); ?> <?php echo date("H\:i a", strtotime($latest_comment->comment_date)); ?></span>
							<?php echo $post_title; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="container-footer-dark-normal"><span></span></div>
        </li>
<?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $comment_count = 		esc_attr($instance["comment_count"]);
		
        ?>
            <p>
            	<label for="<?php echo $this->get_field_id('comment_count'); ?>">Comment Count</label>
                <select size="1" class="widefat" id="<?php echo $this->get_field_id('comment_count'); ?>" name="<?php echo $this->get_field_name('comment_count'); ?>">
                	<?php for($i = 1; $i < 10; $i++) : ?>
	                    <option <?php if($comment_count == $i) : ?>selected="selected"<?php endif; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
			</p>
<?php 
	} // form

}// class

//This sample widget can then be registered in the widgets_init hook:

// register FooWidget widget
add_action('widgets_init', create_function('', 'return register_widget("ocmx_comment_widget");'));

?>
