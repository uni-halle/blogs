<?php
/**
 * Front Page Text
 *
 * File : aadya-front-page-text.php
 * 
 * @package Aadya
 * @since Aadya 1.0.0
 */
?>
<?php

class aadya_frontpage_thumbnail_text_widget extends WP_Widget {

         public function __construct() {

			/* Widget settings. */
			$widget_ops = array( 'classname' => 'front-page-text', 'description' => __('Front Page Thumbnail Text for Marketing.', 'aadya') );

			/* Widget control settings. */
			$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'widget-front-page-text' );

			/* Create the widget. */
			$this->WP_Widget( 'widget-front-page-text', __('(Aadya) Front Page Thumbnail Text', 'aadya'), $widget_ops, $control_ops );		
		
        }
		
		/* Get Default values of fields. */
		function widget_defaults() {
			return array(
				'title' => '',
				'headline' => '',
				'tagline' => '',
				'image' => '',
				'action_url' => '',
				'action_label' => 'Learn More',
				'action_color' => 'primary',
				'alignment' => '',
				'thumbnail' => 'large',
			);
		}		

        public function form( $instance ) {
	
			$instance = wp_parse_args( (array) $instance, $this->widget_defaults());									  
				
			aadya_widget_field( $this, array ( 'field' => 'title', 'label' => __( 'Title:', 'aadya' ) ), $instance['title'] );
			aadya_widget_field( $this, array ( 'field' => 'image', 'label' => __( 'Image:', 'aadya' ), 'type' => 'media' ), $instance['image'] );
			aadya_widget_field( $this, array ( 'field' => 'thumbnail', 'type' => 'select', 
												   'label' => __( 'Image Size:', 'aadya' ), 
												   'options' => aadya_thumbnail_array(), 
												   'class' => '' ), $instance['thumbnail'] );
			if ( $instance['image'] )
				echo wp_get_attachment_image( $instance['image'], aadya_thumbnail_size( $instance['thumbnail'] ), false, array( 'class' => 'widget-image' ) );
				
			aadya_widget_field( $this, array ( 'field' => 'headline', 'label' => __( 'Headline:', 'aadya' ) ), $instance['headline'] );
			aadya_widget_field( $this, array ( 'field' => 'tagline', 'label' => __( 'Tagline:', 'aadya' ), 'type' => 'textarea' ), $instance['tagline'] );
			aadya_widget_field( $this, array ( 'field' => 'action_url', 'label' => __( 'Action URL:', 'aadya' ), 'type' => 'url' )
									, $instance['action_url'] );
									
			aadya_widget_field( $this, array ( 'field' => 'action_label', 'label' => __( 'Action Label:', 'aadya' ) ), $instance['action_label'] );
			
			aadya_widget_field( $this, array ( 'field' => 'alignment', 'type' => 'select', 
												   'label' => __( 'Alignment: ', 'aadya' ),
												   'options' => array (
														array(	'key' => 'left',
																'name' => __( 'Left', 'aadya' ) ),
														array(	'key' => 'center',
																'name' => __( 'Center', 'aadya' ) ),
														array(	'key' => 'right',
																'name' => __( 'Right', 'aadya' ) ),			
																 ), 'class' => '' ), $instance['alignment'] );
																 
			aadya_widget_field( $this, array ( 'field' => 'action_color', 'type' => 'select', 
															   'label' => __( 'Action Button: ', 'aadya' ),
															   'options' => array (
																	array(	'key' => 'primary',
																			'name' => __( 'Primary', 'aadya' ) ),
																	array(	'key' => 'info',
																			'name' => __( 'Info', 'aadya' ) ),
																	array(	'key' => 'warning',
																			'name' => __( 'Warning', 'aadya' ) ),
																	array(	'key' => 'danger',
																			'name' => __( 'Danger', 'aadya' ) ),
																	array(	'key' => 'success',
																			'name' => __( 'Success', 'aadya' ) ),													
																	array(	'key' => 'default',
																			'name' => __( 'Default', 'aadya' ) ),				
																			 ), 'class' => '' ), $instance['action_color'] );																 
			   
        }

        public function update( $new, $old ) {				
			$instance = $old;
			$instance['title'] = strip_tags( $new['title'] );
			$instance['headline'] = wp_kses_stripslashes($new['headline']);
			$instance['tagline'] = wp_kses_stripslashes($new['tagline']);
			$instance['image'] =  $new['image'];
			$instance['thumbnail'] = $new['thumbnail'];
			$instance['action_url'] = esc_url_raw($new['action_url']);
			$instance['action_label'] = wp_kses_stripslashes($new['action_label']);
			$instance['action_color'] = wp_kses_stripslashes( $new['action_color'] );
			$instance['alignment'] = wp_kses_stripslashes( $new['alignment'] );			
			return $instance;			
        }

        public function widget( $args, $instance ) {	
		
			extract( $args, EXTR_SKIP );
			$instance = wp_parse_args($instance, $this->widget_defaults());
			extract( $instance, EXTR_SKIP );
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);		
			
			echo $before_widget; 			
			echo '<div class="thumbnail thumbnail-style thumbnail-kenburn text-'.esc_attr( $alignment ).'">';
			if ( ! empty( $image ) ) {
			echo '<div class="thumbnail-img fp-img">';
			  echo '<div class="overflow-hidden">';
				echo wp_get_attachment_image( $image, aadya_thumbnail_size( $thumbnail ));
			  echo '</div>';
			echo '</div>';
			}
			echo '<div class="caption">';
			if ( ! empty( $headline ) ) {	
			  echo '<h3>'.esc_attr( $headline ).'</h3>';
			 }
			if ( ! empty( $tagline ) ){	
			  echo '<p>'.do_shortcode( $tagline ).'</p>';
			 } 
			 if ( ! empty( $action_url ) && ! empty( $action_label ) ) {
			  echo '<p><a href="'.esc_url( $action_url ).'" class="btn btn-'.esc_attr( $action_color ).'" role="button">'.esc_attr( $action_label ).'</a> </p>';
			 } 
			echo '</div>';
			echo '</div>';
			echo $after_widget;	

        }
}

?>