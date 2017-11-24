<?php 
/**
 * Creates the functions that output the slider
*/
function graphene_slider(){
	global $graphene_settings, $graphene_in_slider;
	
	$graphene_in_slider = true;
	if ( $graphene_settings['slider_display_style'] == 'bgimage-excerpt' ) graphene_set_excerpt_length( 35 );

	/* Get the slider posts */
	$sliderposts = graphene_get_slider_posts();
	if ( ! $sliderposts->have_posts() ) return;

	/* Generate classes for the slider wrapper */
	$class = array( 'carousel', 'slide', 'carousel-fade' );
	$class[] = $graphene_settings['slider_display_style'];
	
	$class = apply_filters( 'graphene_slider_class', $class );
	$class = implode( ' ', $class );

	$slidernav_html = '';
    $i = 0;

	do_action( 'graphene_before_slider' ); 
	?>
    <div class="<?php echo $class; ?>" data-ride="carousel" id="graphene-slider">
	    
	    <?php do_action( 'graphene_before_slider_root' ); ?>
	    <div class="carousel-inner" role="listbox">

	    	<?php 
	    		do_action( 'graphene_before_slideritems' );

	    		while ( $sliderposts->have_posts() ) : 
	    			$sliderposts->the_post();

	    			$style = '';

					/* Background image*/
					if ( $graphene_settings['slider_display_style'] == 'bgimage-excerpt' ) {
						$image = graphene_get_slider_image( get_the_ID(), 'graphene_slider', true);
						if ( $image ){
							$style .= 'style="background-image:url(';
							$style .= ( is_array( $image ) ) ? $image[0] : $image;
							$style .= ');"';
						}
					}

					/* Link URL */
					$slider_link_url = esc_url( graphene_get_post_meta( get_the_ID(), 'slider_url' ) );
					if ( ! $slider_link_url ) $slider_link_url = get_permalink();
					$slider_link_url = apply_filters( 'graphene_slider_link_url', $slider_link_url, get_the_ID() );  


	    	?>
				    <div <?php echo $style; ?> class="item <?php if ( $sliderposts->current_post == 0 ) echo 'active'; ?>" id="slider-post-<?php the_ID(); ?>">
				    	<?php do_action( 'graphene_before_sliderpost' ); ?>

				    	<?php if ( $graphene_settings['slider_display_style'] == 'bgimage-excerpt' ) : ?>
		                	<a href="<?php echo $slider_link_url; ?>" class="permalink-overlay" title="<?php _e( 'View post', 'graphene' ); ?>"></a>
		                <?php endif; ?>

		                <?php if ( $graphene_settings['slider_display_style'] == 'full-post' ) : ?>
		                	<h2 class="slider_post_title"><a href="<?php echo $slider_link_url; ?>"><?php the_title(); ?></a></h2>
		                	<?php the_content(); ?>
	                	<?php else : ?>
						    <div class="carousel-caption">
						    	<h2 class="slider_post_title"><a href="<?php echo $slider_link_url; ?>"><?php the_title(); ?></a></h2>
					    		<div class="slider_post_excerpt"><?php the_excerpt(); ?></div>

					    		<?php do_action( 'graphene_slider_postentry' ); ?>
						    </div>
						<?php endif; ?>
				    </div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
        
        <?php /* The slider navigation */ ?>
		<ol class="carousel-indicators slider_nav">
            <?php for ( $i = 0; $i < $sliderposts->post_count; $i++ ) : ?>
            	 <li data-target="#graphene-slider" <?php if ( $i == 0 ) echo 'class="active"'; ?> data-slide-to="<?php echo $i; ?>"></li>
            <?php endfor; ?>

            <?php do_action( 'graphene_slider_nav' ); ?>
        </ol>

        <a class="left carousel-control" href="#graphene-slider" role="button" data-slide="prev">
        	<i class="fa fa-long-arrow-left"></i>
		    <span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#graphene-slider" role="button" data-slide="next">
			<i class="fa fa-long-arrow-right"></i>
		    <span class="sr-only">Next</span>
		</a>
        
    </div>
    <?php
	do_action( 'graphene_after_slider' );
	
	graphene_reset_excerpt_length();
	$graphene_in_slider = false;
}


