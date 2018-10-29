<?php
if ( ! GRAPHENE_PLUS ) :
/**
 * Check if current post has custom page layout
 *
 * @return boolean
 */
function graphene_has_custom_layout() {
    return false;
}
endif;


/**
 * Function to display ads from adsense
*/
$adsense_adcount = 1;
$ad_limit = apply_filters( 'graphene_adsense_ads_limit', 3 );
if ( ! function_exists( 'graphene_adsense' ) ) :
	function graphene_adsense(){
		global $adsense_adcount, $ad_limit, $graphene_settings;
		
		if ( $graphene_settings['show_adsense'] && $adsense_adcount <= $ad_limit) : ?>
            <div class="post adsense_single clearfix" id="adsense-ad-<?php echo $adsense_adcount; ?>">
                <?php echo stripslashes( $graphene_settings['adsense_code'] ); ?>
            </div>
            <?php do_action( 'graphene_show_adsense' ); ?>
		<?php endif;
		
		$adsense_adcount++;
		
		do_action( 'graphene_adsense' );
	}
endif;


if ( ! function_exists( 'graphene_addthis' ) ) :
/**
 * Function to display the AddThis social sharing button
*/
function graphene_addthis( $post_id = '', $echo = true ){
	if ( ! $post_id ){
		global $post;
		$post_id = $post->ID;
		if ( ! $post_id ) return;
	}
	
	global $graphene_settings;
	
	// Get the local setting
	$show_addthis_local = graphene_get_post_meta( $post_id, 'show_addthis' );
	$show_addthis_global = $graphene_settings['show_addthis'];
	$show_addthis_page = $graphene_settings['show_addthis_page'];
	
	// Determine whether we should show AddThis or not
	if ( $show_addthis_local == 'show' )
		$show_addthis = true;
	elseif ( $show_addthis_local == 'hide' )
		$show_addthis = false;
	elseif ( $show_addthis_local == '' ){
		if ( ( $show_addthis_global && get_post_type() != 'page' ) || ( $show_addthis_global && $show_addthis_page ) )
			$show_addthis = true;
		else
			$show_addthis = false;
	} else
		$show_addthis = false;
	
	$html = '';

	// Show the AddThis button
	if ( $show_addthis) {
		$html = '<div class="add-this">';
		$html .= stripslashes( $graphene_settings['addthis_code'] );
		$html .= '</div>';

		$html = str_replace( '[#post-url]', esc_attr( get_permalink( $post_id ) ), $html );
		$html = str_replace( '[#post-title]', esc_attr( get_the_title( $post_id ) ), $html );
		$html = str_replace( '[#post-excerpt]', esc_attr( get_the_excerpt() ), $html );
	}

	if ( $echo ) echo apply_filters( 'graphene_addthis', $html );
	else return apply_filters( 'graphene_addthis', $html );
}
endif;


if ( ! function_exists( 'graphene_continue_reading_link' ) ) :
/**
 * Returns a "Continue Reading" link for excerpts
 * Based on the function from the Twenty Ten theme
 *
 * @since Graphene 1.0.8
 * @return string "Continue Reading" link
 */
function graphene_continue_reading_link() {
	global $graphene_in_slider, $graphene_in_stack, $graphene_in_rich_snippet;
	if ( ( ! is_page() || $graphene_in_stack )  && ! $graphene_in_slider && ! $graphene_in_rich_snippet ) {
		$more_link_text = __( 'Continue reading', 'graphene' );
		return '</p><p><a class="more-link btn" href="' . get_permalink() . '">' . $more_link_text . '</a>';
	}
}
endif;


/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and graphene_continue_reading_link().
 * Based on the function from Twenty Ten theme.
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Graphene 1.0.8
 * @return string An ellipsis
 */
function graphene_auto_excerpt_more( $more ) {
	return apply_filters( 'graphene_auto_excerpt_more', ' &hellip; ' . graphene_continue_reading_link() );
}
add_filter( 'excerpt_more', 'graphene_auto_excerpt_more' );


/**
 * Add the Read More link to manual excerpts.
 *
 * @since Graphene 1.1.3
*/
function graphene_manual_excerpt_more( $text ){
	global $post, $graphene_settings, $graphene_in_slider;
	
	if ( $graphene_in_slider ) return $text;
	
	$has_excerpt = has_excerpt();
	if ( $has_excerpt && ! $graphene_settings['show_excerpt_more'] ) return $text;
	
	$has_more = preg_match( '/<!--more(.*?)?-->/', $post->post_content, $matches );
	if ( ! $has_excerpt && ! $has_more ) return $text;
	
	if ( $has_more ) {
		if ( $text != graphene_truncate_words( $text, $graphene_settings['excerpt_length'], '' ) ) return $text;
	}

	if ( stripos( $text, '</p>' ) === false ) return $text;
		
	$text = explode( '</p>', $text );
	$text[count( $text )-2] .= graphene_continue_reading_link();
	$text = implode( '</p>', $text );
	
	return $text;
}
add_action( 'the_excerpt', 'graphene_manual_excerpt_more' );


if ( ! function_exists( 'graphene_posts_nav' ) ) :
/**
 * Posts navigation
 */
