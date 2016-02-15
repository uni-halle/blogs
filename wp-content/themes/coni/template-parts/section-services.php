<?php
$coni_enable_section = get_theme_mod( 'coni_services_enable', true );
if ( $coni_enable_section || is_customize_preview() ) :
?>
<div id="services-section"class="services-section"  <?php if( false == $coni_enable_section ): echo 'style="display: none;"'; endif ?>>
    <div class="container">
        <div class="row">

			<?php
			if ( is_active_sidebar( 'services-section' ) ){

				dynamic_sidebar( 'services-section' );

			}else{

				$widget_args = array(
					'title' => 'Multipurpose Design',
					'text' => 'Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.', 
					'link_title' => 'Learn More', 
					'link' => '#', 
					'image_uri' => get_stylesheet_directory_uri() . '/images/server.png', 
					);
				the_widget( 'coni_Service', $widget_args );


				$widget_args = array(
					'title' => 'Easy To Customize',
					'text' => 'Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Cum sociis natoque.', 
					'link_title' => 'Learn More', 
					'link' => '#', 
					'image_uri' => get_stylesheet_directory_uri() . '/images/pencil-ruler.png', 
					);
				the_widget( 'coni_Service', $widget_args );


				$widget_args = array(
					'title' => 'Responsive Design',
					'text' => 'Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.', 
					'link_title' => 'Learn More', 
					'link' => '#', 
					'image_uri' => get_stylesheet_directory_uri() . '/images/computer-network.png', 
					);
				the_widget( 'coni_Service', $widget_args );

			};
			?>

        </div><!-- row -->
    </div><!-- container -->
    
</div><!-- services-section -->
<?php endif ?>