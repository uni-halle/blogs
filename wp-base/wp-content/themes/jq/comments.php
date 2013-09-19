<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>
<!-- You can start editing here. -->
<?php if ( have_comments() ) : ?>
<h2 id="comment-header"><?php comments_number('No comments', '1 comment', '% comments'); ?></h2>
<div id="comments_box">
<ul><?php wp_list_comments('type=all&callback=mytheme_comments'); ?></ul>
<?php paginate_comments_links(); ?>
</div>
 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->
		<h3>No comments yet.</h3>
	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<h3>Comments are closed.</h3>
	<?php endif; ?>
<?php endif; ?>

<!-- comment form -->
<?php if ( comments_open() ) : ?>

<div id="respond">

<h2><?php comment_form_title( 'Leave a Reply', 'Leave a Reply to %s' ); ?></h2>

<div class="cancel-comment-reply">
	<?php cancel_comment_reply_link(); ?>
</div>

<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<p>You must be <a href="<?php echo wp_login_url( get_permalink() ); ?>">logged in</a> to post a comment.</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( is_user_logged_in() ) : ?>

<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p>

<?php else : ?>

<label for="author">Name<?php if ($req) echo "*"; ?></label>
<input type="text" name="author" id="author" class="text" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />

<label for="email">Mail<?php if ($req) echo "*"; ?> (will not be published) </label>
<input type="text" name="email" id="email" class="text" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />

<label for="url">Website</label>
<input type="text" name="url" id="url" class="text" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" />


<?php endif; ?>

<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->

<label for="message">Your Comment</label>
<p><textarea name="comment" id="comment" cols="58" rows="10" tabindex="4"></textarea></p>

<input name="submit" type="submit" id="submit" class="submit" tabindex="5" value="Submit Comment" />
<?php comment_id_fields(); ?>

<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>
