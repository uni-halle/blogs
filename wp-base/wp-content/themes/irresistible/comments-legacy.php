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
	$oddcomment = ' alt';
?>

<!-- You can start editing here. -->

<?php if ($comments) : ?>
	<h4 class="txt1"><?php comments_number('No Responses', 'One Response', '% Responses' );?> to &#8220;<?php the_title(); ?>&#8221;</h4>

	<ol class="commentlist">

	<?php foreach ($comments as $comment) : ?>
		
		<li id="comment-<?php comment_ID(); ?>" class="comment-blog<?php echo $oddcomment; ?>">
		
			<?php echo get_avatar( $comment, $size = '48' ); ?>
			<p><?php comment_author_link() ?> Says:</p>
			<p class="txt0"><?php comment_date('F jS, Y') ?> at <?php comment_time('H:i'); ?></p>
			<?php comment_text(); ?>
			
		</li>

	<?php
		/* Changes every other comment to a different class */
		$oddcomment = ( empty( $oddcomment ) ) ? ' alt' : '';
	?>

	<?php endforeach; /* end for each comment */ ?>

	</ol>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<h4 class="txt1">Leave a comment</h4>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>
<?php else : ?>

<form class="form" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="comments">

<?php if ( $user_ID ) : ?>

<p class="logged-in">Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Log out &raquo;</a></p>

<p><textarea class="textarea" name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>

<?php else : ?>

<ol class="fieldset">

	<li class="field">
	
		<label for="author">Name <?php if ($req) echo "(required)"; ?></label>
		<input class="text" type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
	
	</li>

	<li class="field">
	
		<label for="email">Email <?php if ($req) echo "(required)"; ?></label>
		<input class="text" type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
	
	</li>

	<li class="field">
	
		<label for="url">Website</label>
		<input class="text" type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
	
	</li>

	<li class="field">
	
		<label for="comment">Comments</label>
		<textarea class="textarea" name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea>
	
	</li>

</ol>

<?php endif; ?>

<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->

<p class="submit"><input class="btinput" name="submit" type="submit" id="submit" tabindex="5" value="Submit" /></p>

<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />

<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
