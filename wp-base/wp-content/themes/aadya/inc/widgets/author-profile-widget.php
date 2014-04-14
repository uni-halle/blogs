<?php
/**
 * Google Custom Search Widget
 *
 * File : aadya-google-cse-form.php
 * 
 * @package Aadya
 * @since Aadya 1.0.0
 */
?>
<?php

class aadya_author_profile_widget extends WP_Widget {

         public function __construct() {

			/* Widget settings. */
			$widget_ops = array( 'classname' => 'author-profile-custom', 'description' => __('Enable Author Profile and Display your information in style.', 'aadya') );

			/* Widget control settings. */
			$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'widget-author-profile' );

			/* Create the widget. */
			$this->WP_Widget( 'widget-author-profile', __('(Aadya) Author Profile', 'aadya'), $widget_ops, $control_ops );		
		
        }
		
		/* Get Default values of fields. */
		function widget_defaults() {
			return array(
				'title' => '',
				'short_profile' => '',				
				'image' => '',
				'action_url' => '',
				'action_label' => 'Learn More',
				'thumbnail' => 'large',
			);
		}	

        public function form( $instance ) {		
			$instance = wp_parse_args( (array) $instance, $this->widget_defaults());
			
			aadya_widget_field( $this, array ( 'field' => 'title', 'label' => __( 'Title:', 'aadya' ) ), $instance['title'] );
		
			aadya_widget_field( $this, array ( 'field' => 'image', 'label' => __( 'Image:', 'aadya' ), 'type' => 'media' ), $instance['image'] );

			if ( $instance['image'] )
				echo wp_get_attachment_image( $instance['image'], aadya_thumbnail_size( $instance['thumbnail'] ), false, array( 'class' => 'widget-image' ) );

			aadya_widget_field( $this, array ( 'field' => 'thumbnail', 'type' => 'select', 
												   'label' => __( 'Image Size:', 'aadya' ), 
												   'options' => aadya_thumbnail_array(), 
												   'class' => '' ), $instance['thumbnail'] );				
			
			aadya_widget_field( $this, array ( 'field' => 'short_profile', 'label' => __( 'Short Description:', 'aadya' ), 'type' => 'textarea' ), $instance['short_profile'] );
			
			aadya_widget_field( $this, array ( 'field' => 'action_url', 'label' => __( 'Action URL:', 'aadya' ), 'type' => 'url' )
									, $instance['action_url'] );
									
			aadya_widget_field( $this, array ( 'field' => 'action_label', 'label' => __( 'Action Label:', 'aadya' ) ), $instance['action_label'] );			
		
			
	
			   
        }

        public function update( $new, $old ) {
			$instance = $old;
			$instance['title'] = strip_tags( $new['title'] );
			$instance['short_profile'] = wp_kses_stripslashes($new['short_profile']);
			$instance['image'] =  esc_url($new['image']);
			$instance['thumbnail'] = $new['thumbnail'];
			$instance['action_url'] = esc_url_raw($new['action_url']);
			$instance['action_label'] = wp_kses_stripslashes($new['action_label']);
			return $instance;		
		
        }

        public function widget( $args, $instance ) {
			extract( $args, EXTR_SKIP );
			$instance = wp_parse_args($instance, $this->widget_defaults());
			extract( $instance, EXTR_SKIP );
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);		
			
			echo $before_widget; 			
			echo '<div class="author-profile">';
			if ( $title)
				echo $before_title . $title . $after_title;			
			
			if ( ! empty( $image ) ) {
			echo '<div class="author-img pull-left img-circle">';			 
				echo wp_get_attachment_image( $image, aadya_thumbnail_size( $thumbnail,125,125 ));
			echo '</div>';
			}
			
			if ( ! empty( $short_profile ) ){	
			  echo '<p>'.do_shortcode( $short_profile );
			  if ( ! empty( $action_url ) && ! empty( $action_label ) ) {
				echo '  <a href="'.esc_url( $action_url ).'" class="small" role="button">'.esc_attr( $action_label ).'</a>';
			  } 
			  echo '</p>';
			 } 
			 
			 /*
			 if ( ! empty( $action_url ) && ! empty( $action_label ) ) {
			  echo '<p><a href="'.esc_url( $action_url ).'" class="btn btn-primary btn-'.esc_attr( $action_color ).'" role="button">'.esc_attr( $action_label ).'</a> </p>';
			 } 
			 */
			
			echo '</div>';
			echo $after_widget;			
        }
}
?>