function graphene_posts_nav( $args = array() ){
	global $wp_query, $graphene_settings;
	$defaults = apply_filters( 'graphene_posts_nav_defaults', array(
		'current'			=> max( 1, get_query_var( 'paged' ) ),
		'total'				=> $wp_query->max_num_pages,
		'base'				=> '',
		'format'			=> '',
		'add_fragment'		=> '',
		'type'				=> 'post',
		'prev_text'			=> '<i class="fa fa-angle-left"></i>',
		'next_text' 		=> '<i class="fa fa-angle-right"></i>'
	) );
	$args = wp_parse_args( $args, $defaults );
	
	$paginate_args = array(
		'current' 		=> $args['current'],
		'total'			=> $args['total'],
		'prev_text' 	=> $args['prev_text'],
		'next_text' 	=> $args['next_text'],
		'type'      	=> 'array',
		'echo'			=> false,
		'add_fragment'	=> $args['add_fragment'],
	);
	if ( $args['base'] ) $paginate_args['base'] = $args['base'];
	if ( $args['format'] ) $paginate_args['format'] = $args['format'];
	
	
	if ( $args['type'] == 'comment' ) $links = paginate_comments_links( apply_filters( 'graphene_comments_nav_args', $paginate_args ) );
	else $links = paginate_links( apply_filters( 'graphene_posts_nav_args', $paginate_args ) );
	
	if ( $links ) :
	?>
	<div class="pagination-wrapper">
		<ul class="pagination">
			<?php if ( $args['current'] == 1 ) : ?><li class="disabled"><span class="page-numbers"><?php echo $args['prev_text']; ?></span></li><?php endif; ?>
			<?php 
				foreach ( $links as $link ) {
					if ( stripos( $link, 'current' ) !== false ) $link = '<li class="active">' . $link . '</li>';
					else $link = '<li>' . $link . '</li>';
					echo $link;
				}
			?>
		</ul>
	</div>
	<?php
		do_action( 'graphene_posts_nav', $args );
	endif;
}
endif;


if ( ! function_exists( 'graphene_comments_nav' ) ) :
/**
 * Comments pagination
 */
function graphene_comments_nav( $args = array() ){
	
	if ( ! get_option( 'page_comments' ) ) return;
	
	$defaults = apply_filters( 'graphene_comments_nav_defaults', array(
		'current'			=> max( 1, get_query_var('cpage') ),
		'total'				=> get_comment_pages_count(),
		'base'				=> add_query_arg( 'cpage', '%#%' ),
		'format'			=> '',
		'add_fragment' 		=> '#comments',
		'prev_text'			=> __( '&laquo; Prev', 'graphene' ),
		'next_text' 		=> __( 'Next &raquo;', 'graphene' ),
		'type'				=> 'comment',
	) );
	$args = wp_parse_args( $args, $defaults );
	graphene_posts_nav( $args );
}
endif;


/**
 * Add pagination links in pages
 */
function graphene_link_pages(){
	$args = array(
		'before'           => '<div class="page-links"><h4 class="section-title-sm">' . __( 'Pages:', 'graphene' ) . '</h4><ul class="pagination"><li><span class="page-numbers">',
		'after'            => '</span></li></ul></div>',
		'link_before'      => '',
		'link_after'       => '',
		'next_or_number'   => 'number',
		'separator'        => '</span></li><li><span class="page-numbers">',
		'pagelink'         => '%',
		'echo'             => 0
	); 
	$pages_link = wp_link_pages( apply_filters( 'graphene_link_pages_args', $args ) );
	
	$pages_link = explode( '</li>', $pages_link );
	foreach ( $pages_link as $i => $link ) {
		if ( stripos( $link, '<a ' ) === false ) {
			$pages_link[$i] = str_replace( '<li', '<li class="active"', $link );
			break;
		}
	}
	echo implode( '</li>', $pages_link );
}


if ( ! function_exists( 'graphene_post_nav' ) ) :
/**
 * Generates the post navigation links
*/
function graphene_post_nav( $args = array() ){
	global $graphene_settings;

	$defaults = array(
		'hide_post_nav'	=> ( empty( $args ) ) ? $graphene_settings['hide_post_nav'] : false,
		'in_same_cat'	=> $graphene_settings['post_nav_in_term'],
		'format_prev'	=> '<i class="fa fa-arrow-circle-left"></i> %link',
		'format_next'	=> '%link <i class="fa fa-arrow-circle-right"></i>',
		'link'			=> '%title',
		'excluded_cats' => '',
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );
	
	if ( ! is_singular() ) return;
	if ( $hide_post_nav ) return;
	
	if ( $graphene_settings['slider_type'] == 'categories' && $graphene_settings['slider_exclude_categories'] ) {
		$args['excluded_cats'] = $graphene_settings['slider_specific_categories'];
	}
			
	$args = apply_filters( 'graphene_post_nav_args', $args );

	$previous_post_link = get_previous_post_link( $format_prev, $link, $in_same_cat, $excluded_cats );
	$next_post_link = get_next_post_link( $format_next, $link, $in_same_cat, $excluded_cats );

	if ( $previous_post_link || $next_post_link ) :
		?>
		<div class="post-nav post-nav-top clearfix">
			<?php if ( $previous_post_link ) : ?><p class="previous col-sm-6"><?php echo $previous_post_link; ?></p><?php endif; ?>
			<?php if ( $next_post_link ) : ?><p class="next-post col-sm-6"><?php echo $next_post_link; ?></p><?php endif; ?>
			<?php do_action( 'graphene_post_nav' ); ?>
		</div>
		<?php
	endif;
}
endif;


