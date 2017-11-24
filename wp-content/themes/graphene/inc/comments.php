<?php
if ( ! function_exists( 'graphene_comment' ) ) :
/**
 * Defines the callback function for use with wp_list_comments(). This function controls
 * how comments are displayed.
*/
function graphene_comment( $comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; 
	?>
		<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<div class="row">
				<?php do_action( 'graphene_before_comment' ); ?>
				
				<?php /* Added support for comment numbering using Greg's Threaded Comment Numbering plugin */ ?>
				<?php if (function_exists( 'gtcn_comment_numbering' ) ) {gtcn_comment_numbering( $comment->comment_ID, $args);} ?>
			
				<div class="comment-wrap col-md-12">
					
                    <?php graphene_comment_meta( $comment, $args, $depth ); ?>

					<div class="comment-entry">
						<?php do_action( 'graphene_before_commententry' ); ?>
						
						<?php if ( $comment->comment_approved == '0' ) : ?>
						   <p><em><?php _e( 'Your comment is awaiting moderation.', 'graphene' ) ?></em></p>
						   <?php do_action( 'graphene_comment_moderation' ); ?>
						<?php else : ?>
							<?php comment_text(); ?>
						<?php endif; ?>
						
						<?php do_action( 'graphene_after_commententry' ); ?>
					</div>
				</div>
			
				<?php do_action( 'graphene_after_comment' ); ?>
			</div>
	<?php
}
endif;


/**
 * Customise the comment form
*/
function graphene_comment_form_fields(){
	
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? ' aria-required="true"' : '' );
	$commenter = wp_get_current_commenter();
	
	$fields =  array( 
		'author' => '<div class="form-group col-sm-4">
						<label for="author" class="sr-only"></label>
						<input type="text" class="form-control"' . $aria_req . ' id="author" name="author" value="' . esc_attr( $commenter['comment_author'] ) . '" placeholder="' . esc_attr__( 'Name', 'graphene' ) . '" />
					</div>',
		'email'  => '<div class="form-group col-sm-4">
						<label for="email" class="sr-only"></label>
						<input type="text" class="form-control"' . $aria_req . ' id="email" name="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" placeholder="' . esc_attr__( 'Email', 'graphene' ) . '" />
					</div>',
		'url'    => '<div class="form-group col-sm-4">
						<label for="url" class="sr-only"></label>
						<input type="text" class="form-control" id="url" name="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . esc_attr__( 'Website (optional)', 'graphene' ) . '" />
					</div>',
	);
	
	$fields = apply_filters( 'graphene_comment_form_fields', $fields );
	
	return $fields;
}


/**
 * Add some bootstrap grid layout elements
 */
function graphene_bs_grid_row(){ ?><div class="row"><?php }
function graphene_div_close(){ ?></div><?php }
add_action( 'comment_form_before_fields', 'graphene_bs_grid_row' );
add_action( 'comment_form_after_fields', 'graphene_div_close' );


/**
 * Modify default comment parameters
 */
function graphene_comment_args( $defaults ){
	$args = array(
		'comment_field'	=> 	'<div class="form-group">
								<label for="comment" class="sr-only"></label>
								<textarea name="comment" class="form-control" id="comment" cols="40" rows="10" aria-required="true" placeholder="' . esc_attr__( 'Your message', 'graphene' ) . '"></textarea>
							</div>',
	);
	return array_merge( $defaults, $args );
}
function graphene_comment_clear(){echo '<div class="clear"></div>';}
add_filter( 'comment_form_default_fields', 'graphene_comment_form_fields' );
add_filter( 'comment_form_defaults', 'graphene_comment_args', 5 );
add_filter( 'comment_form', 'graphene_comment_clear', 1000 );


