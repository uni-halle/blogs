<?php 
/*
 * Main loop related functions
 *
 * @package tempera
 * @subpackage Functions
 */


 /**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function tempera_excerpt_length( $length ) {
	global $temperas;
	return absint( $temperas['tempera_excerptwords'] );
}
add_filter( 'excerpt_length', 'tempera_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since tempera 0.5
 * @return string "Continue Reading" link
 */
function tempera_excerpt_continuereading_link() {
	global $temperas;
	return '<p class="continue-reading-button"> <a class="continue-reading-link" href="'. esc_url( get_permalink() ) . '">' . wp_kses_post( $temperas['tempera_excerptcont'] ) . '<i class="crycon-right-dir"></i></a></p>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and tempera_excerpt_continuereading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since tempera 0.5
 * @return string An ellipsis
 */
function tempera_excerpt_dots( $more ) {
	global $temperas;
	return wp_kses_post( $temperas['tempera_excerptdots'] );
}
add_filter( 'excerpt_more', 'tempera_excerpt_dots' );


/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since tempera 0.5
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function tempera_excerpt_morelink( $output ) {
	if ( ! is_attachment() ) {
		$output .= tempera_excerpt_continuereading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'tempera_excerpt_morelink', 20 );


/**
 * Adds a "Continue Reading" link to post excerpts created using the <!--more--> tag.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the the_content_more_link filter hook.
 *
 * @since tempera 0.5
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function tempera_more_link($more_link, $more_link_text) {
	global $temperas;
	$new_link_text = wp_kses_post( $temperas['tempera_excerptcont'] );
	if (preg_match("/custom=(.*)/",$more_link_text,$m) ) {
		$new_link_text = $m[1];
	};
	$more_link = str_replace($more_link_text, $new_link_text . '<i class="crycon-right-dir"></i>', $more_link);
	$more_link = str_replace('more-link', 'continue-reading-link', $more_link);
	return $more_link;
}
add_filter('the_content_more_link', 'tempera_more_link', 10, 2);


/**
 * Allows post excerpts to contain HTML tags
 * @since tempera 1.8.7
 * @return string Excerpt with most HTML tags intact
 */

function tempera_excerpt_html($text) {
     global $temperas;
     $temperas['tempera_excerptwords'] = absint( $temperas['tempera_excerptwords'] );
     $temperas['tempera_excerptcont'] = wp_kses_post( $temperas['tempera_excerptcont'] );
     $temperas['tempera_excerptdots'] = wp_kses_post( $temperas['tempera_excerptdots'] );

     $raw_excerpt = $text;
     if ( '' == $text ) {
         //Retrieve the post content.
         $text = get_the_content('');

         //Delete all shortcode tags from the content.
         $text = strip_shortcodes( $text );

         $text = apply_filters('the_content', $text);
         $text = str_replace(']]>', ']]&gt;', $text);

         $allowed_tags = '<a>,<img>,<b>,<strong>,<ul>,<li>,<i>,<h1>,<h2>,<h3>,<h4>,<h5>,<h6>,<pre>,<code>,<em>,<u>,<br>,<p>';
         $text = strip_tags($text, $allowed_tags);

         $words = preg_split("/[\n\r\t ]+/", $text, $temperas['tempera_excerptwords'] + 1, PREG_SPLIT_NO_EMPTY);
         if ( count($words) > $temperas['tempera_excerptwords'] ) {
             array_pop($words);
			 $words[] = $temperas['tempera_excerptdots'];
             $text = implode(' ', $words);
         } else {
             $text = implode(' ', $words);
         }
     }
     return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}
if ($temperas['tempera_excerpttags']=='Enable') {
	remove_filter( 'get_the_excerpt', 'tempera_excerpt_trim_chars' ); 
 	remove_filter( 'get_the_excerpt', 'tempera_excerpt_morelink' ); 
 	add_filter( 'get_the_excerpt', 'tempera_excerpt_html' , 8 ); 
	add_filter( 'get_the_excerpt', 'do_shortcode'); 
}


/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Tempera's style.css.
 *
 * @since tempera 0.5
 * @return string The gallery style filter, with the styles themselves removed.
 */
function tempera_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'tempera_remove_gallery_css' );


function tempera_meta_author() {
	global $post;
	if ( is_single() && get_the_author_meta( 'user_url', $post->post_author ) ) {
		echo '<link rel="author" href="' . esc_url( get_the_author_meta( 'user_url', $post->post_author) ) . '">';
	}
}
add_action ('wp_head','tempera_meta_author');


if ( ! function_exists( 'tempera_meta_before' ) ) :
/**
 * Prints HTML with meta information for the current post—date/time and author.
 *
 * @since tempera 0.5
 */
function tempera_meta_before() {
    global $temperas;

	// If single page take appropiate settings
	if ( is_single() ) {
		$temperas['tempera_blog_show'] = $temperas['tempera_single_show'];
	}

	// Post Author
	$output = "";
	if ( $temperas['tempera_blog_show']['author'] ) {
		$output .= sprintf( '<span class="author vcard" ><i class="crycon-author crycon-metas" title="' . __( 'Author ','tempera'). '"></i>
					<a class="url fn n" rel="author" href="%1$s" title="%2$s">%3$s</a></span>',
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					sprintf( esc_attr ( __( 'View all posts by %s', 'tempera' ) ), get_the_author() ),
					get_the_author()
		);
	}

    // Post date/time
	if ( $temperas['tempera_blog_show']['date'] || $temperas['tempera_blog_show']['time'] ) {
		$separator = "";
		$date = "";
		$time = "";

	if ( $temperas['tempera_blog_show']['date'] && $temperas['tempera_blog_show']['time'] ) {
		$separator = " - ";
	}
	if ( $temperas['tempera_blog_show']['date'] ) {
		$date = get_the_date();
	}
	if ( $temperas['tempera_blog_show']['time'] ) {
		$time = esc_attr( get_the_time() );
	}

	$output.= '<span><i class="crycon-time crycon-metas" title="' . __( "Date", "tempera" ) . '"></i>
				<time class="onDate date published" datetime="' . get_the_time( 'c' ) . '">
					<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $date . $separator . $time . '</a>
				</time>
			   </span><time class="updated"  datetime="' . get_the_modified_date( 'c' ) . '">' . get_the_modified_date() . '</time>';
	}
	
	// Post categories
    if ( $temperas['tempera_blog_show']['category'] &&  get_the_category_list() ) {
		$output .= '<span class="bl_categ"><i class="crycon-folder-open crycon-metas" title="' . __( "Categories", "tempera" ).'"></i>' . 
			get_the_category_list( ', ' ) . '</span> ';
	}
	echo $output;

}; // tempera_meta_before()
endif;


if ( ! function_exists( 'tempera_meta_after' ) ) :
/**
 * Prints HTML with tags information for the current post. ALso adds the edit button.
 *
 * @since tempera 0.9
 */
function tempera_meta_after() {
	global $temperas;

	$tag_list = get_the_tag_list( '', ', ' );
    if ( $tag_list && ($temperas['tempera_blog_show']['tag']) ) { ?>
		<span class="footer-tags"><i class="crycon-tag crycon-metas" title="<?php _e( 'Tags','tempera'); echo '"> </i>'.$tag_list; ?> </span>
    <?php }
	edit_post_link( __( 'Edit', 'tempera' ), '<span class="edit-link crycon-metas"><i class="crycon-edit  crycon-metas"></i> ', '</span>' );
	cryout_post_footer_hook();  

}; // tempera_meta_after()
endif;

function tempera_meta_infos() {
	global $temperas;
	switch($temperas['tempera_metapos']):

		case "Bottom":
			add_action('cryout_post_after_content_hook','tempera_meta_before',10);
			add_action('cryout_post_after_content_hook','tempera_meta_after',11);
			add_action('cryout_post_after_content_hook','tempera_comments_on',12);
		break;

		case "Top":
		if( !is_single()) {
			add_action('cryout_post_meta_hook','tempera_meta_before',10);
			add_action('cryout_post_meta_hook','tempera_meta_after',11);
			add_action('cryout_post_meta_hook','tempera_comments_on',12);
		}
		break;

	endswitch;
}
add_action('wp_head','tempera_meta_infos');


// Remove category from rel in category tags.
add_filter( 'the_category', 'tempera_remove_category_tag' );
add_filter( 'get_the_category_list', 'tempera_remove_category_tag' );


function tempera_remove_category_tag( $text ) {
     $text = str_replace('rel="category tag"', 'rel="tag"', $text); return $text;
}


/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since tempera 0.5
 */
if ( ! function_exists( 'tempera_posted_in' ) ) :
function tempera_posted_in() {
	global $temperas;

	if ($temperas['tempera_single_show']['tag'] || $temperas['tempera_single_show']['bookmark']) :
		// Retrieves tag list of current post, separated by commas.
		$posted_in = '';
		$tag_list = get_the_tag_list( '', ', ' );
		if ( $tag_list && $temperas['tempera_single_show']['tag'] ) {
			$posted_in .=  '<span class="footer-tags"><i class="crycon-tag crycon-metas" title="'.__( 'Tagged','tempera').'"></i>&nbsp; %2$s.</span>';
		}
		if ($temperas['tempera_single_show']['bookmark'] ) {
			$posted_in .= '<span class="bl_bookmark"><i class="crycon-bookmark crycon-metas" title="'.__(' Bookmark the permalink','tempera').'"></i> <a href="%3$s" title="'.__('Permalink to','tempera').' %4$s" rel="bookmark"> '.__('Bookmark','tempera').'</a>.</span>';
		}

		// Prints the string, replacing the placeholders.
		printf(
			$posted_in,
			get_the_category_list( ', ' ),
			$tag_list,
			esc_url( get_permalink() ),
			the_title_attribute( 'echo=0' )
		);
	endif;
}; // tempera_posted_in()
endif;

/**
 * Display navigation to next/previous pages when applicable
 */
if ( ! function_exists( 'tempera_content_nav' ) ) :
function tempera_content_nav( $nav_id ) {
	global $wp_query;
	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $nav_id; ?>" class="navigation">
			<div class="nav-previous"><?php next_posts_link( __( '<i class="meta-nav-prev"></i> <span>Older posts</span>', 'tempera' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( '<span>Newer posts</span> <i class="meta-nav-next"></i>', 'tempera' ) ); ?></div>
		</nav><!-- #nav-above -->
	<?php endif;
}; // tempera_content_nav()
endif; // tempera_content_nav

// Custom image size for use with post thumbnails
if ($temperas['tempera_fcrop']) add_image_size( 'custom', $temperas['tempera_fwidth'], $temperas['tempera_fheight'], true );
						   else add_image_size( 'custom', $temperas['tempera_fwidth'], $temperas['tempera_fheight'] );


function cryout_echo_first_image( $postID ) {
	$args = array(
    	'numberposts' => 1,
    	'orderby'=> 'none',
    	'post_mime_type' => 'image',
    	'post_parent' => $postID,
    	'post_status' => 'any',
    	'post_type' => 'any'
	);

	$attachments = get_children( $args );
	//print_r($attachments);

	if ($attachments) {
		foreach ($attachments as $attachment) {
			$image_attributes = wp_get_attachment_image_src( $attachment->ID, 'custom' )  ? wp_get_attachment_image_src( $attachment->ID, 'custom' ) : wp_get_attachment_image_src( $attachment->ID, 'custom' );
			return esc_url( $image_attributes[0] );
		}
	}
}; // cryout_echo_first_image()

/**
 * Adds a post thumbnail and if one doesn't exist the first image from the post is used.
 */
if ( ! function_exists( 'tempera_set_featured_thumb' ) ) :
function tempera_set_featured_thumb() {
    global $post;
	global $temperas;

    $image_src = cryout_echo_first_image($post->ID);
    if ( function_exists("has_post_thumbnail") && has_post_thumbnail() && ($temperas['tempera_fpost']=='Enable') )
		the_post_thumbnail( 'custom', array("class" => "align" . strtolower( $temperas['tempera_falign'] ) . " post_thumbnail" ) );

    elseif ( ($temperas['tempera_fpost']=='Enable') && ($temperas['tempera_fauto']=="Enable") && $image_src )
		echo '<a title="' . the_title_attribute('echo=0') . '" href="' . esc_url( get_permalink() ) . '" >
					<img width="' . $temperas['tempera_fwidth'].'" title="" alt="" class="align' . strtolower( $temperas['tempera_falign'] ) . ' post_thumbnail" src="' . $image_src . '">
			  </a>' ;

};
endif; // tempera_set_featured_thumb

if ( ($temperas['tempera_fpost']=='Enable') && $temperas['tempera_fpostlink'] ) add_filter( 'post_thumbnail_html', 'tempera_thumbnail_link', 10, 2 );

/**
 * The thumbnail gets a link to the post's page
 */
function tempera_thumbnail_link( $html, $post_id ) {
     $html = '<a href="' . esc_url( get_permalink( $post_id ) ) . '" title="' . esc_attr( get_post_field( 'post_title', $post_id ) ) . '">' . $html . '</a>';
     return $html;
}; // tempera_thumbnail_link()

// FIN