/**
 * Control the excerpt length
*/
function graphene_modify_excerpt_length( $length ) {
	global $graphene_settings;
	/*
	$column_mode = graphene_column_mode();
	if ( $graphene_settings['slider_display_style'] == 'bgimage-excerpt' ){
		if ( strpos( $column_mode, 'three_col' ) === 0)
			return 24;
		if ( strpos( $column_mode, 'two_col' ) === 0)
			return 40;
		if ( $column_mode == 'one_column' )
			return 55;
	}
	*/
	
	return apply_filters( 'graphene_modify_excerpt_length', $graphene_settings['excerpt_length'] );
}
add_filter( 'excerpt_length', 'graphene_modify_excerpt_length' );


/**
 * Set the excerpt length
 *
 * @param int $length Excerpt length
 *
 * @package Graphene
 * @since 1.6
*/
function graphene_set_excerpt_length( $length ){
	if ( ! $length ) return;
	global $graphene_settings;
	$graphene_settings['excerpt_length'] = $length;
}


/**
 * Reset the excerpt length
 *
 * @package Graphene
 * @since 1.6
*/
function graphene_reset_excerpt_length(){
	global $graphene_settings, $graphene_defaults;
	$graphene_settings['excerpt_length'] = $graphene_defaults['excerpt_length'];
}


if ( ! function_exists( 'graphene_get_post_image' ) ) :
/**
 * This function gets the first image (as ordered in the post's media gallery) attached to
 * the current post. It outputs the complete <img> tag, with height and width attributes.
 * The function returns the thumbnail of the original image, linked to the post's 
 * permalink. Returns FALSE if the current post has no image.
 *
 * This function requires the post ID to get the image from to be supplied as the
 * argument. If no post ID is supplied, it outputs an error message. An optional argument
 * size can be used to determine the size of the image to be used.
 *
 * Based on code snippets by John Crenshaw 
 * (http://www.rlmseo.com/blog/get-images-attached-to-post/)
 *
 * @package Graphene
 * @since Graphene 1.1
*/
function graphene_get_post_image( $post_id = NULL, $size = 'thumbnail', $context = '', $urlonly = false ){
	
	/* Don't do anything if no post ID is supplied */
	if ( $post_id == NULL )	return;
	
	/* Get the images */
	$image = graphene_get_best_post_image( $post_id, $size );
	$html = '';
	
	/* Returns generic image if there is no image to show */
	if ( ! $image && $context != 'excerpt' && ! $urlonly ) {
		$html .= apply_filters( 'graphene_generic_slider_img', '' ); // For backward compatibility
		$html .= apply_filters( 'graphene_generic_post_img', '' );
	
	} else {

		/* Build the <img> tag if there is an image */
		if ( ! $urlonly ) {
			if ( $context == 'excerpt' ) $html .= '<div class="excerpt-thumb">';
			$html .= '<a href="' . get_permalink( $post_id ) . '">';
			$html .= wp_get_attachment_image( $image['id'], $size );
			$html .= '</a>';
			if ( $context == 'excerpt' ) $html .= '</div>';
		} else {
			$html = $image['url'];
		}
	}
	
	/* Returns the image HTMl */
	return $html;
}
endif;


/**
 * Print the post image
 */
if ( ! function_exists( 'graphene_post_image' ) ) :
	function graphene_post_image( $post_id = NULL, $size = 'thumbnail', $context = '', $urlonly = false ){
		$html = graphene_get_post_image( $post_id, $size, $context, $urlonly );
		
		if ( $html ) echo $html;
		else echo '<span class="generic-thumb ' . esc_attr( $size ) . '"><i class="fa fa-camera"></i></span>';
	}
endif;


/**
 * Check if there is usable image in the post
 */
function graphene_has_post_image( $post_id = '' ){
	/* Get the post ID if not provided */
	if ( ! $post_id ) $post_id = get_the_ID();
	
	if ( has_post_thumbnail( $post_id ) ) return true;
	if ( get_attached_media( 'image', $post_id ) ) return true;
	if ( get_post_gallery( $post_id, false ) ) return true;
	
	return false;
}


/**
 * Get the best available post image based on requested size
 */