if ( ! function_exists( 'graphene_comment_count' ) ) :
/**
 * Adds the functionality to count comments by type, eg. comments, pingbacks, tracbacks.
 * Based on the code at WPCanyon (http://wpcanyon.com/tipsandtricks/get-separate-count-for-comments-trackbacks-and-pingbacks-in-wordpress/)
 * 
 * In Graphene version 1.3 the $noneText param has been removed
 *
 * @package Graphene
 * @since Graphene 1.3
*/
function graphene_comment_count( $type = 'comments', $oneText = '', $moreText = '' ){
	
	$result = graphene_get_comment_count( $type );

    if( $result == 1  )
		return str_replace( '%', $result, $oneText );
	elseif( $result > 1 )
		return str_replace( '%', $result, $moreText );
	else
		return false;
}
endif;


if ( ! function_exists( 'graphene_get_comment_count' ) ) :
/**
 * Adds the functionality to count comments by type, eg. comments, pingbacks, tracbacks. Return the number of comments, but do not print them.
 * Based on the code at WPCanyon (http://wpcanyon.com/tipsandtricks/get-separate-count-for-comments-trackbacks-and-pingbacks-in-wordpress/)
 * 
 * In Graphene version 1.3 the $noneText param has been removed
 *
 * @package Graphene
 * @since Graphene 1.3
*/
function graphene_get_comment_count( $type = 'comments', $only_approved_comments = true, $top_level = false ){
	if ( ! get_the_ID() ) return;
	if 		( $type == 'comments' ) 	$type_sql = 'comment_type = ""';
	elseif 	( $type == 'pings' )		$type_sql = 'comment_type != ""';
	elseif 	( $type == 'trackbacks' ) 	$type_sql = 'comment_type = "trackback"';
	elseif 	( $type == 'pingbacks' )	$type_sql = 'comment_type = "pingback"';
	
	$type_sql = apply_filters( 'franz_comments_typesql', $type_sql, $type );
	$approved_sql = $only_approved_comments ? ' AND comment_approved="1"' : '';
	$top_level_sql = ( $top_level ) ? ' AND comment_parent="0" ' : '';
        
	global $wpdb;

	$query = $wpdb->prepare( 'SELECT COUNT(comment_ID) FROM ' . $wpdb->comments . ' WHERE ' . $type_sql . $approved_sql . $top_level_sql . ' AND comment_post_ID = %d', get_the_ID() );
    $result = $wpdb->get_var( $query );
	
	return $result;
}
endif;


if ( ! function_exists( 'graphene_should_show_comments' ) ) :
/**
 * Helps to determine if the comments should be shown.
 */
function graphene_should_show_comments() {
    global $graphene_settings, $post;
    if ( ! isset( $post ) ) return;
	
	if ( $graphene_settings['comments_setting'] == 'disabled_completely' )
        return false;
    
	if ( $graphene_settings['comments_setting'] == 'disabled_pages' && get_post_type( $post->ID ) == 'page' )
        return false;
	
	if ( ! is_singular() && $graphene_settings['hide_post_commentcount'] )
		return false;
	
	if ( ! comments_open() && ! is_singular() && get_comments_number( $post->ID ) == 0 )
		return false;
	
    return true;
}
endif;


/**
 * Delete and mark spam link for comments. Show only if current user can moderate comments
 */
 if ( ! function_exists( 'graphene_moderate_comment' ) ) :
function graphene_moderate_comment( $id ) {
	$html = '| <a class="comment-delete-link" title="' . esc_attr__( 'Delete this comment', 'graphene' ) . '" href="' . get_admin_url() . 'comment.php?action=cdc&c=' . $id . '">' . __( 'Delete', 'graphene' ) . '</a>';
	$html .= '&nbsp;';
	$html .= '| <a class="comment-spam-link" title="' . esc_attr__( 'Mark this comment as spam', 'graphene' ) . '" href="' . get_admin_url() . 'comment.php?action=cdc&dt=spam&c=' . $id . '">' . __( 'Spam', 'graphene' ) . '</a> |';

	if ( current_user_can( 'moderate_comments' ) ) echo $html;
}
endif;


if ( ! function_exists( 'graphene_comments_nav' ) ) :
/**
 * Display comments pagination
 *
 * @package Graphene
 * @since 1.9
 */
