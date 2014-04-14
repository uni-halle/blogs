<?php
/**
 * Social Icon Box Widget
 *
 * File : aadya-social-box-widget.php
 * 
 * @package Aadya
 * @since Aadya 1.0.0
 */
?>
<?php

class aadya_socialiconbox_widget extends WP_Widget {

         public function __construct() {

			/* Widget settings. */
			$widget_ops = array( 'classname' => 'social-icon-box', 'description' => __('Enable Social Icon Box for your site.', 'aadya') );

			/* Widget control settings. */
			$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'widget-social-icon-box' );

			/* Create the widget. */
			$this->WP_Widget( 'widget-social-icon-box', __('(Aadya) Socal Icon Box', 'aadya'), $widget_ops, $control_ops );				
        }
		
		/* Get Default values of fields. */
		function widget_defaults() {
			return array(
					'title' => '', 
					'show_facebook_icon' => '',
					'facebook_profile'=>'',						
					'show_twitter_icon' => '', 
					'twitter_profile' => '', 					
					'show_googleplus_icon' => '', 
					'googleplus_profile' => '', 							
					'show_linkedin_icon' => '', 
					'linkedin_profile' => '', 									
					'show_pinterest_icon' => '', 
					'pinterest_profile' => '',							
					'show_rss_icon' => '', 
					'rss_profile' => '',					
					'icon_style' =>''
			);
		}		

        public function form( $instance ) {	
			$instance = wp_parse_args( (array) $instance, $this->widget_defaults());	
			
			aadya_widget_field( $this, array ( 'field' => 'title', 'label' => __( 'Title:', 'aadya' ) ), $instance['title'] );
			
			//facebook
			aadya_widget_field( $this, array ( 'ptag' => false, 'field' => 'show_facebook_icon', 'label' => __( 'Enable Facebook: ', 'aadya' ),'type' => 'checkbox', 'class'=>''), $instance['show_facebook_icon'] );
			aadya_widget_field( $this, array ( 'ptag' => false, 'field' => 'facebook_profile', 'type' => 'url' ) , $instance['facebook_profile'] );
			aadya_widget_field( $this, array ( 'field' => 'facebook_profile', 'type' => 'label', 'desc' => __( '(e.g: http://facebook.com/my-profile) ', 'aadya' ) ) , '' );
			
			//twitter
			aadya_widget_field( $this, array ( 'ptag' => false, 'field' => 'show_twitter_icon', 'label' => __( 'Enable Twitter: ', 'aadya' ),'type' => 'checkbox', 'class'=>''), $instance['show_twitter_icon'] );
			aadya_widget_field( $this, array ( 'ptag' => false, 'field' => 'twitter_profile', 'type' => 'url' ) , $instance['twitter_profile'] );
			aadya_widget_field( $this, array ( 'field' => 'twitter_profile', 'type' => 'label', 'desc' => __( '(e.g: http://twitter.com/my-profile) ', 'aadya' ) ) ,'' );
			
			//googleplus
			aadya_widget_field( $this, array ( 'ptag' => false, 'field' => 'show_googleplus_icon', 'label' => __( 'Enable GooglePlus: ', 'aadya' ),'type' => 'checkbox', 'class'=>''), $instance['show_googleplus_icon'] );
			aadya_widget_field( $this, array ( 'ptag' => false, 'field' => 'googleplus_profile', 'type' => 'url' ) , $instance['googleplus_profile'] );
			aadya_widget_field( $this, array ( 'field' => 'googleplus_profile', 'type' => 'label', 'desc' => __( '(e.g: http://googleplus.com/my-profile) ', 'aadya' ) ) , '' );		
			
			//linkedin
			aadya_widget_field( $this, array ( 'ptag' => false, 'field' => 'show_linkedin_icon', 'label' => __( 'Enable LinkedIn: ', 'aadya' ),'type' => 'checkbox', 'class'=>''), $instance['show_linkedin_icon'] );
			aadya_widget_field( $this, array ( 'ptag' => false, 'field' => 'linkedin_profile', 'type' => 'url' ) , $instance['linkedin_profile'] );
			aadya_widget_field( $this, array ( 'field' => 'linkedin_profile', 'type' => 'label', 'desc' => __( '(e.g: http://linkedin.com/my-profile) ', 'aadya' ) ) ,'' );		

			//pinterest
			aadya_widget_field( $this, array ( 'ptag' => false, 'field' => 'show_pinterest_icon', 'label' => __( 'Enable Pinterest: ', 'aadya' ),'type' => 'checkbox', 'class'=>''), $instance['show_pinterest_icon'] );
			aadya_widget_field( $this, array ( 'ptag' => false, 'field' => 'pinterest_profile', 'type' => 'url' ) , $instance['pinterest_profile'] );
			aadya_widget_field( $this, array ( 'field' => 'pinterest_profile', 'type' => 'label', 'desc' => __( '(e.g: http://pinterest.com/my-profile) ', 'aadya' ) ) , '' );		

			//rss
			aadya_widget_field( $this, array ( 'ptag' => false, 'field' => 'show_rss_icon', 'label' => __( 'Enable RSS: ', 'aadya' ),'type' => 'checkbox', 'class'=>''), $instance['show_rss_icon'] );
			aadya_widget_field( $this, array ( 'ptag' => false, 'field' => 'rss_profile', 'type' => 'url' ) , $instance['rss_profile'] );
			aadya_widget_field( $this, array ( 'field' => 'rss_profile', 'type' => 'label', 'desc' => __( '(e.g: http://rss.com/my-profile) ', 'aadya' ) ) , '');	
			
			aadya_widget_field( $this, array ( 'field' => 'icon_style', 'type' => 'select', 
												   'label' => __( 'Icon Size: ', 'aadya' ),
												   'options' => array (
														array(	'key' => 'default',
																'name' => __( 'Default', 'aadya' ) ),
														array(	'key' => 'lg',
																'name' => __( 'Large', 'aadya' ) ),
														array(	'key' => 'sm',
																'name' => __( 'Small', 'aadya' ) ),
														array(	'key' => 'xs',
																'name' => __( 'Extra Small', 'aadya' ) )), 'class' => '' ), $instance['icon_style'] );	
        }

        public function update( $new_instance, $old_instance ) {	
			$instance = $old_instance;			
			$new_instance = wp_parse_args( (array) $new_instance, 
										array( 
												'title' => '', 
												'show_facebook_icon' => '',
												'facebook_profile'=>'',		
												'show_twitter_icon' => '', 
												'twitter_profile' => '', 
												'show_googleplus_icon' => '', 
												'googleplus_profile' => '', 
												'show_linkedin_icon' => '', 
												'linkedin_profile' => '', 		
												'show_pinterest_icon' => '', 
												'pinterest_profile' => '',			
												'show_rss_icon' => '', 
												'rss_profile' => ''											
											  ));
											  
			$instance['title'] = strip_tags(stripslashes($new_instance['title']));
			$instance['show_facebook_icon'] = $new_instance['show_facebook_icon'] ? 1 : 0;
			$instance['facebook_profile'] = esc_url_raw($new_instance['facebook_profile']);
			
			$instance['show_twitter_icon'] = $new_instance['show_twitter_icon'] ? 1 : 0;
			$instance['twitter_profile'] = esc_url_raw($new_instance['twitter_profile']);			

			$instance['show_googleplus_icon'] = $new_instance['show_googleplus_icon'] ? 1 : 0;
			$instance['googleplus_profile'] = esc_url_raw($new_instance['googleplus_profile']);		

			$instance['show_linkedin_icon'] = $new_instance['show_linkedin_icon'] ? 1 : 0;
			$instance['linkedin_profile'] = esc_url_raw($new_instance['linkedin_profile']);		

			$instance['show_pinterest_icon'] = $new_instance['show_pinterest_icon'] ? 1 : 0;
			$instance['pinterest_profile'] = esc_url_raw($new_instance['pinterest_profile']);	

			$instance['show_rss_icon'] = $new_instance['show_rss_icon'] ? 1 : 0;
			$instance['rss_profile'] = esc_url_raw($new_instance['rss_profile']);	
			
			$instance['icon_style'] = $new_instance['icon_style'];	

			return $instance;
        }

        public function widget( $args, $instance ) {
		
			extract( $args, EXTR_SKIP );
			$instance = wp_parse_args($instance, $this->widget_defaults());
			extract( $instance, EXTR_SKIP );
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);	

			$btnSize = ($icon_style == "default") ? "" : "btn-".$icon_style;	
			
			echo $before_widget;
			?>
			<div class="social-icon-box-inner">				
			<?php
			if ( $title )
			echo $before_title . $title . $after_title;	
			?>			

				<?php if($show_facebook_icon): ?>
				<a target="_blank" href="<?php echo $facebook_profile ?>" class="btn btn-social-icon btn-facebook <?php echo $btnSize;?>"><i class="fa fa-facebook"></i></a>			
				<?php endif; ?> 
				
				<?php if($show_twitter_icon): ?> 
				<a target="_blank" href="<?php echo $twitter_profile ?>" class="btn btn-social-icon btn-twitter <?php echo $btnSize;?>"><i class="fa fa-twitter"></i></a>
				<?php endif; ?>
				
				<?php if($show_googleplus_icon): ?> 
				<a target="_blank" href="<?php echo $googleplus_profile ?>" class="btn btn-social-icon btn-google-plus <?php echo $btnSize;?>"><i class="fa fa-google-plus"></i></a> 
				<?php endif; ?>
				
				<?php if($show_linkedin_icon): ?> 
				<a target="_blank" href="<?php echo $linkedin_profile ?>" class="btn btn-social-icon btn-linkedin <?php echo $btnSize;?>"><i class="fa fa-linkedin"></i></a> 
				<?php endif;?>
				
				<?php if($show_pinterest_icon): ?> 
				<a target="_blank" href="<?php echo $pinterest_profile ?>" class="btn btn-social-icon btn-pinterest <?php echo $btnSize;?>"><i class="fa fa-pinterest"></i></a> 
				<?php endif;?>
				
				<?php if($show_rss_icon): ?> 
				<a target="_blank" href="<?php echo $rss_profile ?>" class="btn btn-social-icon btn-rss <?php echo $btnSize;?>"><i class="fa fa-rss"></i></a> 
				<?php endif;?>	
				
			</div>
			<?php
			echo $after_widget;
        }
}
?>