<?php
$bck_color = get_theme_mod( 'coni_phone_bck_color', '#68beec' );
?>
<?php
$coni_enable_section = get_theme_mod( 'coni_phone_enable', true );
if ( $coni_enable_section || is_customize_preview() ) :
?>
<div id="phone-section" class="phone-section" style="background-color: <?php echo esc_attr( $bck_color ); ?>;" <?php if( false == $coni_enable_section ): echo 'style="display: none;"'; endif ?>>
    <div class="phone-wrap hidden-sm hidden-xs wow fadeInLeft">
    	<?php
    	$screen_image = get_template_directory_uri() . '/images/phone-screen.jpg';
    	$coni_phone_image = get_theme_mod( 'coni_phone_image' );
		if ( !empty( $coni_phone_image ) ) {
			$screen_image = wp_get_attachment_image_src( absint( $coni_phone_image ), 'full' );
			$screen_image = $screen_image[0];
		}
    	?>
        <img class="phone-screen" src="<?php echo esc_url( $screen_image ); ?>" alt="">
        <img class="phone-mockup" src="<?php echo get_template_directory_uri(); ?>/images/iphone-<?php echo esc_attr( get_theme_mod( 'coni_phone_color', 'black' ) ); ?>.png" alt="">
    </div><div class="phone-services">

    	<?php
		if ( is_active_sidebar( 'phone-section' ) ){

			dynamic_sidebar( 'phone-section' );

		}else{

			$widget_args = array(
				'title' => 'Quema Labs',
				'text' => 'Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.',
				'image_uri' => get_stylesheet_directory_uri() . '/images/heart.png', 
				);
			the_widget( 'coni_Service', $widget_args, array( 'before_widget' => '', 'after_widget' => '' ) );

			$widget_args = array(
				'title' => 'Grow Faster',
				'text' => 'Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.',
				'image_uri' => get_stylesheet_directory_uri() . '/images/gauge.png', 
				);
			the_widget( 'coni_Service', $widget_args, array( 'before_widget' => '', 'after_widget' => '' ) );

			$widget_args = array(
				'title' => 'Lunch Your Site',
				'text' => 'Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.',
				'image_uri' => get_stylesheet_directory_uri() . '/images/target.png', 
				);
			the_widget( 'coni_Service', $widget_args, array( 'before_widget' => '', 'after_widget' => '' ) );

			$widget_args = array(
				'title' => 'Multiple Lenguages',
				'text' => 'Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.',
				'image_uri' => get_stylesheet_directory_uri() . '/images/quill-ink.png', 
				);
			the_widget( 'coni_Service', $widget_args, array( 'before_widget' => '', 'after_widget' => '' ) );

		}
		?>
       
        <div class="clearfix"></div>
		
		<?php $coni_phone_link_title = get_theme_mod( 'coni_phone_link_title', esc_html__( 'Learn More', 'coni' ) ); ?>
		<?php if ( !empty( $coni_phone_link_title ) || is_customize_preview() ) { ?>
        <a href="<?php echo esc_url( get_theme_mod( 'coni_phone_link_url', '#' ) ); ?>" class="btn-ql alternative-white wow fadeIn" data-wow-delay="1400ms"><?php echo esc_html( $coni_phone_link_title ); ?></a>
        <?php } ?>

    </div>
    
    
</div><!-- phone-section -->
<?php endif ?>