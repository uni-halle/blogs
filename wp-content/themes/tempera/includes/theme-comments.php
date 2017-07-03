<?php /*
 * Comments related functions - comments.php
 *
 * @package tempera
 * @subpackage Functions
 */

if ( ! function_exists( 'tempera_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own tempera_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since tempera 0.5
 */
function tempera_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback: ', 'tempera' ); ?><?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'tempera'), ' ' ); ?></p>
	<?php
		break;
		case '' :
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo "<div class='avatar-container' >".get_avatar( $comment, 60 )."</div>"; ?>
			<div class="comment-details">
				<?php printf(  '%s ', sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
					<?php /* translators: 1: date, 2: time */
					printf(  '%1$s '.__('at', 'tempera' ).' %2$s', get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'tempera' ), ' ' );
					?>
				</div><!-- .comment-meta .commentmetadata -->
			</div> <!-- .comment-details -->
		</div><!-- .comment-author .vcard -->

		<?php if ( $comment->comment_approved == '0' ) : ?>
			<span class="comment-await"><em><?php _e( 'Your comment is awaiting moderation.', 'tempera' ); ?></em></span>
			<br />
		<?php endif; ?>


		<div class="comment-body"><?php comment_text(); ?>
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => '<i class="crycon-reply"></i>'.__('Reply','tempera'), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</div>

	</div><!-- #comment-##  -->

	<?php
		break;
	endswitch;
}
endif;

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * @since tempera 0.5
 */
function tempera_remove_recent_comments_style() {
	global $wp_widget_factory;
	if( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) {
		remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
	}
}

add_action( 'widgets_init', 'tempera_remove_recent_comments_style' );

if ( ! function_exists( 'tempera_comments_on' ) ) :
/**
 * Number of comments on loop post if comments are enabled.
 */
function tempera_comments_on() {
	global $temperas;
	if ( comments_open() && ! post_password_required() && $temperas['tempera_blog_show']['comments'] && ! is_single()) :
        echo '<span class="comments-link">';
        comments_popup_link(
            '<i class="crycon-comments crycon-metas" title="' . __('Leave a comment', 'tempera') . '"></i><b>0</b>',
            '<i class="crycon-comments crycon-metas" title="' .
                sprintf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'tempera' ), number_format_i18n( get_comments_number() )) .
             '"></i><b>1</b>',
            '<i class="crycon-comments crycon-metas" title="' .
                sprintf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'tempera' ), number_format_i18n( get_comments_number() )) .
            '"></i><b>%</b>',
            '',
            '' );
        echo '</span>';
	endif;
}
endif;

/**
 * The number of comments title
 */
function tempera_number_comments() { ?>
			<h3 id="comments-title"><i class="crycon-replies" ></i>
				<?php  printf( _n( 'One Comment:', '%1$s Comments:', get_comments_number(), 'tempera' ),
				number_format_i18n( get_comments_number() )); ?>
			</h3>
<?php }

add_action('cryout_before_comments_hook','tempera_number_comments');

/**
 * The comments navigation in case of comments on multiple pages (both top and bottom)
 */
function tempera_comments_navigation() {
if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( '<i class="crycon-reply"></i>'.__('Older Comments', 'tempera' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments', 'tempera' ).'<i class="crycon-forward"></i>' ); ?></div>
			</div> <!-- .navigation -->
<?php endif; // check for comment navigation
}

//add_action('cryout_before_comments_hook','tempera_comments_navigation');
add_action('cryout_after_comments_hook','tempera_comments_navigation');

/*
* Listing the actual comments
*
* Loop through and list the comments. Tell wp_list_comments()
* to use tempera_comment() to format the comments.
* If you want to overload this in a child theme then you can
* define tempera_comment() and that will be used instead.
* See tempera_comment() in tempera/functions.php for more.
 */
function tempera_list_comments() {
					wp_list_comments( array( 'callback' => 'tempera_comment' ) );
			}

add_action('cryout_comments_hook','tempera_list_comments');

/*
 * If there are no comments and comments are closed
 */
function tempera_comments_off() {
if ( ! comments_open() ) : ?>
	<p class="nocomments"><?php _e( 'Comments are closed.', 'tempera' ); ?></p>
<?php endif; // end ! comments_open()
}


add_action('cryout_nocomments_hook','tempera_comments_off');

/*
 * Edit comments form
 * Removing labels and adding them as placeholders
 */

function tempera_comments_form($arg) {
$commenter = wp_get_current_commenter();
$req = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );

$arg =  array(

  'author' =>
    '<p class="comment-form-author"><label for="author">' . __( 'Name', 'tempera' ) .  ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .
    '<input id="author" placeholder="'. __( 'Name', 'tempera' ) .'" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
    '" size="30"' . $aria_req . ' /></p>',

  'email' =>
    '<p class="comment-form-email"><label for="email">' . __( 'Email', 'tempera' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .
    '<input id="email" placeholder="'. __( 'Email', 'tempera' ) . '" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
    '" size="30"' . $aria_req . ' /></p>',

  'url' =>
    '<p class="comment-form-url"><label for="url">' . __( 'Website', 'tempera' ) . '</label>' .
    '<input id="url" placeholder="'. __( 'Website', 'tempera' ) .'" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
    '" size="30" /></p>',

);
	return $arg;
}

add_filter('comment_form_default_fields', 'tempera_comments_form');


function tempera_comments_form_textarea($arg) {
 $arg =
    '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun', 'tempera' ) .
    '</label><textarea placeholder="'. _x( 'Comment', 'noun', 'tempera' ) .'" id="comment" name="comment" cols="45" rows="8" aria-required="true">' .
    '</textarea></p>';
return $arg;
}

add_filter('comment_form_field_comment', 'tempera_comments_form_textarea');

?>