function graphene_comments_nav(){
	global $graphene_settings, $is_paginated;
	if ( get_comment_pages_count() > 1 && $is_paginated ) : 
	?>
        <div class="comment-nav clearfix">
            <?php if ( function_exists( 'wp_commentnavi' ) && ! $graphene_settings['inf_scroll_comments'] ) : wp_commentnavi(); ?>
                <p class="commentnavi-view-all"><?php wp_commentnavi_all_comments_link(); ?></p>
            <?php else : ?> 
                <p><?php paginate_comments_links(); ?></p>
            <?php endif; do_action( 'graphene_comments_pagination' ); ?>
        </div>
        
        <?php if ( $graphene_settings['inf_scroll_comments'] ) : ?>
			<p class="fetch-more-wrapper"><a href="#" class="fetch-more"><?php _e( 'Fetch more comments', 'graphene' ); ?></a></p>
		<?php endif;
	endif;
}
endif;


if ( ! function_exists( 'graphene_comment_author_link' ) ) :
/**
 * Display comment author's display name if author is registered
 *
 * @package Graphene
 * @since 1.9
 */
function graphene_comment_author_link( $user_id ){
	if ( $user_id ) {
		$author = get_userdata( $user_id );
		$author_link = get_comment_author_url_link( $author->display_name, '' , '' );
	} else {
		$author_link = get_comment_author_link();
	}
	
	return apply_filters( 'graphene_comment_author_link', $author_link );
}
endif;


/**
 * Comment meta
 */
function graphene_comment_meta( $comment, $args = array(), $depth = 1 ){
    global $post; 
    $meta = array();

    /* Avatar */
    if ( ! $comment->comment_type ) {
	    $meta['avatar'] = array(
	        'class' => 'comment-avatar',
	        'meta'  => get_avatar( $comment->comment_author_email, 50 ) . do_action( 'graphene_comment_gravatar' )
	    );
	}

    /* Attributes */
    $meta['attr'] = array(
        'class' => 'comment-attr',
        'meta'  => sprintf( __( '%1$s on %2$s <span class="time">at %3$s</span>', 'graphene' ), '<span class="comment-author">' . graphene_comment_author_link( $comment->user_id ) . '</span>', '<span class="comment-date">' . get_comment_date(), get_comment_time() . '</span>' )
    );
    
    /* Comment by post author */
    if ( $comment->user_id === $post->post_author ) {
        $meta['attr']['meta'] .= '<br /><span class="label label-primary author-cred">' . __( 'Author', 'graphene' ) . '</span>';
    }

	if ( ! $comment->comment_type ) {
	    /* Reply link */
	    $args = array(
			'depth' 		=> $depth, 
			'max_depth' 	=> $args['max_depth'], 
			'reply_text' 	=> __( 'Reply', 'graphene' ),
		);
		$comment_reply = get_comment_reply_link( apply_filters( 'graphene_comment_reply_link_args', $args ) );

		if ( $comment_reply ) {
		    $meta['reply'] = array(
		    	'class'	=> 'comment-reply',
		    	'meta'	=> $comment_reply
		    );
		}
	}
    

    $meta = apply_filters( 'graphene_comment_meta', $meta );
    if ( ! $meta ) return;
    ?>
    <ul class="comment-meta">
        <?php foreach ( $meta as $item ) : ?>
        <li class="<?php echo esc_attr( $item['class'] ); ?>"><?php echo $item['meta']; ?></li>
        <?php endforeach; ?>
    </ul>
    <?php
}


/**
 * Modify the HTML output of the comment reply link
 */
function graphene_comment_reply_link( $link, $args, $comment, $post ){
	$link = str_replace( 'comment-reply', 'btn btn-white btn-xs comment-reply', $link );
	return $link;
}
add_filter( 'comment_reply_link', 'graphene_comment_reply_link', 10, 4 );


/**
 * Modify the HTML output of the cancel comment reply link
 */
function graphene_cancel_comment_reply_link( $formatted_link, $link, $text ){
	$formatted_link = str_replace( '<a', '<a class="btn btn-white btn-sm"', $formatted_link );
	return $formatted_link;
}
add_filter( 'cancel_comment_reply_link', 'graphene_cancel_comment_reply_link', 10, 3 );