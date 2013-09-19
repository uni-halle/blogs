<?php if ( !empty($post->post_password) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) : ?>
<div style="padding:0px 0px 0px 90px;">
<p><?php _e('Enter your password to view comments.'); ?></p>
</div>
<?php return; endif; ?>
<div class="post-announcement" id="comments">
	<?php comments_number(__('There are 0 comments to this post.'), __('There is 1 comment to this post.'), __('There are % comments to this post.')); ?>
</div>

<!--Check if it is a comment-->
<?php if ( $comments ) : ?>
<?php foreach ($comments as $comment) : ?>
<?php $comment_type = get_comment_type(); ?>
<?php if($comment_type == 'comment') { ?>


<div class="comment-container" id="comment-<?php comment_ID() ?>">
	<div class="comment-left">
		<?php echo get_avatar( $comment, 62 ); ?>
	</div>
	<div class="comment-right">
		<div class="comment-header">
			<span class="comment-header-title">
				<?php comment_author_link() ?> had this to say,
			</span>
			<?php comment_date('M j, Y') ?> @ <?php comment_time() ?>
		</div>
		<div class="comment-content">
			<?php comment_text() ?>
			<?php edit_comment_link('Edit','<p><span class="bold">Admin: </span>','</p>'); ?>
		</div>
	</div>
	<div class="clear"></div>
</div>

<?php } else { $trackback = true; }?> <!--Or else it must be a trackback-->

<?php endforeach; ?>

<?php if ($trackback == true) { ?>

<div class="post-announcement">
	Trackback/Pingbacks to this post.
</div>

<div style="width:550px; float:right; clear:right; overflow:hidden; display:block; padding:0px 0px 10px;margin:0px 0px 30px 0px;background:url(<?php bloginfo('template_url');?>/images/dots.gif) bottom center repeat-x;" class="comment-content">
	  <ol>
		  <?php foreach ($comments as $comment) : ?>
		  <?php $comment_type = get_comment_type(); ?>
		  <?php if($comment_type != 'comment') { ?>
		  <li><?php comment_author_link() ?></li>
		  <?php } ?>
		  <?php endforeach; ?>
	  </ol>
	  <div class="clear"></div>
</div>
<?php } ?>

<?php else : ?>

<?php endif; ?>

<?php if ( comments_open() ) : ?>

<div class="post-announcement" id="respond">
	Add Your Comment.
</div>

<div style="padding:0px 0px 0px 90px;">
<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>
	<?php printf(__('<span class="bold">You must be <a href="%s">logged in</a> to post a comment.</span>'), get_option('siteurl')."/wp-login.php?redirect_to=".urlencode(get_permalink()));?>
</p>

<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p>
	<?php printf(__('<span class="bold">Logged in as %s.</span>'), '<a href="'.get_option('siteurl').'/wp-admin/profile.php">'.$user_identity.'</a>'); ?>
</p>

<?php else : ?>

<p style="padding:10px 0px 10px 0px;">
	<input type="text" name="author" class="comment-input" id="author" value="Your Name" size="27" tabindex="1" onclick="clickclear(this, 'Your Name')" onblur="clickrecall(this,'Your Name')" />
</p>
<p style="padding:10px 0px 10px 0px;">
	<input type="text" name="email" class="comment-input" id="email" value="Email Address" size="27" tabindex="2" onclick="clickclear(this, 'Email Address')" onblur="clickrecall(this,'Email Address')" />
</p>
<p style="padding:10px 0px 10px 0px;">
	<input type="text" name="url" class="comment-input" id="url" value="Website URL" size="27" tabindex="3" onclick="clickclear(this, 'Website URL')" onblur="clickrecall(this,'Website URL')" />
</p>

<?php endif; ?>

<p style="padding:10px 0px 10px 0px;">
	<textarea name="comment" class="comment-textarea" id="comment" cols="50" rows="5" tabindex="4"></textarea>
</p>
<!--ForTheLose.org-->
<p>
	<input name="submit" type="submit" class="comment-button" id="submit" tabindex="5" class="submit_b" value="Submit" />
	<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>

<?php do_action('comment_form', $post->ID); ?>

</form>
</div>
<?php endif; // If registration required and not logged in ?>

<?php else : // Comments are closed ?>
<div style="padding:0px 0px 0px 90px;">
<p>
	<?php _e('<span class="bold go-center">Sorry, comments are closed.</span>'); ?>
</p>
</div>
<?php endif; ?>
<div style="width:100%;height:10px;display:block;"></div>