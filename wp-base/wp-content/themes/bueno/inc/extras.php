<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package bueno
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function bueno_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'bueno_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 */
function bueno_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'bueno_body_classes' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 */
function bueno_enhanced_image_navigation( $url, $id ) {
	if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
		return $url;

	$image = get_post( $id );
	if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
		$url .= '#main';

	return $url;
}
add_filter( 'attachment_link', 'bueno_enhanced_image_navigation', 10, 2 );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 */
function bueno_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'bueno' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'bueno_wp_title', 10, 2 );

/**
 * Show gallery on blog page
 */
function bueno_gallery_sl() {
		global $post;
        $args = array(
            'post_type' => 'attachment', 
            'post_mime_type' => 'image', 
            'numberposts' => 20, 
            'post_status' => null, 
            'post_parent' => $post->ID, 
            'orderby' => 'menu_order', 
            'order' => 'asc'
        );
        $attachments = get_posts( $args );
    ?>
    <?php
        if ( $attachments ) {
        	?>
        <div class="gallery_slider gallery-<?php echo $post->ID; ?>">
			<ul class="slides">
        	<?php
        	$gal_counter = 0;
        	foreach ( $attachments as $attachment ) {
        		$cur_url = wp_get_attachment_url( $attachment->ID, false );

        	?>
        		<li>
	        	<?php
	        		if ( 'one' != of_get_option('blog_page_columns') ) {
	        			$gal_image = wp_get_attachment_image( $attachment->ID, 'featured-thumb' );
	        		} else {
	        			$gal_image = wp_get_attachment_image( $attachment->ID, 'large-thumb' );
	        		}
	        		if ("" == $gal_image) $gal_image = $cur_url;
	        	?>
		        	<a href="<?php echo esc_url( $cur_url ); ?>" class="lightbox_img" data-effect="mfp-zoom-in">
		        		<?php echo $gal_image; ?>
		        	</a>
	        	</li>
	        	<?php
	        	$gal_counter ++;
        	}
        	?>
        	</ul>
        	<div class="gall-holder-<?php echo $post->ID; ?>"></div>
        </div>
		<script type="text/javascript">
		/* <![CDATA[ */
		    jQuery(window).load(function() {
		    	if (!(jQuery.browser.msie && jQuery.browser.version == '8.0')) {
			    	var obj<?php echo $post->ID; ?> = jQuery('.gallery_slider.gallery-<?php echo $post->ID; ?>').clone();
			        jQuery('.gallery_slider.gallery-<?php echo $post->ID; ?>').flexslider({
			            animation: 'fade',
			            slideshow: true,
			            controlNav: false,
			            directionNav: true,
			            controlsContainer: jQuery(".gall-holder-<?php echo $post->ID; ?>")
			        }); 
			        jQuery(".gallery-<?php echo $post->ID; ?> .lightbox_img").magnificPopup({
						type: 'image',
						removalDelay: 500, //delay removal by X to allow out-animation
						callbacks: {
						    beforeOpen: function() {
						      // just a hack that adds mfp-anim class to markup 
						       this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
						       this.st.mainClass = this.st.el.attr('data-effect');
						    }
						},
						gallery:{enabled:true}
					});
					if (jQuery('.two_column_wrapper').length) {
				    	jQuery(window).on("debouncedresize", function( event ) {
				    		jQuery('.gallery_slider.gallery-<?php echo $post->ID; ?>').closest(".gallery_slider.gallery-<?php echo $post->ID; ?>").replaceWith(jQuery(obj<?php echo $post->ID; ?>).clone());
				    		jQuery('.gallery_slider.gallery-<?php echo $post->ID; ?>').flexslider({
					            animation: 'fade',
					            slideshow: true,
					            controlNav: false,
					            directionNav: true,
					            controlsContainer: jQuery(".gall-holder-<?php echo $post->ID; ?>")
					        }); 
					        jQuery(".gallery-<?php echo $post->ID; ?> .lightbox_img").magnificPopup({
								type: 'image',
								removalDelay: 500, //delay removal by X to allow out-animation
								callbacks: {
								    beforeOpen: function() {
								      // just a hack that adds mfp-anim class to markup 
								       this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
								       this.st.mainClass = this.st.el.attr('data-effect');
								    }
								},
								gallery:{enabled:true}
							});
					    })
				   	}
			    } else {
			   		jQuery('.gallery_slider.gallery-<?php echo $post->ID; ?>').flexslider({
			            animation: 'fade',
			            slideshow: true,
			            controlNav: false,
			            directionNav: true,
			            controlsContainer: jQuery(".gall-holder-<?php echo $post->ID; ?>")
			        }); 
			        jQuery(".gallery-<?php echo $post->ID; ?> .lightbox_img").magnificPopup({
						type: 'image',
						removalDelay: 500, //delay removal by X to allow out-animation
						callbacks: {
						    beforeOpen: function() {
						      // just a hack that adds mfp-anim class to markup 
						       this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
						       this.st.mainClass = this.st.el.attr('data-effect');
						    }
						},
						gallery:{enabled:true}
					});
			   	}
		    });
		/* ]]> */
		</script>
        	<?php
        }
}

