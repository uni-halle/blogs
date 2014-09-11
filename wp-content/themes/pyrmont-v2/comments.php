<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'pyrmont_v2'); ?></p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( comments_open() ) : ?>
<div id="comments">
	<h3><?php comments_number(__('No Comment.', 'pyrmont_v2'), __('1 comment so far', 'pyrmont_v2'), __('% comments so far', 'pyrmont_v2'));?></h3>
	<span class="add_your_comment"><a href="#respond"><?php _e('Add Your Comment', 'pyrmont_v2'); ?></a></span>
	<div class="clear"></div>
</div>
<?php endif; ?>

<?php if ( have_comments() ) : ?>

<ol class="commentlist">
	<?php
		wp_list_comments('type=all&callback=mytheme_comment');
	?>
</ol>

<!--paged comment goes here-->
<div class="comments_navi">
	<div class="alignleft"><?php previous_comments_link() ?></div>
	<div class="alignright"><?php next_comments_link() ?></div>
	<div class="clear"></div>
</div>

<?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e('Comments are closed.', 'pyrmont_v2'); ?></p>

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<div id="respond">

<div class="h3_cancel_reply">
	<h3><?php comment_form_title( __('Your Comment', 'pyrmont_v2'), 'Re: %s \'s comment' ); ?></h3>
	<span class="cancel-comment-reply"><?php cancel_comment_reply_link(__('Change mind? Click here to cancel.', 'pyrmont_v2')); ?></span>
	<div class="clear"></div>
</div>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p><?php _e('You must be <a href="<?php echo get_option(\'siteurl\'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.','pyrmont_v2');?></p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p><? _e('Logged in as','pyrmont_v2');?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account','pyrmont_v2');?>"><?php _e('Log out &raquo;','pyrmont_v2');?></a></p>

<div class="input_area"><textarea name="comment" id="comment" cols="60" rows="5" tabindex="1" class="message_input" onkeydown="if((event.ctrlKey&&event.keyCode==13)){document.getElementById('submit').click();return false};" ></textarea></div>

<?php else : ?>

<div class="input_area"><textarea name="comment" id="comment" cols="60" rows="5" tabindex="1" class="message_input" onkeydown="if((event.ctrlKey&&event.keyCode==13)){document.getElementById('submit').click();return false};" ></textarea></div>
	
<div class="user_info">
	<div class="single_field">
		<label for="author" class="desc"><?php _e('Name', 'pyrmont_v2'); ?><?php if ($req) _e('<abbr title="Required">*</abbr> :','pyrmont_v2'); ?></label>
		<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="2" class="comment_input" <?php if ($req) echo "aria-required='true'"; ?> />
	</div>
	
	<div class="single_field">
		<label for="email" class="desc"><?php _e('Email', 'pyrmont_v2'); ?><?php if ($req) _e('<abbr title="Required, would not be published">*</abbr> :','pyrmont_v2'); ?></label>
		<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="3" class="comment_input" <?php if ($req) echo "aria-required='true'"; ?> />
	</div>

	<div class="single_field">
		<label for="url" class="desc"><?php _e('URI :', 'pyrmont_v2'); ?></label>
		<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="4" class="comment_input" />
	</div>
	<div class="clear"></div>
</div>

<?php endif; ?>

<!--<p><small><strong>XHTML:</strong> <?php _e('You can use these tags:','pyrmont_v2');?> <code><?php echo allowed_tags(); ?></code></small></p>-->

<div class="submit_button">
	<input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit','pyrmont_v2');?>" class="button" />
	<?php comment_id_fields(); ?>
	<?php do_action('comment_form', $post->ID); ?>
	<div class="clear"></div>
</div>

</form>

<?php endif; // If registration required and not logged in ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>