function graphene_get_best_post_image( $post_id = '', $size = 'thumbnail' ){
	global $graphene_settings, $content_width;

	/* Get the requested dimension */
	$size = apply_filters( 'graphene_post_image_size', $size, $post_id );
	if ( ! is_array( $size ) ) {
		$dimension = graphene_get_image_sizes( $size );
		if ( $dimension ) {
			$width = $dimension['width'];
			$height = $dimension['height'];
		} else {
			$width = $content_width;
			$height = floor( 0.5625 * $content_width ); // Defaults to 16:9 aspect ratio
		}
	} else {
		$width = $size[0];
		$height = $size[1];
	}

	/* Make sure there's valid width and height values. Otherwise, bail. */
	if ( ! ( $width && $height ) ) return false;
	
	/* Get the post ID if not provided */
	if ( ! $post_id ) $post_id = get_the_ID();
	
	/* Get and return the cached result if available */
	$cached_images = get_post_meta( $post_id, '_graphene_post_images', true );
	if ( $cached_images ) {
		if ( array_key_exists( $width . 'x' . $height, $cached_images ) ) return $cached_images[$width . 'x' . $height];
	} else {
		$cached_images = array();
	}
	
	$images = array();
	$image_ids = array();
	
	/* Check if the post has a featured image */
	if ( has_post_thumbnail( $post_id ) ) {
		$image_id = get_post_thumbnail_id( $post_id );
		$image = wp_get_attachment_image_src( $image_id, $size );
		if ( $image ) {
			$images[] = array(
				'id'			=> $image_id,
				'featured'		=> true,
				'url'			=> $image[0],
				'width'			=> $image[1],
				'height'		=> $image[2],
				'aspect_ratio'	=> $image[1] / $image[2]
			);
			$image_ids[] = $image_id;
		}
	}
	
	/* Get other images uploaded to the post */
	$media = get_attached_media( 'image', $post_id );
	if ( $media ) {
		foreach ( $media as $image ) {
			$image_id = $image->ID;
			$image = wp_get_attachment_image_src( $image_id, $size );
			if ( $image ) {
				$images[] = array(
					'id'			=> $image_id,
					'featured'		=> false,
					'url'			=> $image[0],
					'width'			=> $image[1],
					'height'		=> $image[2],
					'aspect_ratio'	=> $image[1] / $image[2]
				);
				$image_ids[] = $image_id;
			}
		}
	}
	
	/* Get the images from galleries in the post */
	$galleries = get_post_galleries( $post_id, false );
	if ( $galleries ) {
		foreach ( $galleries as $gallery ) {
			if ( ! array_key_exists( 'ids', $gallery ) ) continue;
			$gallery_images = explode( ',', $gallery['ids'] );
			foreach ( $gallery_images as $image_id ) {
				if ( in_array( $image_id, $image_ids ) ) continue;
				$image = wp_get_attachment_image_src( $image_id, $size );
				if ( $image ) {
					$images[] = array(
						'id'			=> $image_id,
						'featured'		=> false,
						'url'			=> $image[0],
						'width'			=> $image[1],
						'height'		=> $image[2],
						'aspect_ratio'	=> $image[1] / $image[2]
					);
					$image_ids[] = $image_id;
				} 
			}
		}
	}
	
	/* Score the images for best match to the requested size */
	$weight = array(
		'dimension'		=> 1.5,
		'aspect_ratio'	=> 1,
		'featured_image'=> 1
	);
	$target_aspect = $width / $height;
	
	foreach ( $images as $key => $image ) {
		
		$score = 0.0;
		
		/* Aspect ratio */
		if ( $image['aspect_ratio'] > $target_aspect ) $score += ( $target_aspect / $image['aspect_ratio'] ) * $weight['aspect_ratio'];
		else $score += ( $image['aspect_ratio'] / $target_aspect ) * $weight['aspect_ratio'];
		
		/* Dimension: ( width ratio + height ratio ) / 2 */
		$dim_score = min( array( ( $image['width'] / $width ), 1 ) ) + min( array( ( $image['height'] / $height ), 1 ) );
		$score += ( $dim_score / 2 ) * $weight['dimension'];
		
		/* Featured image */
		if ( $image['featured'] ) $score += $weight['featured_image'];
		
		$images[$key]['score'] = $score;
	}
	
	/* Sort the images based on the score */
	usort( $images, 'graphene_sort_array_key_score' );
	
	$images = apply_filters( 'graphene_get_post_image', $images, $size, $post_id );
	
	if ( $images ) {
		$cached_images = array_merge( $cached_images, array( $width . 'x' . $height => $images[0] ) );
		update_post_meta( $post_id, '_graphene_post_images', $cached_images );
		return $images[0];
	} else {
		$cached_images = array_merge( $cached_images, array( $width . 'x' . $height => false ) );
		update_post_meta( $post_id, '_graphene_post_images', $cached_images );
		return false;
	}
}


/**
 * Clear the post image cache when post is updated
 */
function graphene_clear_post_image_cache( $post_id ){
	if ( wp_is_post_revision( $post_id ) ) return;
	
	delete_post_meta( $post_id, '_graphene_post_images' );
}
add_action( 'save_post', 'graphene_clear_post_image_cache' );


