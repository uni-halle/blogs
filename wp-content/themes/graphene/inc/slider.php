<?php 
/**
 * Creates the functions that output the slider
*/
function graphene_slider( $args = array() ){
	global $graphene_settings, $graphene_defaults, $graphene_in_slider; $graphene_in_slider = true;

	$defaults = array(
		'title'					=> '',
		'description'			=> '',
		'type' 					=> $graphene_settings['slider_type'],
		'specific_posts' 		=> $graphene_settings['slider_specific_posts'],
		'specific_categories'	=> $graphene_settings['slider_specific_categories'],
		'exclude_categories'	=> $graphene_settings['slider_exclude_categories'],
		'random_category_posts' => $graphene_settings['slider_random_category_posts'],
		'exclude_posts' 		=> $graphene_settings['slider_exclude_posts'],
		'exclude_posts_cats'	=> $graphene_settings['slider_exclude_posts_cats'],
		'postcount' 			=> $graphene_settings['slider_postcount'],
		'with_image_only'		=> $graphene_settings['slider_with_image_only'],
		'img'					=> $graphene_settings['slider_img'],
		'imgurl'				=> $graphene_settings['slider_imgurl'],
		'display'				=> $graphene_settings['slider_display_style'],
		'height' 				=> $graphene_settings['slider_height'],
		'height_mobile'			=> $graphene_settings['slider_height_mobile'],
		'speed'					=> $graphene_settings['slider_speed'],
		'position'				=> $graphene_settings['slider_position'],
		'full_width'			=> $graphene_settings['slider_full_width'],
		'slider_as_header'		=> $graphene_settings['slider_as_header'],
		'id'					=> 'graphene-slider',
		'layout'				=> 'full-stretched'
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	if ( $args['id'] != 'graphene-slider' ) {
		$slider_as_header = false;
		$full_width = false;
		$with_image_only = $graphene_defaults['slider_with_image_only'];
	}
	
	if ( $display != 'full-post' ) graphene_set_excerpt_length( 35 );

	/* Get the slider posts */
	$sliderposts = graphene_get_slider_posts( $args );
	if ( ! $sliderposts->have_posts() ) return;

	/* Generate classes for the slider wrapper */
	$class = array( 'carousel', 'slide', 'carousel-fade' );
	$class[] = 'style-' . $display;
	
	$class = apply_filters( 'graphene_slider_class', $class, $args );
	$class = implode( ' ', $class );

	$slidernav_html = '';
    $i = 0;

	do_action( 'graphene_before_slider' ); 
	?>
    <div class="<?php echo $class; ?> row" data-ride="carousel" id="<?php echo $args['id']; ?>">
		    
	    <?php do_action( 'graphene_before_slider_root' ); ?>
	    <div class="carousel-inner" role="listbox">

	    	<?php 
	    		do_action( 'graphene_before_slideritems' );

	    		while ( $sliderposts->have_posts() ) : 
	    			$sliderposts->the_post();

	    			$style = '';

					/* Background image*/
					if ( $display == 'bgimage-excerpt' || $display == 'banner' ) {
						$image = apply_filters( 'jetpack_photon_url', graphene_get_slider_image( get_the_ID(), 'graphene_slider', true ) );
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

				    	<?php if ( $display == 'bgimage-excerpt' || $display == 'banner' ) : ?>
		                	<a href="<?php echo $slider_link_url; ?>" class="permalink-overlay" title="<?php _e( 'View post', 'graphene' ); ?>"></a>

		                	<?php if ( ! $graphene_settings['slider_disable_caption'] ) : ?>
		                		<div class="carousel-caption">
		                			<?php if ( $slider_as_header ) echo '<div class="container">'; ?>
		                			<div class="carousel-caption-content">
								    	<h2 class="slider_post_title"><a href="<?php echo $slider_link_url; ?>"><?php the_title(); ?></a></h2>
							    		<div class="slider_post_excerpt"><?php the_excerpt(); ?></div>

							    		<?php if ( $display == 'banner' ) graphene_slider_entry_meta(); ?>

							    		<?php do_action( 'graphene_slider_postentry' ); ?>
						    		</div>
						    		<?php if ( $slider_as_header ) echo '</div>'; ?>
						    	</div>
						    <?php endif; ?>
		                <?php endif; ?>

		                <?php if ( $display == 'card' ) : $image = graphene_get_slider_image( get_the_ID(), 'graphene_slider', true ); ?>
		                	<div class="image col-md-5" style="background-image: url(<?php echo $image; ?>);">
		                		<a href="<?php echo $slider_link_url; ?>" class="permalink-overlay" title="<?php _e( 'View post', 'graphene' ); ?>"></a>
		                	</div>

		                	<?php if ( ! $graphene_settings['slider_disable_caption'] ) : ?>
			                	<div class="content col-md-7">
							    	<h2 class="slider_post_title"><a href="<?php echo $slider_link_url; ?>"><?php the_title(); ?></a></h2>
						    		<div class="slider_post_excerpt"><?php the_excerpt(); ?></div>
						    		<?php do_action( 'graphene_slider_postentry' ); ?>

						    		<?php graphene_slider_entry_meta(); ?>
							    </div>
							<?php endif; ?>
	                	<?php endif; ?>


		                <?php if ( $display == 'full-post' ) : ?>
		                	<h2 class="slider_post_title"><a href="<?php echo $slider_link_url; ?>"><?php the_title(); ?></a></h2>
		                	<?php the_content(); ?>
		                	<?php do_action( 'graphene_slider_postentry' ); ?>
	                	<?php endif; ?>
				    </div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
        
        <?php /* The slider navigation */ if ( $sliderposts->post_count > 1 ) : ?>
			<ol class="carousel-indicators slider_nav">
	            <?php for ( $i = 0; $i < $sliderposts->post_count; $i++ ) : ?>
	            	 <li data-target="#<?php echo $args['id']; ?>" <?php if ( $i == 0 ) echo 'class="active"'; ?> data-slide-to="<?php echo $i; ?>"></li>
	            <?php endfor; ?>

	            <?php do_action( 'graphene_slider_nav' ); ?>
	        </ol>

	        <a class="left carousel-control" href="#<?php echo $args['id']; ?>" role="button" data-slide="prev">
	        	<i class="fa fa-long-arrow-left"></i>
			    <span class="sr-only">Previous</span>
			</a>
			<a class="right carousel-control" href="#<?php echo $args['id']; ?>" role="button" data-slide="next">
				<i class="fa fa-long-arrow-right"></i>
			    <span class="sr-only">Next</span>
			</a>
		<?php endif; ?>
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
	if ( $graphene_settings['slider_disable'] ) return;
	if ( graphene_has_custom_layout() && ! $graphene_settings['slider_as_header'] ) return;

	/* Display in pages according to selected setting */
	if ( 'front-page' 	== $graphene_settings['slider_display_in'] && ! is_front_page() ) return;
	if ( 'pages' 		== $graphene_settings['slider_display_in'] && ! is_page() ) return;
	if ( 'posts' 		== $graphene_settings['slider_display_in'] && ! ( is_home() || is_single() || is_archive() ) ) return;

	if ( $graphene_settings['slider_as_header'] ) {
		add_action( 'graphene_header_slider', 'graphene_slider' );
	} elseif ( ! $graphene_settings['slider_position'] ) {
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

function graphene_get_slider_posts( $args = array() ){
	global $graphene_settings;

	$defaults = array(
		'type' 					=> $graphene_settings['slider_type'],
		'specific_posts' 		=> $graphene_settings['slider_specific_posts'],
		'specific_categories'	=> $graphene_settings['slider_specific_categories'],
		'exclude_categories'	=> $graphene_settings['slider_exclude_categories'],
		'random_category_posts' => $graphene_settings['slider_random_category_posts'],
		'exclude_posts' 		=> $graphene_settings['slider_exclude_posts'],
		'exclude_posts_cats'	=> $graphene_settings['slider_exclude_posts_cats'],
		'postcount' 			=> $graphene_settings['slider_postcount'],
		'with_image_only'		=> $graphene_settings['slider_with_image_only'],
		'img'					=> $graphene_settings['slider_img'],
		'imgurl'				=> $graphene_settings['slider_imgurl'],
		'display'				=> $graphene_settings['slider_display_style'],
		'height' 				=> $graphene_settings['slider_height'],
		'height_mobile'			=> $graphene_settings['slider_height_mobile'],
		'speed'					=> $graphene_settings['slider_speed'],
		'position'				=> $graphene_settings['slider_position'],
		'full_width'			=> $graphene_settings['slider_full_width'],
		'slider_as_header'		=> $graphene_settings['slider_as_header'],
		'id'					=> 'graphene-slider',
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	/* Set the post types to be displayed */
	$slider_post_type = ( in_array( $type, array( 'posts_pages', 'categories' ) ) ) ? array( 'post', 'page' ) : array( 'post' ) ;
	$slider_post_type = apply_filters( 'graphene_slider_post_type', $slider_post_type );
		
	/* Get the number of posts to show */
	$postcount = $graphene_settings['slider_postcount'];
		
	$query_args = array( 
		'posts_per_page'	=> $postcount,
		'orderby' 			=> 'menu_order date',
		'order' 			=> 'DESC',
		'suppress_filters' 	=> 0,
		'post_type' 		=> $slider_post_type,
		'ignore_sticky_posts' => 1, // otherwise the sticky posts show up undesired
	);		
	
	/* Get the slider content to display */
	if ( $type == 'random' ) {
		$query_args = array_merge( $query_args, array( 'orderby' => 'rand' ) );
	}		
	if ( $type == 'posts_pages' && $specific_posts ) {                    
		$post_ids = graphene_object_id( explode( ',', $specific_posts ) );

		/* Check if any of the ID is excluded */
		if ( $exclude_posts ) {
			$exclude_post_ids = graphene_object_id( explode( ',', $exclude_posts ) );
			foreach ( $post_ids as $i => $post_id ) {
				if ( in_array( $post_id, $exclude_post_ids ) ) unset( $post_ids[$i] );
			}
		}

		$query_args = array_merge( $query_args, array( 'post__in' => $post_ids, 'posts_per_page' => -1, 'orderby' => 'post__in' ) );
	}
	if ( $type == 'categories' && is_array( $specific_categories ) ) {        
		$specific_categories = graphene_object_id( $specific_categories, 'category' );
		$query_args = array_merge( $query_args, array( 'category__in' => $specific_categories ) );
		
		if ( $random_category_posts ) $query_args = array_merge( $query_args, array( 'orderby' => 'rand' ) );
	}

	/* Exclude posts and pages from slider */
	if ( $exclude_posts ) {
		$exclude_post_ids = graphene_object_id( explode( ',', $exclude_posts ) );
		$query_args = array_merge( $query_args, array( 'post__not_in' => $exclude_post_ids ) );
	}
	if ( $exclude_posts_cats ) {
		$exclude_cats = graphene_object_id( $exclude_posts_cats, 'category' );
		$query_args = array_merge( $query_args, array( 'category__not_in' => $exclude_cats ) );
	}

	/* Get only posts with featured image */
	if ( $with_image_only ) {
		$query_args['meta_query'][] = array(
			'key'		=> '_thumbnail_id',
			'compare'	=> 'EXISTS'
		);
	}

	if ( isset( $query_args['meta_query'] ) && count( $query_args['meta_query'] ) > 1 ) $query_args['meta_query']['relation'] = 'AND';
	
	// disect_it( $query_args );

	/* Get the posts */
	$sliderposts = new WP_Query( apply_filters( 'graphene_slider_args', $query_args, $args ) );
	return apply_filters( 'graphene_slider_posts', $sliderposts, $query_args, $args );
}

endif;


/**
 * Exclude posts that belong to the categories displayed in slider from the posts listing
 */
function graphene_exclude_slider_categories( $request ){
	global $graphene_settings, $graphene_in_slider;

	if ( $graphene_in_slider ) return $request;
	if ( $graphene_settings['slider_type'] != 'categories' ) return $request;
	if ( ! $graphene_settings['slider_exclude_categories'] ) return $request;
	if ( is_admin() ) return $request;

	$dummy_query = new WP_Query();
	$dummy_query->parse_query( $request );

	if ( get_option( 'show_on_front' ) == 'page' && $dummy_query->query_vars['page_id'] == get_option( 'page_on_front' ) ) return $request;
	
	$request['category__not_in'] =  graphene_object_id( $graphene_settings['slider_specific_categories'], 'category' );
	
	return $request;
}
add_filter( 'request', 'graphene_exclude_slider_categories' );


/**
 * Display slider entry meta
 * @since Graphene 2.4
 */
function graphene_slider_entry_meta(){
	global $graphene_settings, $post;
	$post_id = $post->ID;
	$meta = array();

	/* Date */
	$meta['date'] = array(
		'class'	=> 'date',
		'meta'	=> '<i class="fa fa-clock-o"></i> ' . get_the_time( get_option( 'date_format' ) )
	);

	/* Category */
	if ( ! $graphene_settings['hide_post_cat'] ) {
		$cats = get_the_category(); $categories = array();
		if ( $cats ) {
			foreach ( $cats as $cat ) $categories[] = '<a class="term term-' . esc_attr( $cat->taxonomy ) . ' term-' . esc_attr( $cat->term_id ) . '" href="' . esc_url( get_term_link( $cat->term_id, $cat->taxonomy ) ) . '">' . $cat->name . '</a>';
		}
		if ( $categories ) {
			$meta['categories'] = array(
				'class'	=> 'categories',
				'meta'	=> '<i class="fa fa-tags"></i> ' . implode( ', ', $categories )
			);
		}
	}

	/* Author */
	if ( ! $graphene_settings['hide_post_author'] && $post->post_type == 'post' ) {
		$author = '<span class="author"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID', $post->post_author ) ) ) . '" rel="author">' . get_the_author_meta( 'display_name' , $post->post_author ) . '</a></span>';

		$meta['author'] = array(
			'class'	=> 'post-author',
			'meta'	=> '<i class="fa fa-user"></i> ' . $author
		);
	}


	$meta = apply_filters( 'graphene_slider_entry_meta', $meta, $post_id );
	if ( ! $meta ) return;
	?>
	    <ul class="post-meta">
	    	<?php foreach ( $meta as $item ) : ?>
	        <li class="<?php echo esc_attr( $item['class'] ); ?>"><?php echo $item['meta']; ?></li>
	        <?php endforeach; ?>
	    </ul>
    <?php
	
	do_action( 'graphene_slider_entry_meta' );
}


/**
 * Calculate the slider's image width
 */
function graphene_get_slider_image_width(){
	global $graphene_settings;

	/* Slider is being used as header, or full-width slider */
	if ( $graphene_settings['slider_as_header'] || $graphene_settings['slider_full_width'] ) {
		if ( $graphene_settings['container_style'] == 'boxed' ) $slider_width = graphene_grid_width( $graphene_settings['gutter_width']*2, 12 );
		else $slider_width = 1903;

	/* Other cases */
	} else {

		$frontpage_id = ( get_option( 'show_on_front' ) == 'posts' ) ? NULL : get_option( 'page_on_front' );
		$column_mode = graphene_column_mode( $frontpage_id );
		
		if ( strpos( $column_mode, 'two_col' ) === 0 ) $column_mode = 'two_col';
		elseif ( strpos( $column_mode, 'three_col' ) === 0 ) $column_mode = 'three_col';
		else $column_mode = NULL;
		
		if ( $column_mode )	$slider_width = graphene_grid_width( '', $graphene_settings['column_width'][$column_mode]['content'] );
		else $slider_width = graphene_grid_width( '', 12 );
	}

	/* Card display style */
	if ( $graphene_settings['slider_display_style'] == 'card' ) $slider_width = ceil( ( 5 / 12 ) * $slider_width );

	return apply_filters( 'graphene_slider_image_width', $slider_width );
}