<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) { ?>
			<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
			<?php return;
		}
	}
?>

<!-- Start editing here. -->

<h3 class="heading">~ <span>End</span> <em>Article</em> and <span>Begin</span> <em>Conversation</em> ~</h3>
<?php if ($comments) : ?>
	

	<ul class="commentlist">

	<?php $count = 1; foreach ($comments as $comment) : ?>
		<li class="clearfix<?php if($comment->user_id == $post->post_author) { echo ' author'; } ?>" id="comment-<?php comment_ID() ?>">
			<?php if($comment->user_id === $post->post_author) echo '<div class="byline_expander"></div>'; ?>
			<p class="byline">
				<span class="date"><?php comment_date('F jS, Y') ?></span><br /><em>by</em> <?php comment_author_link() ?>
				<?php if($comment->user_id === $post->post_author) { echo "<br /><strong>The Author</strong>"; } ?>
			</p>
			<div class="comment_num">
				<span class="num"><?=$count?></span>
			</div>
			<div class="text">
				<?php if ($comment->comment_approved == '0') : echo "<p><em>Your comment is awaiting moderation.</em></p>"; endif; ?>
				<?php comment_text() ?>
			</div>
		</li>

	<?php $count++; endforeach; ?>

	</ul>

	<?php else : // this is displayed if there are no comments so far ?>

		<?php if ('open' == $post->comment_status) : ?>
		<p class="notice">There are no comments yet...</p>
		<?php else : ?>
		<p class="notice">Comments are closed.</p>

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<h3 class="heading">~ <span>Now</span> <em>It's Your Turn</em> ~</h3>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p class="notice">You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>

<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

	<p class="notice">Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(); ?>" title="Log out of this account">Log out &raquo;</a></p>

<?php else : ?>

	<p class="clearfix"><label for="author">Your Name:</label> <input type="text" class="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" /></p>

	<p class="clearfix"><label for="email">Your E-mail:</label> <input type="text" class="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" /></p>

	<p class="clearfix"><label for="url">Your Website:</label> <input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" class="text" /></p>

<?php endif; ?>

	<label for="comment">Your Comment:<br /><span class="bracket">{</span></label> <div class="textbox"><textarea name="comment" id="comment" cols="10" rows="10" tabindex="4"></textarea></div>
	<p class="allowed_tags">Feel free to use &lt;strong&gt;, &lt;em&gt;, and &lt;a href=""&gt;</p>

	<p class="clearfix submit">[<input name="submit" type="submit" id="submit" tabindex="5" value="Submit" />]</p>
	<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />

<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; endif; ?>