/**
 * Improves the WordPress default excerpt output. This function will retain HTML tags inside the excerpt.
 * Based on codes by Aaron Russell at http://www.aaronrussell.co.uk/blog/improving-wordpress-the_excerpt/
*/
function graphene_improved_excerpt( $text ){
	global $graphene_settings, $post;

	if ( ! $graphene_settings['excerpt_html_tags'] ) return $text;
	remove_filter( 'get_the_excerpt', 'wp_trim_excerpt' );
	
	$raw_excerpt = $text;
	if ( '' == $text ) {
		$text = get_the_content( '' );
		$text = strip_shortcodes( $text );
		$text = apply_filters( 'the_content', $text);
		$text = str_replace( ']]>', ']]&gt;', $text);
		
		/* Remove unwanted JS code */
		$text = preg_replace( '@<script[^>]*?>.*?</script>@si', '', $text);
		
		/* Strip HTML tags, but allow certain tags */
		$text = strip_tags( $text, $graphene_settings['excerpt_html_tags'] );

		$excerpt_length = apply_filters( 'excerpt_length', 55);
		$excerpt_more = apply_filters( 'excerpt_more', ' ' . '[...]' );
		$words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
		if ( count( $words) > $excerpt_length ) {
			array_pop( $words);
			$text = implode( ' ', $words);
			$text = $text . $excerpt_more;
		} else {
			$text = implode( ' ', $words);
		}
	}
	
	// Try to balance the HTML tags
	$text = force_balance_tags( $text );
	
	return apply_filters( 'wp_trim_excerpt', $text, $raw_excerpt );
}
add_filter( 'get_the_excerpt', 'graphene_improved_excerpt', 5 );


/**
 * Determine if date should be displayed. Returns true if it should, or false otherwise.
*/
if ( ! function_exists( 'graphene_should_show_date' ) ) :
function graphene_should_show_date(){
	
	// Check post type
	$allowed_posttypes = apply_filters( 'graphene_date_display_posttype', array( 'post' ) );
	if ( ! in_array( get_post_type(), $allowed_posttypes ) )
		return false;
	
	// Check per-post settings
	global $post;
	$post_setting = graphene_get_post_meta( $post->ID, 'post_date_display' );
	if ( $post_setting == 'hidden' )
		return false;
	elseif ( $post_setting != '' )
		return true;
		
	// Check global setting
	global $graphene_settings;
	if ( $graphene_settings['post_date_display'] == 'hidden' )
		return false;
	
	return true;
}
endif;


/**
 * This functions adds additional classes to the post element. The additional classes
 * are added by filtering the WordPress post_class() function.
*/
function graphene_post_class( $classes ){
    global $post;
    
	if ( in_array( graphene_post_date_setting( $post->ID ), array( 'hidden', 'text' ) ) || ! graphene_should_show_date() ) {
		$classes[] = 'nodate';
	}

	// Class for infinite scroll
	$classes[] = 'item-wrap';
		
    // Prints the body class
    return $classes;
}
add_filter( 'post_class', 'graphene_post_class' );


/**
 * Allows post queries to sort the results by the order specified in the post__in parameter. 
 * Just set the orderby parameter to post__in!
 *
 * Based on the Sort Query by Post In plugin by Jake Goldman (http://www.get10up.com)
*/
function graphene_sort_query_by_post_in( $sortby, $thequery ) {
	global $wpdb;
	if ( ! empty( $thequery->query['post__in'] ) && isset( $thequery->query['orderby'] ) && $thequery->query['orderby'] == 'post__in' )
		$sortby = "find_in_set(" . $wpdb->prefix . "posts.ID, '" . implode( ',', $thequery->query['post__in'] ) . "')";
	
	return $sortby;
}
add_filter( 'posts_orderby', 'graphene_sort_query_by_post_in', 9999, 2 );


if ( ! function_exists( 'graphene_post_date' ) ) :
/**
 * Displays the date. Must be used inside the loop.
*/
function graphene_post_date( $id = '' ){
	
	if ( ! $id ) {
		global $post;
		$id = $post->ID;
	}
	
	if ( ! graphene_should_show_date() ) return;
	
	global $graphene_settings;
	$style = graphene_post_date_setting( $id, 'post_date_display' );

	ob_start();
	
	if ( stristr( $style, 'icon' ) ) :
	?>
    	<div class="post-date date alpha <?php if ( $style == 'icon_plus_year' ) echo 'with-year'; ?>">
            <p class="default_date">
            	<span class="month"><?php echo get_the_time( 'M' ); ?></span>
                <span class="day"><?php echo get_the_time( 'd' ) ?></span>
                <?php if ( $style == 'icon_plus_year' ) : ?>
	                <span class="year"><?php echo get_the_time( 'Y' ); ?></span>
                <?php endif; ?>
            </p>
            <?php do_action( 'graphene_post_date' ); ?>
        </div>
    <?php
	endif;
	
	if ( $style == 'text' ) :
	?>
    	<p class="post-date-inline">
            <abbr class="published" title="<?php echo get_the_time( 'c' ); ?>"><?php echo get_the_time( get_option( 'date_format' ) ); ?></abbr>
            <?php do_action( 'graphene_post_date' ); ?>
        </p>
    <?php
	endif;

	return ob_get_clean();
}
endif;


/**
 * Displays the print button
*/
if ( ! function_exists( 'graphene_print_button' ) ) :
function graphene_print_button( $post ){
	$post_type = get_post_type_object( $post->post_type );
	ob_start();
	?>
        <a href="javascript:print();" title="<?php echo esc_attr( sprintf( __('Print this %s', 'graphene' ), strtolower( $post_type->labels->singular_name ) ) ); ?>">
            <i class="fa fa-print"></i>
        </a>
    <?php
    return ob_get_clean();
}
endif;


if ( ! function_exists( 'graphene_parent_return_link' ) ) :
/**
 * Display a link to return to the parent page for child pages
 *
 * @param object $post The post object of the current page
 */
