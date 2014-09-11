<?php
/**
 * Plugin Name: Services Widget
 * Plugin URI: http://web2feel.com
 * Description: A widget that displays user testimonials.
 * Version: 0.1
 * Author: Jinsona ( Widget framework courtesy - Justin Tadlock )
 * Author URI: http://web2feel.com , http://justintadlock.com
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *
 * textdomain() used - web2feel
 *
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'w2f_services_widgets' );

/**
 * Register our widget.
 * 'Example_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function w2f_services_widgets() {
	register_widget( 'W2F_Service_Widget' );
}

/**
 * Example Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class W2F_Service_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function W2F_Service_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'W2F_Service_Widget', 'description' => __('A widget to display services.', 'web2feel') );

		/* Widget control settings. */
		//$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'W2F_Testimonial_Widget' );

		/* Create the widget. */
		$this->WP_Widget( 'W2F_Service_Widget', __('W2F Service widget', 'web2feel'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract($args, EXTR_SKIP);

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		
		
		$image = $instance['image'];
		$text = $instance['text'];
		$seite = $instance['seite'];

		/* Before widget (defined by themes). */
		echo $before_widget;
			
?>

	<div class="service-widget">
		<a href="<?php echo $seite; ?>">
		<div class="service-pic">
		<img src="<?php echo $image; ?>"></a>
		<h3><?php if ( $title ) echo $title; ?></h3>
		</div>
		<div class="service-text">
		<p><?php echo $text; ?></p></a>
		</div>
	</div>
			
<?php
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['image'] = strip_tags( $new_instance['image'] );		
		$instance['text'] = stripslashes( $new_instance['text']);
		$instance['seite'] = stripslashes( $new_instance['seite']);
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Service', 'web2feel'),'link' => '','name' => '','image' => '','text' => '', 'seite' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'web2feel'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:95%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'seite' ); ?>"><?php _e('Url:', 'web2feel'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'seite' ); ?>" name="<?php echo $this->get_field_name( 'seite' ); ?>" value="<?php echo $instance['seite']; ?>" style="width:95%;" />
		</p>


        <p>
			<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e('Image URL (60 x 60 px):', 'web2feel') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'image' ); ?>" name="<?php echo $this->get_field_name( 'image' ); ?>" value="<?php echo $instance['image']; ?>" style="width:95%;"/>
		</p>
        
    	<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e('Text:', 'web2feel'); ?></label>
			<textarea style="height:200px;" class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['text'] ), ENT_QUOTES)); ?></textarea>
		</p>

	<?php
	}
}

?>