/**
 * Create an intermediate function that controls where the slider should be displayed
 */
function graphene_display_slider(){
	global $graphene_settings;
	if ( $graphene_settings['slider_disable'] || ! is_front_page() ) return;

	if ( ! $graphene_settings['slider_position'] ) {
		if ( $graphene_settings['slider_full_width'] ) add_action( 'graphene_before_content-main', 'graphene_slider' );
		else add_action( 'graphene_top_content', 'graphene_slider' );	
	} else {
		if ( $graphene_settings['slider_full_width'] ) add_action( 'graphene_after_content', 'graphene_slider' );
		else add_action( 'graphene_bottom_content', 'graphene_slider', 11 );
	}
}
add_action( 'template_redirect', 'graphene_display_slider' );


if ( ! function_exists( 'graphene_get_slider_image' ) ) :
/**
 * This function determines which image to be used as the slider image based on user
 * settings, and returns the <img> tag of the the slider image.
 *
 * It requires the post's ID to be passed in as argument so that the user settings in
 * individual post / page can be retrieved.
*/
function graphene_get_slider_image( $post_id = NULL, $size = 'thumbnail', $urlonly = false, $default = '' ){
	global $graphene_settings;
	
	// Throw an error message if no post ID supplied
	if ( $post_id == NULL){
		echo '<strong>ERROR:</strong> Post ID must be passed as an input argument to call the function <code>graphene_get_slider_image()</code>.';
		return;
	}
	
	// First get the settings
	$global_setting = ( $graphene_settings['slider_img'] ) ? $graphene_settings['slider_img'] : 'featured_image';
	$local_setting = graphene_get_post_meta( $post_id, 'slider_img' );
	$local_setting = ( $local_setting ) ? $local_setting : '';
	
	// Determine which image should be displayed
	$final_setting = ( $local_setting == '' ) ? $global_setting : $local_setting;
	
	// Build the html based on the final setting
	$html = '';
	if ( $final_setting == 'disabled' ){					// image disabled
	
		return false;
		
	} elseif ( $final_setting == 'featured_image' ){		// Featured Image
	
		if ( has_post_thumbnail( $post_id ) ) :
			if ( $urlonly )
				$html = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );
			else
				$html .= get_the_post_thumbnail( $post_id, $size );
		endif;
		
	} elseif ( $final_setting == 'post_image' ){			// First image in post
		
			$html = graphene_get_post_image( $post_id, $size, '', $urlonly);
		
	} elseif ( $final_setting == 'custom_url' ){			// Custom URL
		
		if ( ! $urlonly ){
			$html .= '';
			if ( $local_setting != '' ) :
				$html .= '<img src="' . esc_url( graphene_get_post_meta( $post_id, 'slider_imgurl' ) ) . '" alt="" />';
			else :
				$html .= '<img src="' . esc_url( $graphene_settings['slider_imgurl'] ) . '" alt="" />';
			endif;
		} else {
			if ( $local_setting != '' ) :
				$html .= esc_url( graphene_get_post_meta( $post_id, 'slider_imgurl' ) );
			else :
				$html .= esc_url(  $graphene_settings['slider_imgurl'] );
			endif;
		}
		
	}
	
	if ( ! $html )
		$html = $default;
	
	// Returns the html
	return $html;
	
}
endif;


/**
 * Returns the posts to be displayed in the slider
 *
 * @return object Object containing the slider posts
 
 * @package Graphene
 * @since 1.6
*/
if ( ! function_exists( 'graphene_get_slider_posts' ) ) :

