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
<?php if ($comments) : ?>
<?php /* Holds comment in moderation, if specified */
if ( $comment->comment_approved == '0' ) { ?>
<p class="alert">Thanks for your comment. It is currently in the moderation cue and will be visible for everyone to read as soon as I have verified it</p>
<?php } ?>
<h3 id="comments">
  <?php comments_number('0 Comments', '1 Comments', '% Comments' );?></h3>
<ol class="commentlist">
  <?php foreach ($comments as $comment) : ?>
  <?php $comment_type = get_comment_type(); ?>
  <?php if($comment_type == 'comment') { ?>
  <li <?php if ($comment->user_id) echo ' class="my_comment" '; ?>id="comment-<?php comment_ID() ?>">
    <div class="clearfloat">
	<div class="gravatar"><?php echo get_avatar( $comment, $size = '42' ); ?></div>
         <div class="commentinfo"><cite>
        <?php comment_author_link() ?>
        </cite> says:
        <?php if ($comment->comment_approved == '0') : ?>
        <em>Your comment is awaiting moderation.</em>
        <?php endif; ?>
        <div class="commentmetadata"><a href="#comment-<?php comment_ID() ?>" title="Permalink for this comment">
          <?php comment_date('F jS, Y') ?>
          at
          <?php comment_time() ?>
          </a>
          <?php edit_comment_link('edit','&nbsp;&nbsp;',''); ?>
        </div>
      </div>
      <!--END COMMENTINFO-->
    </div>
    <div class="commenttext"><?php comment_text() ?></div>
  </li>
  <?php
		/* Changes every other comment to a different class */
		$oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : '';
	?>
  <?php } /* End of is_comment statement */ ?>
  <?php endforeach; /* end for each comment */ ?>
</ol>
<? // Begin Trackbacks ?>
<?php foreach ($comments as $comment) : ?>
<? if ($comment->comment_type == "trackback" || $comment->comment_type == "pingback" || ereg("<pingback />", $comment->comment_content) || ereg("<trackback />", $comment->comment_content)) { ?>
<? if (!$runonce) { $runonce = true; ?>
<h3 id="trackbacks">Trackbacks &amp; Pingbacks</h3>
<ol id="trackbacklist">
  <? } ?>
  <li class="<?php echo $oddcomment; ?>" id="comment-<?php comment_ID() ?>"> 
 
  <cite><?php comment_author_link() ?></cite>
	
	  <div class="commentmetadata">
    <?php comment_date() ?>
    at <a href="#comment-<?php comment_ID() ?>">
    <?php comment_time() ?>
    </a></div> 
	
	
     <div class="commenttext"><?php comment_text() ?></div>
	  
  
	</li>
  <? } ?>
  <?php endforeach; ?>
  <? if ($runonce) { ?>
</ol>
<? } ?>
<? // End Trackbacks ?>
<?php else : // this is displayed if there are no comments so far ?>
<?php if ('open' == $post->comment_status) : ?>
<!-- If comments are open, but there are no comments. -->
<?php else : // comments are closed ?>
<!-- If comments are closed. -->
<p class="nocomments">Comments are closed.</p>
<?php endif; ?>
<?php endif; ?>
<?php if ('open' == $post->comment_status) : ?>
<h3 id="respond">Leave a Reply</h3>
<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>
<?php else : ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
  <?php if ( $user_ID ) : ?>
  <p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a></p>
  <?php else : ?>
  <p>
    <input type="text" name="author" id="user-name" value="<?php echo $comment_author; ?>" size="32" tabindex="1" class="field" />
   
  </p>
  <p>
    <input type="text" name="email" id="user-email" value="<?php echo $comment_author_email; ?>" size="32" tabindex="2" class="field" />
    
  </p>
  <p>
    <input type="text" name="url" id="user-url" value="<?php echo $comment_author_url; ?>" size="32" tabindex="3" class="field" />
  </p>
  <?php endif; ?>
  <p>
    <textarea name="comment" id="user-comment" tabindex="4" class="field" cols="" rows=""></textarea>
  </p>
  <p>
    <input name="submit" type="submit" id="submit" tabindex="5" class="button" value="Leave a Comment" />
    <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
  </p>
  <?php do_action('comment_form', $post->ID); ?>
</form>
<?php endif; // If registration required and not logged in ?>
<?php endif; // if you delete this the sky will fall on your head ?>
