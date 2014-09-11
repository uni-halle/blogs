<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (__('Please do not load this page directly. Thanks!','jenny'));

	if ( post_password_required() ) { ?>
	<?php
		return;
	}
?>

<?php if ( have_comments() ) : ?>
<div id="comments" class="post-comments">
	<h2><?php comments_number( __('No Comments', 'jenny'), __( '1 Comment', 'jenny'), __('% Comments', 'jenny') );?></h2>
	
	<?php wp_list_comments('callback=p2h_comment&style=div'); ?>
	
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
	<hr />
	<div class="comment-navigation">
		<ul>
			<li><?php previous_comments_link( __('&laquo; Older Comments','jenny') ); ?></li>
			<li><?php next_comments_link( __('Newer Comments &raquo;', 'jenny') ) ?></li>
		</ul>
	</div>
	<?php endif; // check for comment navigation ?>
</div><!--#comments-->
 	<?php else : // this is displayed if there are no comments so far ?>

	<?php if ( ! comments_open() && !is_page() ) : ?>
	 	<!-- If comments are closed. -->
		<p><?php _e( 'Comments are closed.', 'jenny' ); ?></p>
	<?php endif; ?>
	
<?php endif; ?>


<?php comment_form(
array(
	'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . __( 'Comment', 'jenny' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
	'comment_notes_before' => '<p class="comment-notes">' . __( 'Your email will not be published or shared.' ) . ( $req ? __( ' Required fields are marked <span class="required">*</span>', 'jenny' ) : '' ) . '</p>',
	'comment_notes_after'  => '<p class="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'jenny' ), ' <code>' . allowed_tags() . '</code>' ) . '</p>',
	'id_form'              => 'commentform',
	'id_submit'            => 'submit',
	'title_reply'          => __( 'Leave Your Comment', 'jenny' ),
	'cancel_reply_link'    => __( '(Cancel Reply)', 'jenny' ),
	'label_submit'         => __( 'Submit', 'jenny'),
)
); ?>


<hr />