function graphene_get_slider_posts(){
	global $graphene_settings;
	
	/* Get the category whose posts should be displayed here. */
	$slidertype = ( $graphene_settings['slider_type'] != '' ) ? $graphene_settings['slider_type'] : false;
	
	/* Set the post types to be displayed */
	$slider_post_type = ( in_array( $slidertype, array( 'posts_pages', 'categories' ) ) ) ? array( 'post', 'page' ) : array( 'post' ) ;
	$slider_post_type = apply_filters( 'graphene_slider_post_type', $slider_post_type );
		
	/* Get the number of posts to show */
	$postcount = $graphene_settings['slider_postcount'];
		
	$args = array( 
		'posts_per_page'	=> $postcount,
		'orderby' 			=> 'menu_order date',
		'order' 			=> 'DESC',
		'suppress_filters' 	=> 0,
		'post_type' 		=> $slider_post_type,
		'ignore_sticky_posts' => 1, // otherwise the sticky posts show up undesired
	);		
	
	/* Get the slider content to display */
	if ( $slidertype && $slidertype == 'random' ) {
		$args = array_merge( $args, array( 'orderby' => 'rand' ) );
	}		
	if ( $slidertype && $slidertype == 'posts_pages' && $graphene_settings['slider_specific_posts'] ) {                    
		$post_ids = $graphene_settings['slider_specific_posts'];
		$post_ids = preg_split("/[\s]*[,][\s]*/", $post_ids, -1, PREG_SPLIT_NO_EMPTY); // post_ids are comma separated, the query needs a array
		$post_ids = graphene_object_id( $post_ids );
		$args = array_merge( $args, array( 'post__in' => $post_ids, 'posts_per_page' => -1, 'orderby' => 'post__in' ) );
	}
	if ( $slidertype && $slidertype == 'categories' && is_array( $graphene_settings['slider_specific_categories'] ) ) {        
		$cats = $graphene_settings['slider_specific_categories'];
		$cats = graphene_object_id( $cats, 'category' );
		$args = array_merge( $args, array( 'category__in' => $cats ) );
		
		if ( $graphene_settings['slider_random_category_posts'] ) $args = array_merge( $args, array( 'orderby' => 'rand' ) );
	}

	/* Get only posts with featured image */
	if ( $graphene_settings['slider_with_image_only'] ) {
		$args['meta_query'][] = array(
			'key'		=> '_thumbnail_id',
			'compare'	=> 'EXISTS'
		);
	}

	if ( isset( $args['meta_query'] ) && count( $args['meta_query'] ) > 1 ) $args['meta_query']['relation'] = 'AND';

	
	/* Get the posts */
	$sliderposts = new WP_Query( apply_filters( 'graphene_slider_args', $args ) );
	return apply_filters( 'graphene_slider_posts', $sliderposts );
}

endif;


/**
 * Exclude posts that belong to the categories displayed in slider from the posts listing
 */
function graphene_exclude_slider_categories( $request ){
	global $graphene_settings, $graphene_defaults;

	if ( $graphene_settings['slider_type'] != 'categories' ) return $request;
	if ( is_admin() ) return $request;
	
	if ( $graphene_settings['slider_exclude_categories'] != $graphene_defaults['slider_exclude_categories'] ){
		$dummy_query = new WP_Query();
    	$dummy_query->parse_query( $request );
		
		if ( get_option( 'show_on_front' ) == 'page' && $dummy_query->query_vars['page_id'] == get_option( 'page_on_front' ) ) return $request;
		
		if ( ( $graphene_settings['slider_exclude_categories'] == 'everywhere' ) || 
				$graphene_settings['slider_exclude_categories'] == 'homepage' && $dummy_query->is_home() )
			$request['category__not_in'] =  graphene_object_id( $graphene_settings['slider_specific_categories'], 'category' );
	}
	
	return $request;
}
add_filter( 'request', 'graphene_exclude_slider_categories' );