<?php
/**
 * Slider related functions used for slider output
 */

/**
 * Function to pass the variables for php to js file.
 * This funcition passes the slider effect variables.
 */
function catchbox_pass_slider_value() {
	$options = catchbox_get_options();
	if ( !isset( $options['transition_effect'] ) ) {
		$options['transition_effect'] = "fade";
	}
	if ( !isset( $options['transition_delay'] ) ) {
		$options['transition_delay'] = 4;
	}
	if ( !isset( $options['transition_duration'] ) ) {
		$options['transition_duration'] = 1;
	}
	$transition_effect = $options['transition_effect'];
	$transition_delay = $options['transition_delay'] * 1000;
	$transition_duration = $options['transition_duration'] * 1000;
	wp_localize_script(
		'catchbox-slider',
		'js_value',
		array(
			'transition_effect' => $transition_effect,
			'transition_delay' => $transition_delay,
			'transition_duration' => $transition_duration
		)
	);
}//catchbox_pass_slider_value


if ( ! function_exists( 'catchbox_sliders' ) ) :
/**
 * This function to display featured posts on index.php
 *
 * @get the data value from theme options
 * @displays on the index
 *
 * @useage Featured Image, Title and Content of Post
 *
 * @uses set_transient and delete_transient
 */
function catchbox_sliders() {
	//delete_transient( 'catchbox_sliders' );
	
	if ( !$output = get_transient( 'catchbox_sliders' ) ) {
		echo '<!-- refreshing cache -->';

		// get data value from catchbox_options_slider through theme options
		$options = catchbox_get_options();
		// get slider_qty from theme options
		if ( !isset( $options['slider_qty'] ) || !is_numeric( $options['slider_qty'] ) ) {
			$options['slider_qty'] = 4;
		}

		$postperpage = $options['slider_qty'];

		$slider_array = isset( $options['featured_slider'] ) ? (array) $options['featured_slider'] : array() ;

		$post_list		= array();

		foreach ( $slider_array as $slider ) {
			if ( '' != $slider && 'publish' == get_post_status( $slider ) ){
				$post_list[] = $slider;
			}
		}

		$output = '';
		
		if ( ! empty( $post_list ) ) {

			$output = '
			<div id="slider">
				<section id="slider-wrap">';
				$loop = new WP_Query( array(
					'posts_per_page' => $postperpage,
					'post__in'		 => $post_list,
					'orderby' 		 => 'post__in',
					'ignore_sticky_posts' => 1 // ignore sticky posts
				));

				while ( $loop->have_posts()) : 
					$loop->the_post(); 

					$title_attribute = the_title_attribute( 'echo=0' );

					if ( 0 == $loop->current_post ) { 
						$classes = "slides displayblock"; 
					} else { 
						$classes = "slides displaynone"; 
					}
					
					$output .= '
					<div class="'. $classes . '">
						<a href="'. esc_url ( get_permalink() ).'" title="' . $title_attribute .'" rel="bookmark">
								' . get_the_post_thumbnail( get_the_ID(), 'featured-slider', array( 'title' => $title_attribute, 'alt' => $title_attribute, 'class'	=> 'pngfix' ) ).'
						</a>
						<div class="featured-text">'
							. the_title( '<span class="slider-title">','</span>', false ).' <span class="sep">:</span>
							<span class="slider-excerpt">' . get_the_excerpt() . '</span>
						</div><!-- .featured-text -->
					</div> <!-- .slides -->';
				endwhile; 
			wp_reset_postdata();
			
			$output .= '
				</section> <!-- .slider-wrap -->
				<nav id="nav-slider">
					<div class="nav-previous"><img class="pngfix" src="'.get_template_directory_uri().'/images/previous.png" alt="next slide"></div>
					<div class="nav-next"><img class="pngfix" src="'.get_template_directory_uri().'/images/next.png" alt="next slide"></div>
				</nav>
			</div> <!-- #featured-slider -->';
		}

		set_transient( 'catchbox_sliders', $output, 86940 );
	}
	echo $output;
}
endif;  // catchbox_sliders