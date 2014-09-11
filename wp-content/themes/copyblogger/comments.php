<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

        if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
	?>
			
		<p class="center"><?php _e("This post is password protected. Enter the password to view comments."); ?><p>
				
<?php	return; } }


	/* Function for seperating comments from track- and pingbacks. */
	function k2_comment_type_detection($commenttxt = 'Comment', $trackbacktxt = 'Trackback', $pingbacktxt = 'Pingback') {
		global $comment;
		if (preg_match('|trackback|', $comment->comment_type))
			return $trackbacktxt;
		elseif (preg_match('|pingback|', $comment->comment_type))
			return $pingbacktxt;
		else
			return $commenttxt;
	}

	$templatedir = get_bloginfo('template_directory');
	
	$comment_number = 1;
?>

<!-- You can start editing here. -->

<?php if (($comments) or ('open' == $post-> comment_status)) { ?>

<div id="comments">

	<h3 class="comment_intro"><?php comments_number('0 comments', '1 comment so far', '% comments' );?> &darr;</h3>

	<?php if ($comments) { ?>

	<dl id="comment_list">

	<?php $count_pings = 1; foreach ($comments as $comment) { ?>
		
		<dt id="comment-<?php comment_ID() ?>"<?php if ($comment->comment_author_email == get_the_author_email()) { ?> class="author"<?php } ?>>
			<span class="comment_num"><a href="#comment-<?php comment_ID() ?>" title="Permalink to this comment">#<?php echo($comment_number); ?></a></span>
			<strong><?php comment_author_link() ?> </strong>on <?php comment_date('m.d.y') ?> at <?php comment_time() ?>
		</dt>
		<dd class="entry<?php if ($comment->comment_author_email == get_the_author_email()) { ?> author<?php } ?>">
			<?php comment_text() ?> 
			<?php if ($comment->comment_approved == '0') : ?>
			<p><strong>Your comment is awaiting moderation.</strong></p>
			<?php endif; ?>
		</dd>
		
	<?php $comment_number++; } /* end for each comment */ ?>
	
	</dl>
		
	<?php } else { // this is displayed if there are no comments so far ?>

		<?php if ('open' == $post-> comment_status) { ?> 
		<!-- If comments are open, but there are no comments. -->
		
		<div class="entry">
			<p>There are no comments yet...Kick things off by filling out the form below.</p>
		</div>

		<?php } else { // comments are closed ?>

		<!-- If comments are closed. -->

		<?php if (is_single) { // To hide comments entirely on Pages without comments ?>
		<div class="entry">
			<p>Like gas stations in rural Texas after 10 pm, comments are closed.</p>
		</div>
		<?php } ?>
	
		<?php } ?>

	<?php } ?>

	<!-- Comment Form -->
	<?php if ('open' == $post-> comment_status) : ?>
	
		<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
			<div class="entry">
				<p class="log_in">You must <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">log in</a> to post a comment.</p>
			</div>
		<?php else : ?>

			<h3 id="respond">Leave a Comment</h3>
			<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="comment_form">
			
			<?php if ( $user_ID ) { ?>	
				<p class="unstyled">Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account') ?>">Logout &raquo;</a></p>
			<?php } ?>
			<?php if ( !$user_ID ) { ?>
				<p><input class="text_input" type="text" name="author" id="author" value="<?php echo $comment_author; ?>" tabindex="1" /><label for="author"><strong>Name</strong></label></p>
				<p><input class="text_input" type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" tabindex="2" /><label for="email"><strong>Mail</strong></label></p>
				<p><input class="text_input" type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" tabindex="3" /><label for="url"><strong>Website</strong></label></p>
			<?php } ?>
				<!--<p><small><strong>XHTML:</strong> You can use these tags: <?php echo allowed_tags(); ?></small></p>-->
			
				<p><textarea class="text_input text_area" name="comment" id="comment" rows="7" tabindex="4"></textarea></p>
			
				<?php if (function_exists('show_subscription_checkbox')) { show_subscription_checkbox(); } ?>
			
				<p>
					<input name="submit" class="form_submit" type="submit" id="submit" tabindex="5" value="Submit" />
					<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
				</p>
		
				<?php do_action('comment_form', $post->ID); ?>
	
			</form>
		<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
</div> <!-- Close #comments container -->
<?php } ?>