function graphene_parent_return_link( $post = '' ){
	if ( function_exists( 'bcn_display' ) ) return;
	if ( empty( $post ) ) return;
	if ( ! $post->post_parent ) return;
	
	$title = get_the_title( $post->post_parent );
	$permalink = get_permalink( $post->post_parent );
	?>
	<div class="post-nav-top parent-return parent-<?php echo $post->post_parent; ?> clearfix">
		<p class="col-md-12"><i class="fa fa-arrow-circle-up"></i> <?php printf( __( 'Return to %s', 'graphene' ), '<a class="parent-return-link" href="' . $permalink . '">' . $title . '</a>' ); ?></p>
    </div>
    <?php
}
endif;


if ( ! function_exists( 'graphene_tax_description' ) ) :
/**
 * Display the term description in a taxonomy archive page
 */ 
function graphene_tax_description(){
	global $wp_query;
	if ( $wp_query->queried_object ){
		$term = $wp_query->queried_object;
		$tax = $term->taxonomy;
	} else {
		$tax = $wp_query->tax_query->queries[0]['taxonomy'];
		$term = $wp_query->tax_query->queries[0]['terms'][0];
		$term = get_term_by( 'slug', $term, $tax );
	}
	
	if ( ! $term ) return;

	if ( $term->description ) : 
	?>
        <div id="term-desc-<?php echo $term->term_id; ?>" class="<?php echo $tax; ?>-desc term-desc">
            <?php echo $term->description; ?>
        </div>
	<?php endif;
}
endif;


/**
 * Get the custom fields for the post
 *
 * @param int $post_id The ID for the post to get the custom fields for
 * @param string $field The key for the custom field to retrieve.
 * @return string Custom field value | array All custom fields if $field is not defined
 *
 * @package Graphene
 * @since Graphene 1.8
 */
function graphene_get_post_meta( $post_id, $field = '' ){
	global $graphene_post_meta;
	if ( ! is_array( $graphene_post_meta ) ) $graphene_post_meta = array();
	
	if ( ! array_key_exists( $post_id, $graphene_post_meta ) ) {
		
		if ( get_post_meta( $post_id, '_graphene_slider_img' ) ) 
			graphene_convert_meta( $post_id );
		
		$current_post_meta = get_post_meta( $post_id, '_graphene_meta', true );
		if ( ! $current_post_meta ) 
			$graphene_post_meta[$post_id] = graphene_custom_fields_defaults();
		else
			$graphene_post_meta[$post_id] = array_merge( graphene_custom_fields_defaults(), $current_post_meta );
	}
	
	if ( ! $field ) {
		$post_meta = $graphene_post_meta[$post_id];
	} else {
		if ( ! in_array( $field, array( 'slider_imgurl', 'slider_url' ) ) && $graphene_post_meta[$post_id][$field] == 'global' ) 
			$graphene_post_meta[$post_id][$field] = '';
		$post_meta = $graphene_post_meta[$post_id][$field];
	}
	
	return apply_filters( 'graphene_get_post_meta', $post_meta, $post_id, $field );
}


/**
 * Only show posts from specific category in the front page
 */
function graphene_filter_posts_category( $query ){
	if ( ! ( $query->is_home() && $query->is_main_query() ) ) return;
	
	global $graphene_settings;
	if ( empty( $graphene_settings['frontpage_posts_cats'] ) || in_array( 'disabled', $graphene_settings['frontpage_posts_cats'] ) ) return;
	
	$cats = $graphene_settings['frontpage_posts_cats'];
	$query->set( 'category__in', graphene_object_id( $cats, 'category' ) );
}
add_action( 'pre_get_posts', 'graphene_filter_posts_category', 5 );


/**
 * Get the post date display type for each post
 *
 * @package Graphene
 * @since 1.8.3
 */
function graphene_post_date_setting( $id ){
	
	$post_setting = graphene_get_post_meta( $id, 'post_date_display' );
	if ( $post_setting ) return $post_setting;
	
	global $graphene_settings;
	return $graphene_settings['post_date_display'];
}


/**
 * Entry meta
 * 
 * @package Graphene
 * @since 2.0
 */
