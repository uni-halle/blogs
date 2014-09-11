<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
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
	$oddcomment = ' alt ';
	global $style;
?>

<!-- You can start editing here. -->

<?php if ($comments) : ?>

	<h3 id="comments" class="post">
		<?php comments_number('<span class="'.$style.'">0</span> Comments', '<span class="'.$style.'">1</span> Comment', '<span class="'.$style.'">%</span> Comments' );?>
	</h3>
	
	<ol class="commentlist">
		<?php foreach ($comments as $comment) : ?>
			<li id="comment-<?php comment_ID() ?>" class="clearfix<?php echo $oddcomment; ?>">
				<div class="comment-author">
					<?php echo get_avatar( $comment, 56 ); ?>
					<span class="<?php echo $style; ?> weight-bold verdana block large"><?php comment_author_link() ?></span>
					<span class="small verdana light">on <?php comment_date('F jS, Y') ?></span>
				</div>
				<div class="comment-text">
					<?php if ($comment->comment_approved == '0') : ?>
						<em class="light">Your comment is awaiting moderation.</em>
					<?php endif; ?>
					
					<span class="medium"><?php comment_text() ?></span>
				</div>
			</li>
			<?php $oddcomment = ( empty( $oddcomment ) ) ? ' alt ' : ''; ?>
		<?php endforeach; /* end for each comment */ ?>
	</ol>
	<br /><br />
 <?php else : ?>
	<?php if ('open' == $post->comment_status) : ?>
	<?php else : // comments are closed ?>
		<h2 class="nocomments post">Comments are closed.</h2>
	<?php endif; ?>
<?php endif; ?>

<?php if ('open' == $post->comment_status) : ?>

<h3 id="respond" class="post">Leave a Reply</h3>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Log out &raquo;</a></p>

<?php else : ?>

<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" class="text" />
<label for="author"><small>Name <?php if ($req) echo "(required)"; ?></small></label></p>

<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" class="text" />
<label for="email"><small>Mail (will not be published) <?php if ($req) echo "(required)"; ?></small></label></p>

<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" class="text" />
<label for="url"><small>Website</small></label></p>

<?php endif; ?>

<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->

<p><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4" class="text textarea"></textarea></p>

<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit" class="btn submit btn-<?php echo $style; ?>" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>
<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
