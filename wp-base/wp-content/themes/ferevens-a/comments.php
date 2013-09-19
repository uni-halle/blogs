<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

			<p class="nocomments">This post is password protected. Enter the password to view comments.</p>

			<?php
			return;
		}
	}

	/* This variable is for alternating comment background */
	$oddcomment = 'class="alt" ';
?>

<!-- You can start editing here. -->
<div class="com-list">
<h3 id="comments"><?php comments_number('No Responses', '1 Response', '% Responses' ); ?> to "<?php the_title(); ?>"</h3>

<?php if ($comments) : ?>
<?php foreach ($comments as $comment) : ?>

<div class="com-entry" id="comment-<?php comment_ID(); ?>"><div class="com-entry-bot">

<div class="com-con">
<p class="com-name"><?php $commentNumber++; echo $commentNumber; ?> | <?php comment_author_link(); ?></p>
<p class="com-date"><a href="#comment-<?php comment_ID() ?>"><?php comment_date('F jS, Y') ?> at <?php comment_time() ?></a>  <?php edit_comment_link('edit','|&nbsp;',''); ?></p>
<p class="com-avt"><img src="<?php gravatar("R", 30); ?>" alt="Avatar" /></p>
<?php if ($comment->comment_approved == '0') : ?>
<p><em>Your comment is awaiting moderation.</em></p>
<?php endif; ?>									
<?php comment_text() ?>
</div>

</div></div>
<?php endforeach; ?>
					
 
<?php elseif ('open' != $post->comment_status) : ?>
<p>Comments are closed.</p>
<?php endif; ?>					
</div>	

<?php if ('open' == $post->comment_status) : ?>

<div class="com-form"><div class="com-form-bot">
<h3 id="respond">Comment Form</h3>

<form id="comment-form" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">
<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>

<?php else : ?>
<?php if ( $user_ID ) : ?>
<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a></p>

<?php else : ?>
<p>
<label for="comment-name">Your Name <strong><?php if ($req) echo "(required)"; ?></strong></label>
<input id="comment-name" value="<?php echo $comment_author; ?>" name="author"  type="text" style="width: 230px;" />
</p>
<p>
<label for="comment-name">Your Email <strong><?php if ($req) echo "(required)"; ?></strong></label>
<input id="comment-email" name="email" value="<?php echo $comment_author_email; ?>" type="text" style="width: 230px;" />
</p>
<p>
<label for="comment-name">Your URL</label>
<input id="comment-url" name="url" value="<?php echo $comment_author_url; ?>" type="text" style="width: 230px;" />
</p>
<?php endif; ?>
<p><textarea name="comment" cols="40" rows="10" style="width: 380px;"></textarea></p>

<button name="submit" type="submit">Post</button>
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />			
<?php endif; ?>
</form>

</div></div>					
<?php endif; ?>