function graphene_entry_meta(){
	global $graphene_settings, $post;
	$post_id = $post->ID;
	$author_id = $post->post_author;
	$meta = array();


	/* Post author and categories */
	$categories = $author = '';
	if ( ! $graphene_settings['hide_post_cat'] ) {
		$cats = get_the_category(); $categories = array();
		if ( $cats ) {
			foreach ( $cats as $cat ) $categories[] = '<a class="term term-' . esc_attr( $cat->taxonomy ) . ' term-' . esc_attr( $cat->term_id ) . '" href="' . esc_url( get_term_link( $cat->term_id, $cat->taxonomy ) ) . '">' . $cat->name . '</a>';
		}
		if ( $categories ) $categories = '<span class="terms">' . implode( ', ', $categories ) . '</span>';
	}

	if ( ! $graphene_settings['hide_post_author'] && ! is_page() ) {
		$author = '<span class="author"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ) ) . '" rel="author">' . get_the_author_meta( 'display_name' , $author_id ) . '</a></span>';
	}
	
	if ( $categories && $author ) $byline = sprintf( __( 'By %1$s in %2$s', 'graphene' ), $author, $categories );
	elseif ( $categories ) $byline = sprintf( __( 'Filed under %2$s', 'graphene' ), $author, $categories );
	elseif ( $author ) $byline = sprintf( __( 'By %s', 'graphene' ), $author );
	else $byline = false;
	
	if ( $byline ) $meta['byline'] = array( 'class'	=> 'byline', 'meta'	=> $byline );

	/* Inline post date */
	if ( graphene_post_date_setting( get_the_ID() ) == 'text' ) {
		$meta['date'] = array(
			'class'	=> 'date-inline',
			'meta'	=> graphene_post_date()
		);
	}

	/* For printing: the date of the post */
	if ( $graphene_settings['print_css'] && graphene_should_show_date() ) {
		 $meta['date_print'] = array( 
		 	'class'	=> 'print-date',
		 	'meta'	=> graphene_print_only_text( '<em>' . get_the_time( get_option( 'date_format' ) ) . '</em>' )
	 	);
	}

	/* Add a print button only for single pages/posts and if the theme option is enabled. */
	if ( $graphene_settings['print_button'] && is_singular() ) {
		$meta['print_button'] = array(
			'class'	=> 'print',
			'meta'	=> graphene_print_button( $post )
		);
	}

	/* Add a send post by email button if the WP Email plugin is active */
	if ( function_exists( 'wp_email' ) && is_singular() ) {
		$meta['email_button'] = array(
			'class'	=> 'email wp-email-button',
			'meta'	=> email_link( '', '', false )
		);
	}
	
	$meta = apply_filters( 'graphene_entry_meta', $meta, $post_id );
	if ( ! $meta ) return;
	?>
	    <ul class="post-meta">
	    	<?php foreach ( $meta as $item ) : ?>
	        <li class="<?php echo esc_attr( $item['class'] ); ?>"><?php echo $item['meta']; ?></li>
	        <?php endforeach; ?>
	    </ul>
    <?php
	
	do_action( 'graphene_post_meta' );
}


/**
 * Entry footer
 * 
 * @package Graphene
 * @since 2.0
 */
function graphene_entry_footer(){
	global $graphene_settings, $post;
	$post_id = $post->ID;
	$author_id = $post->post_author;
	$meta = array();

	/* Display the post's tags, if there is any */
	if ( $post->post_type != 'page' && ! $graphene_settings['hide_post_tags'] && has_tag() ){
		$tags = array();
		foreach ( get_the_tags() as $tag ) {
			$tags[] = '<a class="term term-tag' . esc_attr( $tag->taxonomy ) . ' term-' . esc_attr( $tag->term_id ) . '" href="' . esc_url( get_term_link( $tag->term_id, $tag->taxonomy ) ) . '">' . $tag->name . '</a>';
		}
		$tags = '<span class="terms">' . implode( ', ', $tags ) . '</span>';

		$meta['post_tags'] = array(
			'class'	=> 'post-tags col-sm-8',
			'meta'	=> '<i class="fa fa-tags" title="' . __( 'Tags', 'graphene' ) . '"></i> ' . $tags
		);
	}
	
	/* Display comments popup link. */
    if ( graphene_should_show_comments() && ! is_singular() ) {
    	$comments_num = get_comments_number();
    	if ( ! $comments_num ) $label = __( 'Leave comment', 'graphene' );
    	else $label = sprintf( _n( '%s comment', '%s comments', $comments_num, 'graphene' ), number_format_i18n( $comments_num ) );

    	$meta['comments_popup_link'] = array(
    		'class'	=> 'comment-link col-sm-4',
    		'meta'	=> '<i class="fa fa-comments"></i> <a href="' . get_comments_link( $post_id ) . '">' . $label . '</a>'
    	);
    }

    /* Display AddThis social sharing button */
	if ( stripos( $graphene_settings['addthis_location'], 'bottom' ) !== false ) { 
		if ( ( is_archive() && $graphene_settings['show_addthis_archive'] ) || is_singular() ) {
			if ( $code = graphene_addthis( $post_id, false ) ) {
				$meta['addthis'] = array(
					'class'	=> 'addthis col-sm-8',
					'meta'	=> $code
				);
			}
		}
	} 
	
	
	$meta = apply_filters( 'graphene_entry_footer', $meta, $post_id );
	if ( ! $meta ) return;
	?>
	    <ul class="entry-footer">
	    	<?php foreach ( $meta as $item ) : ?>
	        <li class="<?php echo esc_attr( $item['class'] ); ?>"><?php echo $item['meta']; ?></li>
	        <?php endforeach; ?>
	    </ul>
    <?php
	
	do_action( 'graphene_post_footer' );
}


if ( ! function_exists( 'graphene_single_author_bio' ) ) :
/**
 * Print out author's bio
 *
 * @package Graphene
 * @since 2.0
 */
