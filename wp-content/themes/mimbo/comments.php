<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ( __('Please do not load this page directly. Thanks!','Mimbo') );
if ( post_password_required() ) { ?>

<p class="nocomments">
  <?php _e('This post is password protected. Enter the password to view comments.','Mimbo'); ?>
</p>
<?php
	return;
}

// Show the comments
if ( have_comments() ) : ?>
<h3 id="comments">
  <?php comments_number(__('0 Comments','Mimbo'),  __('1 Comment','Mimbo'), '% '.__('Comments','Mimbo').'');?>
</h3>
<ol class="commentlist" id="singlecomments">
  <?php wp_list_comments('type=comment&callback=mytheme_comment'); ?>
</ol>

<? // Begin Trackbacks ?>
<?php foreach ($comments as $comment) : ?>
<?php if ($comment->comment_type == "trackback" || $comment->comment_type == "pingback" || ereg("<pingback />", $comment->comment_content) || ereg("<trackback />", $comment->comment_content)) { ?>
<?php if (!$runonce) { $runonce = true; ?>

<h3 id="trackbacks"><?php _e('Trackbacks','Mimbo'); ?></h3>
<ol id="trackbacklist">
  <?php } ?>
  <li class="<?php echo $oddcomment; ?>" id="comment-<?php comment_ID() ?>"> <cite>
    <?php comment_author_link() ?>
    </cite> </li>
  <?php } ?>
  <?php endforeach; ?>
  <?php if ($runonce) { ?>
</ol>
<?php } ?>
<? // End Trackbacks ?>

<div class="clearfloat" id="pagination">
  <?php previous_comments_link(__('&laquo;Older Comments','Mimbo')); ?>
  <?php next_comments_link(__('Newer Comments&raquo;','Mimbo')); ?>
</div>

<?php else : // this is displayed if there are no comments so far ?>
<?php if ('open' == $post->comment_status) : ?>
<?php else : ?>
<p class="nocomments">
  <?php _e('Comments are closed.','Mimbo'); ?>
</p>
<?php endif; ?>
<?php endif; ?>

<?php if ('open' == $post-> comment_status) : ?>
<h3 id="respond">
  <?php _e('Leave a Response','Mimbo'); ?>
</h3>
<div id="cancel-comment-reply">
  <?php cancel_comment_reply_link(__('Cancel Reply','Mimbo')); ?>
</div>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>
  <?php _e('You must be','Mimbo'); ?>
  <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">
  <?php _e('logged in','Mimbo'); ?>
  </a>
  <?php _e('to post a comment.','Mimbo'); ?>
  <?php else : ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
  <?php if ( $user_ID ) : ?>
  <p>
    <?php _e('Logged in as','Mimbo'); ?>
    <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a> &bull; <a href=" <?php echo wp_logout_url($redirect); ?>">
    <?php _e('Log out','Mimbo'); ?>
    &raquo;</a></p>
  <?php else : ?>
  <p>
    <input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
    <label for="author">
    <?php _e('Name','Mimbo'); ?>
    <?php if($req) : ?>
    (
    <?php _e('required','Mimbo'); ?>
    )
    <?php endif; ?>
    </label>
  </p>
  <p>
    <input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
    <label for="email">
    <?php _e('Email','Mimbo'); ?>
    <?php if($req) : ?>
    (
    <?php _e('required','Mimbo'); ?>
    )
    <?php endif; ?>
    </label>
  </p>
  <p>
    <input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
    <label for="url">
    <?php _e('Website','Mimbo'); ?>
    </label>
  </p>
  <?php endif; ?>
  <?php comment_id_fields(); ?>
  <input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" />
  <p>
    <textarea name="comment" id="comment" cols="10" rows="10" tabindex="4"></textarea>
  </p>
  <?php if (get_option("comment_moderation") == "1") { ?>
  <p>
    <?php _e('Please note: comment moderation is enabled and may delay your comment. There is no need to resubmit your comment.','Mimbo'); ?>
  </p>
  <?php } ?>
  <p>
    <input name="submit" type="submit" id="submit" class="button" tabindex="5" value="<?php _e('Submit Comment','Mimbo'); ?>" />
  </p>
  <?php do_action('comment_form', $post->ID); ?>
</form>
<?php endif; ?>
<?php endif; ?>
