<?php
/**
 * Slider related functions used for slider output
 */

/**
 * Function to pass the variables for php to js file.
 * This funcition passes the slider effect variables.
 */
function catchbox_pass_slider_value() {
	$options = catchbox_get_theme_options();
	if( !isset( $options[ 'transition_effect' ] ) ) {
		$options[ 'transition_effect' ] = "fade";
	}
	if( !isset( $options[ 'transition_delay' ] ) ) {
		$options[ 'transition_delay' ] = 4;
	}
	if( !isset( $options[ 'transition_duration' ] ) ) {
		$options[ 'transition_duration' ] = 1;
	}
	$transition_effect = $options[ 'transition_effect' ];
	$transition_delay = $options[ 'transition_delay' ] * 1000;
	$transition_duration = $options[ 'transition_duration' ] * 1000;
	wp_localize_script(
		'catchbox_slider',
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
	global $post;

	//delete_transient( 'catchbox_sliders' );

	// get data value from catchbox_options_slider through theme options
	$options = catchbox_get_theme_options();
	// get slider_qty from theme options
	if( !isset( $options['slider_qty'] ) || !is_numeric( $options['slider_qty'] ) ) {
		$options[ 'slider_qty' ] = 4;
	}

	$postperpage = $options[ 'slider_qty' ];
	if ( isset( $options[ 'featured_slider' ] ) ) {
		//In customizer, all values are returned but with empty, this rectifies the issue in customizer
		$slider_array = array_filter( $options[ 'featured_slider' ] );

		if( ( !$catchbox_sliders = get_transient( 'catchbox_sliders' ) ) && !empty( $slider_array ) ) {
			echo '<!-- refreshing cache -->';

			$catchbox_sliders = '
			<div id="slider">
				<section id="slider-wrap">';
				$get_featured_posts = new WP_Query( array(
					'posts_per_page' => $postperpage,
					'post__in'		 => $options[ 'featured_slider' ],
					'orderby' 		 => 'post__in',
					'ignore_sticky_posts' => 1 // ignore sticky posts
				));

				$i=0; while ( $get_featured_posts->have_posts()) : $get_featured_posts->the_post(); $i++;
					$title_attribute = esc_attr( apply_filters( 'the_title', get_the_title( $post->ID ) ) );

					if ( $i == 1 ) { $classes = "slides displayblock"; } else { $classes = "slides displaynone"; }
					$catchbox_sliders .= '
					<div class="'.$classes.'">
						<a href="'. esc_url ( get_permalink() ).'" title="'.sprintf( esc_attr__( 'Permalink to %s', 'catch-box' ), the_title_attribute( 'echo=0' ) ).'" rel="bookmark">
								'.get_the_post_thumbnail( $post->ID, 'featured-slider', array( 'title' => $title_attribute, 'alt' => $title_attribute, 'class'	=> 'pngfix' ) ).'
						</a>
						<div class="featured-text">'
							.the_title( '<span class="slider-title">','</span>', false ).' <span class="sep">:</span>
							<span class="slider-excerpt">'.get_the_excerpt().'</span>
						</div><!-- .featured-text -->
					</div> <!-- .slides -->';
				endwhile; wp_reset_query();
			$catchbox_sliders .= '
				</section> <!-- .slider-wrap -->
				<nav id="nav-slider">
					<div class="nav-previous"><img class="pngfix" src="'.get_template_directory_uri().'/images/previous.png" alt="next slide"></div>
					<div class="nav-next"><img class="pngfix" src="'.get_template_directory_uri().'/images/next.png" alt="next slide"></div>
				</nav>
			</div> <!-- #featured-slider -->';
			set_transient( 'catchbox_sliders', $catchbox_sliders, 86940 );
		}
		echo $catchbox_sliders;
	}
}
endif;  // catchbox_sliders
