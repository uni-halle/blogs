<?php
if ( function_exists('register_sidebars') )
    /*register_sidebars();*/
	register_sidebar();

/* loads legacy.comments.php as comment file when using a old version of Wordpress */
add_filter( 'comments_template', 'legacy_comments' );
function legacy_comments( $file ) {
	if ( !function_exists('wp_list_comments') )
		$file = TEMPLATEPATH . '/legacy.comments.php';
	return $file;
}

/* custom comment design */
function sepcomments($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
	<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-arrow"></div>
		<div class="comment-text">
			<div class="comment-reply">
				<?php edit_comment_link(__('Edit'),'','') ?>
				<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</div>
			<div class="comment-author vcard">
				<?php echo get_avatar($comment,$size='32' ); ?>
				<?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?>
				<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a></div>
			</div>
			<?php comment_text() ?>
			<?php if ($comment->comment_approved == '0') : ?>
				<p><em><?php _e('Your comment is awaiting moderation.') ?></em></p>
			<?php endif; ?>
		</div>
	 </div>
<?php
}

/* custom comment design for printing trackbacks */
function septrackbacks($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
		<?php printf(__('%1$s'), get_comment_date()) ?>: 
		<?php printf(__('%s'), get_comment_author_link()) ?>
<?php
}
?>