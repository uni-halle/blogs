<?php
/**
 * Custom template tags for Twenty Fifteen
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

if ( ! function_exists( 'twentyfifteen_comment_nav' ) ) :
/**
 * Display navigation to next/previous comments when applicable.
 *
 * @since Twenty Fifteen 1.0
 */
function twentyfifteen_comment_nav() {
	// Are there comments to navigate through?
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
	?>
	<nav class="navigation comment-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'twentyfifteen' ); ?></h2>
		<div class="nav-links">
			<?php
				if ( $prev_link = get_previous_comments_link( __( 'Older Comments', 'twentyfifteen' ) ) ) :
					printf( '<div class="nav-previous">%s</div>', $prev_link );
				endif;

				if ( $next_link = get_next_comments_link( __( 'Newer Comments', 'twentyfifteen' ) ) ) :
					printf( '<div class="nav-next">%s</div>', $next_link );
				endif;
			?>
		</div><!-- .nav-links -->
	</nav><!-- .comment-navigation -->
	<?php
	endif;
}
endif;

if ( ! function_exists( 'twentyfifteen_entry_meta' ) ) :
/**
 * Prints HTML with meta information for the categories, tags.
 *
 * @since Twenty Fifteen 1.0
 */
function twentyfifteen_entry_meta() {
	if ( is_sticky() && is_home() && ! is_paged() ) {
		printf( '<span class="sticky-post">%s</span>', __( 'Featured', 'twentyfifteen' ) );
	}

	$format = get_post_format();
	if ( current_theme_supports( 'post-formats', $format ) ) {
		printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
			sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'twentyfifteen' ) ),
			esc_url( get_post_format_link( $format ) ),
			get_post_format_string( $format )
		);
	}

	if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			get_the_date(),
			esc_attr( get_the_modified_date( 'c' ) ),
			get_the_modified_date()
		);

		printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
			_x( 'Posted on', 'Used before publish date.', 'twentyfifteen' ),
			esc_url( get_permalink() ),
			$time_string
		);
	}

	if ( 'post' == get_post_type() ) {
		if ( is_singular() || is_multi_author() ) {
			printf( '<span class="byline"><span class="author vcard"><span class="screen-reader-text">%1$s </span><a class="url fn n" href="%2$s">%3$s</a></span></span>',
				_x( 'Author', 'Used before post author name.', 'twentyfifteen' ),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				get_the_author()
			);
		}

		$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'twentyfifteen' ) );
		if ( $categories_list && twentyfifteen_categorized_blog() ) {
			printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Categories', 'Used before category names.', 'twentyfifteen' ),
				$categories_list
			);
		}

		$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'twentyfifteen' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Tags', 'Used before tag names.', 'twentyfifteen' ),
				$tags_list
			);
		}
	}

	if ( is_attachment() && wp_attachment_is_image() ) {
		// Retrieve attachment metadata.
		$metadata = wp_get_attachment_metadata();

		printf( '<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>',
			_x( 'Full size', 'Used before full size attachment link.', 'twentyfifteen' ),
			esc_url( wp_get_attachment_url() ),
			$metadata['width'],
			$metadata['height']
		);
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'twentyfifteen' ), __( '1 Comment', 'twentyfifteen' ), __( '% Comments', 'twentyfifteen' ) );
		echo '</span>';
	}
}
endif;


/**
 * Determine whether blog/site has more than one category.
 *
 * @since Twenty Fifteen 1.0
 *
 * @return bool True of there is more than one category, false otherwise.
 */
function twentyfifteen_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'twentyfifteen_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'twentyfifteen_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so twentyfifteen_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so twentyfifteen_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in {@see twentyfifteen_categorized_blog()}.
 *
 * @since Twenty Fifteen 1.0
 */
function twentyfifteen_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'twentyfifteen_categories' );
}
add_action( 'edit_category', 'twentyfifteen_category_transient_flusher' );
add_action( 'save_post',     'twentyfifteen_category_transient_flusher' );

if ( ! function_exists( 'twentyfifteen_post_thumbnail' ) ) :
/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 *
 * @since Twenty Fifteen 1.0
 */
function twentyfifteen_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
	    $OLGpostId = get_the_ID();
		$OLGattachments = get_children( array( 'post_parent' => $OLGpostId ) );
		$nbOLGattachments = count($OLGattachments);
	$codeforAtt = $nbOLGattachments < 2 ? '<div class="panel">' . get_attachment_highslide($OLGpostId) . '</div>' : get_attachments_list($OLGpostId);
		echo $codeforAtt;
		//echo get_attachments_list($post->ID );
		
		//echo '<div class="panel">' . get_attachment_highslide($post->ID ) . '</div>';
	
	
   
	else : 
	
	
	//in der liste keine thumbnails
	
	endif; // End is_singular()
}
endif;

if ( ! function_exists( 'twentyfifteen_get_link_url' ) ) :
/**
 * Return the post URL.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @since Twenty Fifteen 1.0
 *
 * @see get_url_in_content()
 *
 * @return string The Link format URL.
 */
function twentyfifteen_get_link_url() {
	$has_url = get_url_in_content( get_the_content() );

	return $has_url ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}
endif;

