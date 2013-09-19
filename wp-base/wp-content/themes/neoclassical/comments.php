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
	$alt_comment = '';
?>

<!-- You can start editing here. -->

<?php if (($comments) or ('open' == $post-> comment_status)) { ?>

<div id="comments">

	<p class="comments_intro"><span><?php comments_number('0 comments', '1 comment', '% comments' );?></span></p>
	
	<dl id="comment_list">

	<?php if ($comments) { ?>

		<?php foreach ($comments as $comment) { ?>
	
		<dt class="comment<?php if (k2_comment_type_detection() != "Comment") { echo(' trackback'); } if ($alt_comment == 'alt') { echo (' alt'); } if ($comment->comment_author_email == get_the_author_email()) { echo (' author'); } ?>" id="comment-<?php comment_ID() ?>">
			<span class="comment_num"><a href="#comment-<?php comment_ID() ?>" title="Permalink to this comment" rel="nofollow"><?php echo($comment_number); ?></a></span>
			<strong><?php comment_author_link() ?> </strong><span class="comment_time">{ <?php comment_date('m.d.y') ?> at <?php comment_time() ?> }</span>
		</dt>
		<dd class="comment<?php if (k2_comment_type_detection() != "Comment") { echo(' trackback'); } if ($alt_comment == 'alt') { echo (' alt'); } if ($comment->comment_author_email == get_the_author_email()) { echo (' author'); } ?>" id="comment-<?php comment_ID() ?>">
			<div class="format_text">
				<?php comment_text() ?> 
				<?php if ($comment->comment_approved == '0') : ?>
				<p><strong>Your comment is awaiting moderation.</strong></p>
				<?php endif; ?>
			</div>
		</dd>
		
		<?php if ($alt_comment == 'alt') { $alt_comment = ''; } else $alt_comment = 'alt'; ?>
		
		<?php $comment_number++; } /* end for each comment */ ?>
	
	</dl>
		
	<?php } else { // this is displayed if there are no comments so far ?>

		<?php if ('open' == $post-> comment_status) { ?> 
		<!-- If comments are open, but there are no comments. -->
		<dt class="comment"><strong>There are no comments yet...</strong></dt>
		<dd class="comment">
			<div class="format_text">
				<p>Kick things off by filling out the form below.</p>
			</div>
		</dd>

		<?php } else { // comments are closed ?>

		<!-- If comments are closed. -->

		<?php if (is_single) { // To hide comments entirely on Pages without comments ?>
		<dt class="comment"><strong>Sorry, we're <em>closed</em></strong></dt>
		<dd class="comment">
			<div class="format_text">
				<p>Like gas stations in rural Texas after 10 pm, comments are closed.</p>
			</div>
		</dd>
		<?php } ?>
	
		<?php } ?>

	</dl>

	<?php } ?>


	<!-- Comment Form -->
	<?php if ('open' == $post-> comment_status) : ?>
	
		<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
	
			<p class="unstyled">You must <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>" rel="nofollow">log in</a> to post a comment.</p>
	
		<?php else : ?>

			<p id="respond"><span>Leave a Comment</span></p>

			<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="comment_form">

			<?php if ( $user_ID ) { ?>
	
				<p class="unstyled">Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php" rel="nofollow"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account') ?>" rel="nofollow">Logout &rarr;</a></p>
	
			<?php } ?>
			<?php if ( !$user_ID ) { ?>
				<p><input class="text_input" type="text" name="author" id="author" value="<?php echo $comment_author; ?>" tabindex="1" /><label for="author">Name</label></p>
				<p><input class="text_input" type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" tabindex="2" /><label for="email">E-mail</label></p>
				<p><input class="text_input" type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" tabindex="3" /><label for="url">Website</label></p>
			<?php } ?>
				<!--<p><small><strong>XHTML:</strong> You can use these tags: <?php echo allowed_tags(); ?></small></p>-->
			
				<p><textarea class="text_input text_area" name="comment" id="comment" rows="7" tabindex="4"></textarea></p>
			
				<?php if (function_exists('show_subscription_checkbox')) { show_subscription_checkbox(); } ?>
			
				<p class="submit_p">
					<input name="submit" class="form_submit" type="submit" id="submit" src="<?php bloginfo('template_url') ?>/images/submit_comment.gif" tabindex="5" value="Submit" />
					<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
				</p>
		
				<?php do_action('comment_form', $post->ID); ?>
	
			</form>

		<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
</div> <!-- Close #comments container -->
<?php } ?>