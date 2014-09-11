<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<div id="comments_wrap">

<?php if ( have_comments() ) : ?>

	<h3 id="comments"><?php comments_number('No Responses', 'One Response', '% Responses' );?> to &#8220;<?php the_title(); ?>&#8221;</h3>

	<ol class="commentlist">
	<?php wp_list_comments('avatar_size=35&callback=custom_comment'); ?>
	</ol>    

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
		<div class="fix"></div>
	</div>
	<br />
 
<?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>

	<?php endif; ?>

<?php endif; ?>

</div> <!-- end #comments_wrap -->

<?php if ('open' == $post->comment_status) : ?>

<div id="respond">

<h3 class="lc"><?php comment_form_title( 'Leave a Reply', 'Leave a Reply to %s' ); ?></h3>
<div class="cancel-comment-reply">
	<p><small><?php cancel_comment_reply_link(); ?></small></p>
</div>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>

<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>

<?php else : ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(); ?>" title="Log out of this account">Logout &raquo;</a></p>

<?php else : ?>
<label for="author">Username <?php if ($req) echo "(required)"; ?> :<br />
<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="27" tabindex="1" /></label> 



<label for="email">Email <?php if ($req) echo "(required)"; ?> :<br />
<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="27" tabindex="2" /></label> 



<label for="url">Web Site :<br />
<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="27" tabindex="3" /></label> 


<?php endif; ?>

<!--<p><small><strong>XHTML:</strong> You can use these tags: <?php echo allowed_tags(); ?></small></p>-->


<label for="comment">Comment :<br /></label> 
<textarea name="comment" id="comment" cols="50" rows="8" tabindex="4"></textarea>



<input name="submit" type="submit" id="submit" tabindex="5" value="Submit" class="sb" />



<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
<?php comment_id_fields(); ?>
<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If logged in ?>

<div class="fix"></div>
</div> <!-- end #respond -->

<?php endif; // if you delete this the sky will fall on your head ?>