if ( ! function_exists( 'twentyfifteen_excerpt_more' ) && ! is_admin() ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and a 'Continue reading' link.
 *
 * @since Twenty Fifteen 1.0
 *
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function twentyfifteen_excerpt_more( $more ) {
	$link = sprintf( '<a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( __( 'Continue reading %s', 'twentyfifteen' ), '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>' )
		);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'twentyfifteen_excerpt_more' );
endif;

//olg added post thumbnail field
function get_post_thumbnail_field( $field = ‘caption’, $post_id = NULL, $suppress_filters = FALSE ) {

$attachment_id = get_post_thumbnail_id( $post_id );

if ( $attachment_id ) {

$data = wp_prepare_attachment_for_js( $attachment_id );
$field = $data[$field];

if ( $suppress_filters ) return $field;

return apply_filters(‘get_post_thumbnail_field’, $field);
}
return NULL;
}

function the_post_thumbnail_field( $field = ‘caption’, $post_id = NULL, $suppress_filters = FALSE ) {
echo get_post_thumbnail_field( $field, $post_id, $suppress_filters );
}

if ( ! function_exists( 'get_attachments_list' ) ) :
/**
 * Return the attachments as list.
 *
 *
 * @since Twenty Fifteen 1.0
 *
 *
 * @return string HTML list with attachments.
 */
function get_attachments_list($thePostId){
	 $args = array(
   'post_type' => 'attachment',
   'numberposts' => -1,
   'post_status' => null,
   'post_parent' => $thePostId  //$post->ID
  );
	$kiwinav = '';
	$kiwiscroll = '';
	$kiwicount = 0;
	$olgNonImgAttachments = ''; 
  $attachments = get_posts( $args );
     if ( $attachments ) {
        foreach ( $attachments as $attachment ) {
			$data = wp_prepare_attachment_for_js( $attachment->ID );
			$image_caption = apply_filters( 'the_title', $attachment->post_title ) ;
			$image_description = $data['description'] ? $data['description'] : $data['caption'];
			if(wp_attachment_is_image($attachment->ID)){
				$kiwishow = $kiwicount+1;
				$large_image_url = wp_get_attachment_image_src( $attachment->ID, 'large' );
           		$kiwinav .=  "<li><a href='#panel$thePostId-$kiwicount'>$kiwishow</a></li>";
           		$kiwiscroll .= "<div class='scrollContainer'><div class='panel' id='panel$thePostId-$kiwicount' style='width:250px; height:274px'>"
			   . '<a href="' . $large_image_url[0] . '" class="highslide" rel="highslide" >'
			   . wp_get_attachment_image( $attachment->ID, 'post-thumbnail',  false ,array('title' => $image_caption) ) . '</a>
			   <p class="csc-textpic-caption">'. $image_caption . '</p></div></div>';
				$kiwicount++;
			}else{
				$olgNonImgAttachments .=  '<h2> '. $image_caption .'</h2><div class="filelinks filelinks_layout_0">';
				$olgNonImgAttachments .= $image_description ? '<p>' . $image_description . '<br />' : '<p>';
				$olgNonImgAttachments .= '<img class="downloads-icon" src="'. $data['icon'] . '" alt="' . $data['subtype'] . '"><span><a href="' . wp_get_attachment_url($attachment->ID) . '"> ' . $data['filename']  . '</a></span></p></div>';
			}
			
          }
		 $kiwinav = $kiwicount ==1 ? '<div>': '<div class="tx-kiwislider-pi1" style="width:250px; height:274px"><ul class="navigation">' . $kiwinav . '</ul>';
		 return $kiwinav .'<div class="scroll" style="width:249px; height:274px">' . $kiwiscroll . '</div></div>' . $olgNonImgAttachments;
     }
}
endif;

function get_attachment_highslide($thePostId){
	$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $thePostId), 'large' );
	$image_caption = get_post_thumbnail_field('caption', $thePostId  );
	
	$highslideHtml = '<a href="' . $large_image_url[0] . '" class="highslide" rel="highslide" >' .
		get_the_post_thumbnail( $thePostId, 'post-thumbnail' , array('title' => $image_caption)) .
		'</a><p class="csc-textpic-caption">' . $image_caption . '</p>';
	return $highslideHtml;
}

/**
 * Check if an item exists out there in the "ether".
 *
 * @param string $url - preferably a fully qualified URL
 * @return boolean - true if it is out there somewhere
 */
function webItemExists($url) {
    if (($url == '') || ($url == null)) { return false; }
    $response = wp_remote_head( $url, array( 'timeout' => 5 ) );
    $accepted_status_codes = array( 200, 301, 302 );
    if ( ! is_wp_error( $response ) && in_array( wp_remote_retrieve_response_code( $response ), $accepted_status_codes ) ) {
        return true;
    }
    return false;
}

/**
 * Add category image
 *
 * @return string the category image
 */
function olg_category_image() {
$cur_cat_id = get_cat_id( single_cat_title("",false) );
	$imgDir = content_url();

	if ( ($cur_cat_id) && (webItemExists($imgDir .'/uploads/cat_'.$cur_cat_id.'.jpg')) ) {
		$attachment_id = get_attachment_id_from_src ($imgDir .'/uploads/cat_'.$cur_cat_id.'.jpg');
		$large_image_url = wp_get_attachment_image_src( $attachment_id, 'large' );
		$data = wp_prepare_attachment_for_js( $attachment_id );
//$field = $data[$field];
			$image_caption = $data['caption'] ;

	
	$highslideHtml = '<div class="col3"><div class="col3_content"><div class="panel"><a href="' . $large_image_url[0] . '" class="highslide" rel="highslide" >' .
		wp_get_attachment_image($attachment_id, 'thumbnail', false ,array('title' => $image_caption) ).
		'</a><p class="csc-textpic-caption">' . $image_caption . '</p></div></div></div>';
	echo $highslideHtml;

	}
	
}
function get_attachment_id_from_src ($image_src) {

		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
		$id = $wpdb->get_var($query);
		return $id;

	}