function graphene_single_author_bio(){
	global $graphene_settings, $post;
	if ( ! is_singular() || ( $graphene_settings['hide_author_bio'] && ! graphene_has_custom_layout() ) ) return;

	$author_id = $post->post_author;
	?>
    <div class="entry-author">
        <div class="row">
            <div class="author-avatar col-sm-3">
            	<a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" rel="author">
					<?php echo get_avatar( $author_id, 200 ); ?>
                </a>
            </div>

            <div class="author-bio col-sm-9">
                <h3 class="section-title-sm"><?php echo get_the_author_meta( 'display_name', $author_id ); ?></h3>
                <?php echo wpautop( get_the_author_meta( 'description', $author_id ) ); graphene_author_social_links( $author_id ); ?>
            </div>
        </div>
    </div>
    <?php
}
endif;


if ( ! function_exists( 'graphene_page_navigation' ) ) :
/**
 * List subpages of current page
 *
 * @package Graphene
 * @since 2.0
 */
function graphene_page_navigation(){
	global $graphene_settings;
	if ( $graphene_settings['disable_child_pages_nav'] ) return;
	if ( ! is_singular() ) return;
	
	$current = get_the_ID();
	$ancestors = get_ancestors( $current, 'page' );
	if ( $ancestors ) $parent = $ancestors[0];
	else $parent = $current;

	$title = ( $graphene_settings['section_nav_title'] ) ? $graphene_settings['section_nav_title'] : __( 'In this section', 'graphene' );
	
	$args = array(
		'post_type'			=> array( 'page' ),
		'posts_per_page'	=> -1,
		'post_parent'		=> $parent,
		'orderby'			=> 'menu_order title',
		'order'				=> 'ASC'
	);
	$children = new WP_Query( apply_filters( 'graphene_page_navigation_args', $args ) );
	
	if ( $children->have_posts() ) :
	?>
        <div class="widget contextual-nav">
            <h3 class="section-title-sm"><?php echo $title; ?></h3>
            <div class="list-group page-navigation">
            	<a class="list-group-item parent <?php if ( $parent == $current ) echo 'active'; ?>" href="<?php echo esc_url( get_permalink( $parent ) ); ?>"><?php echo get_the_title( $parent ); ?></a>
                <?php while ( $children->have_posts() ) : $children->the_post(); ?>
                <a class="list-group-item <?php if ( get_the_ID() == $current ) echo 'active'; ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                <?php endwhile; ?>
            </div>
        </div>
    <?php 
	endif; wp_reset_postdata(); 
}
endif;
add_action( 'graphene_before_sidebar1', 'graphene_page_navigation' );


/**
 * Remove additional margin added by WordPress on captioned images
 *
 * @package Graphene
 * @since 2.0
 */
function graphene_remove_captioned_image_margin( $atts ) {
    if ( ! empty( $atts['width'] ) ) $atts['width'] -= 10;
    return $atts;
}
add_filter( 'shortcode_atts_caption', 'graphene_remove_captioned_image_margin' );


/**
 * Allow template parts to be filtered
 */
function graphene_get_template_part( $slug, $name = null ) {
	/**
	 * Fires before the specified template part file is loaded.
	 *
	 * The dynamic portion of the hook name, `$slug`, refers to the slug name
	 * for the generic template part.
	 *
	 * @since 3.0.0
	 *
	 * @param string      $slug The slug name for the generic template.
	 * @param string|null $name The name of the specialized template.
	 */
	do_action( "get_template_part_{$slug}", $slug, $name );

	$templates = array();
	$name = (string) $name;
	if ( '' !== $name )
		$templates[] = "{$slug}-{$name}.php";

	$templates[] = "{$slug}.php";

	locate_template( apply_filters( 'graphene_get_template_part', $templates, $slug, $name ), true, false );
}


/**
 * Get the header image
 */
function graphene_header_image(){
	global $post, $graphene_settings;
	$post_id = ( $post ) ? $post->ID : false;

	$alt = '';

	if ( ! $graphene_settings['slider_as_header'] ) {
		$header_img = graphene_get_header_image( $post_id );
	} else {
		$header_img = get_header_image();
		if ( ! $header_img ) return;

		$alt = get_bloginfo( 'name' );
	}

	/* Check if the page uses SSL and change HTTP to HTTPS if true */
    if ( is_ssl() && stripos( $header_img, 'https' ) === false ) {
        $header_img = str_replace( 'http', 'https', $header_img );
    }
    
    echo graphene_get_image_html( $header_img, array( HEADER_IMAGE_WIDTH, $graphene_settings['header_img_height'] ), $alt );
}


/**
 * Display the post's featured image
 */
function graphene_featured_image(){
	global $graphene_settings;
	
	if ( ! has_post_thumbnail() ) return;

	/* Check if featured image size is at least as wide as the content area width */
	global $content_width;
	$featured_image_id = get_post_thumbnail_id();
	$featured_image = wp_get_attachment_metadata( $featured_image_id );
	if ( $featured_image['width'] < $content_width ) return;
	?>
		<div class="featured-image">
			<?php the_post_thumbnail(); ?>
			<?php 
				/* Featured image caption */
				$featured_image = get_post( $featured_image_id );
				if ( $featured_image->post_excerpt ) { 
			?>
				<div class="caption"><i class="fa fa-camera"></i> <?php echo $featured_image->post_excerpt; ?></div>
			<?php } 
				do_action( 'graphene_featured_image' );
			?>
		</div>
	<?php
}