<div class="post-text">

<?php
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die (__('Please do not load this page directly. Thanks!') );

if ( post_password_required() ) { ?>
	<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','disciplede') ;?></p>
<?php
	return;
}

if ( have_comments() ) : ?>
	<h2 id="comments"><?php comments_number(__('No Responses','disciplede'), __('One Response','disciplede'), __('% Responses','disciplede') );?></h2>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>

	<ol class="commentlist">
		<?php wp_list_comments('type=comment&callback=sepcomments'); ?>
	</ol>

	<ul class="trackbacklist">
		<?php wp_list_comments('type=pingback&callback=septrackbacks'); ?>
	</ul>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<h2><?php comment_form_title(__( 'Leave a Reply','disciplede'), __('Leave a Reply to %s','disciplede') ); ?></h2>

<div id="respond">

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p><?php _e('You must be','disciplede') ;?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e('logged in','disciplede') ;?></a> <?php _e('to post a comment','disciplede') ;?>.</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<div class="cancel-comment-reply">
	<small><?php cancel_comment_reply_link(); ?></small>
</div>

<?php if ( $user_ID ) : ?>

<p><?php _e('Logged in as','disciplede') ;?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account"><?php _e('Log out &raquo;','disciplede') ;?></a></p>

<?php else : ?>

<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="25" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
<label for="author"><b><?php _e('Name','disciplede') ;?></b> <?php if ($req) echo "(__('required','disciple')"; ?></label></p>

<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="25" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
<label for="email"><b><?php _e('Mail','disciplede') ;?></b> (<?php _e('will not be published','disciplede') ;?>) <?php if ($req) echo "(__('required','disciplede)"; ?></label></p>

<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="25" tabindex="3" />
<label for="url"><b><?php _e('Website','disciplede') ;?></b></label></p>

<?php endif; ?>

<p><textarea name="comment" id="comment" rows="10" tabindex="4"></textarea></p>

<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" />
<?php comment_id_fields(); ?>
</p>
<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>
</div>

<p><small><strong>XHTML:</strong> <?php _e('You can use these tags','disciplede') ;?>: <code><?php echo allowed_tags(); ?></code></small></p>

<?php endif; // if you delete this the sky will fall on your head ?>
</div>