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
<div id="comments">
<?php if ($comments) : ?>

  <h4 id="comments">Comments</h4>

	<?php foreach ($comments as $comment) : ?>

    <div class="commentEntry">
      <?php echo get_avatar( $comment, $size = '48' ); ?>
        <div class="commentContent" id="comment-<?php comment_ID() ?>">
        <?php if ($comment->comment_approved == '0') : ?>
  			<em>Your comment is awaiting moderation.</em>
  			<?php endif; ?>
  			
        <?php comment_text() ?>
        
       <div class="commentMeta">
        posted by <cite><?php comment_author_link() ?></cite> on <a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('m.d.y') ?></a> at <?php comment_time() ?> <?php edit_comment_link('edit','&nbsp;&nbsp;',''); ?>
      </div>
     </div>
    </div>
      
	<?php endforeach; /* end for each comment */ ?>
  
  <?php if ('closed' == $post->comment_status) : ?>
	  <div class="nocomments">Comments are closed.</div>
		<?php endif; ?>
  	

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<div class="nocomments">Comments are closed.</div>

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>


<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">


  <div class="leaveComment">
    <?php if ( $user_ID ) : ?>

    <p class="loggedin">Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a></p>


    <?php endif; ?>
    
    <fieldset>
      <legend><span>Leave a Comment</span></legend>
      <div class="commentForm">
        <label>Name: <em>Required</em> <input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" /></label>
        <label>Email: <em>Required, not published</em> <input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" /></label>
        <label>Homepage: <input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" /></label>
        <label>Comment:
        <textarea name="comment" id="comment" cols="50" rows="20"></textarea></label>
        <input type="submit" value="Post Comment" /> <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
      </div>
    </fieldset>
  </div>

<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>


<?php endif; // if you delete this the sky will fall on your head ?>
  </div>