function bueno_post_format_image() {
	global $post;
	if ( 'image' == get_post_format() ) {
		if ( function_exists('get_post_format_meta') ) {
			$meta = get_post_format_meta( $post->ID );
		}
		if ( ! empty( $meta['image'] ) ) {
			$att_id = bueno_img_html_to_post_id( $meta['image'] );
			if ( 'one' != of_get_option('blog_page_columns') ) {
				$image = wp_get_attachment_image( $att_id, 'featured-thumb' );
			} else {
				$image = wp_get_attachment_image( $att_id, 'large-thumb' );
			}
			$cur_url = wp_get_attachment_url( $att_id, false );
		} elseif ( has_post_thumbnail() ) {
			$post_thumbnail_id = get_post_thumbnail_id();
			if ( !is_singular() ) {
				if ( 'one' != of_get_option('blog_page_columns') ) {
					$image = wp_get_attachment_image( $post_thumbnail_id, 'featured-thumb' );
				} else {
					$image = wp_get_attachment_image( $post_thumbnail_id, 'large-thumb' );
				}
			} else {
				$image = wp_get_attachment_image( $post_thumbnail_id, 'large-thumb' );
			}
			$cur_url = wp_get_attachment_url( $post_thumbnail_id, false );
		} 
		if (isset($cur_url)) {
			$output = '<figure class="featured-thumbnail thumbnail"><a href="'.$cur_url.'" class="lightbox_img single" data-effect="mfp-zoom-in">'.$image.'</a></figure>';
		}
	}
	if ( !isset($output) ) {
		return false;
	} else {
		return $output;
	}
}


add_filter( 'bueno_portfolio_args', 'bueno_portfolio_format', 10, 1 );
function bueno_portfolio_format($args) {
	$portfolio_format = of_get_option('g_portfolio_format');
	$portfolio_format = isset( $portfolio_format ) ? $portfolio_format : "all";
	if ( 'images' == $portfolio_format ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => array( 'post-format-image' )
			)
		);
	}
	return $args;
}

add_filter( 'bueno_portfolio_args', 'bueno_portfolio_cat', 10, 1 );
function bueno_portfolio_cat($args) {
	$portfolio_cat = of_get_option('g_portfolio_cat', 'from_all');
	$portfolio_cat = isset( $portfolio_cat ) ? $portfolio_cat : "from_all";
	if ( 'from_all' != $portfolio_cat ) {
		$args['cat'] = esc_attr($portfolio_cat);
	}
	return $args;
}

function bueno_portfolio_show() {
	wp_reset_query();
	global $post;
	$preview_link = of_get_option( 'g_portfolio_preview_btn', 'false' );
	$post_num = of_get_option( 'g_portfolio_per_page', 8 );
	$post_num = intval($post_num);
	if ( '' == $post_num || 0 == $post_num ) {
		$post_num = 8;
	}
	$default_args = array(
		'posts_per_page' => $post_num,
		'ignore_sticky_posts' => 1
	);
	$args = apply_filters( 'bueno_portfolio_args', $default_args );
	
	$count_portf = 0;
	$portf_query = new WP_Query( $args );
	$bueno_modals = '';
	if ( $portf_query->have_posts() ):
		while ( $portf_query->have_posts() ) :
			$portf_query->the_post();
			if ( has_post_thumbnail() ) {
				?>
				<div class="span3">
					<div class="portf_item">
						<a href="<?php echo get_permalink(); ?>">
						<h5>
							<span><?php the_title(); ?></span>
						</h5>
						<figure class="portfolio-thumbnail">
							<?php the_post_thumbnail('portfolio-thumb'); ?>
						</figure>
						<div class="view_link_wrapper">
							<span class="view_link"><?php _e('View', 'bueno'); ?></span>
						</div>
						</a>
						<?php
							if ( 'true' == $preview_link ) {
							?>
							<a href="#preview-<?php the_id(); ?>" class="preview_link" data-toggle="modal"><?php _e('Preview', 'bueno'); ?></a>
							<?php
							$bueno_modals .= '<div id="preview-' . get_the_id() . '" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="preview-label-' . get_the_id() . '" aria-hidden="true">
							    <div class="modal-header">
							    	<h3 id="preview-label-' . get_the_id() . '">' . get_the_title() . '</h3>
							    </div>
							    <div class="modal-body">' . apply_filters( 'the_excerpt', bueno_string_limit_words( strip_tags(get_the_content()),100 ) ) . '</div>
							    <div class="modal-footer">
								    <a href="#" class="btn" data-dismiss="modal" >' . __( 'Close', 'bueno' ) . '</a>
								    <a href="' . get_permalink() . '" class="btn btn-primary">' . __( 'Read More', 'bueno' ) . '</a>
							    </div>
						    </div>';
							} ?>
					</div>
				</div>
			<?php
			$count_portf++;
			$portf_num = $count_portf % 4;
			if ( 0 == $portf_num ) {
				?>
				<div class="clear"></div>
				<?php
			}
			}
		endwhile;
	endif;
	if ( 'true' == $preview_link ) {
		$GLOBALS['bueno_portfolio_modals'] = $bueno_modals;
		add_action( 'bueno_portfolio_after_content', 'bueno_add_portfolio_modals' );
	}
	wp_reset_postdata();
}

function bueno_add_portfolio_modals() {
	global $bueno_portfolio_modals;

	if ( isset($bueno_portfolio_modals) ) {
		echo $bueno_portfolio_modals;
	}
}

?>