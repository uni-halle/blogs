<div id="comment-template">

<?php if ( !empty($post->post_password) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) : ?>
	<p><?php _e('Enter your password to view comments.'); ?></p>
<?php return; endif; ?>
		
	<?php if ( $comments ) : ?>
	
	<h2><span><?php comments_number(__('No responses'), __('One response'), __('% responses')); ?></span></h2><br />
	<p class="do-you-comment"><a href="#respond" title="Comment">Do you want to comment?</a></p>
	<p class="trackback"><?php comments_rss_link(__('Comments RSS')); ?> <?php if ( pings_open() ) : ?>and <a href="<?php trackback_url() ?>" rel="trackback"><?php _e('TrackBack Identifier URI'); ?></a>	<?php endif; ?>?</p><!-- end .trackback -->
	<div class="clearing"></div>
	
		<div id="comments">
		<?php $i = 0; ?>
		<?php foreach ($comments as $comment) : ?>
			<?php $comment_type = get_comment_type(); ?>
			<?php if($comment_type == 'comment') { ?>
		<?php $i++; ?>
				<blockquote <?php if($i&1) { echo 'class="odd"';} else {echo 'class="even"';} ?>>
					<div class="floatleft">
						<div class="avatar-wrap"><?php echo get_avatar( $comment, 80 ); ?></div><!-- end .avatar -->
					</div><!-- end .name -->
					<div class="floatright">
						<div class="author"><a href="<?php comment_author_url(); ?>" title="<?php comment_author(); ?>" rel="external nofollow"><strong><?php comment_author(); ?></strong></a></div>
						<div class="comment"><?php comment_text() ?><?php edit_comment_link(__("(Edit This)<br />"), ''); ?></div><!-- end .comment -->
						<div class="date"><span><?php comment_date() ?> <a href="#comment-<?php comment_ID() ?>"><?php comment_time() ?></a></span></div><!-- end .date -->
					</div><!-- end .floatright -->
					<div class="clearing"></div>
				</blockquote>
		<?php } /* End of is_comment statement */ ?>
		<br />
		<?php endforeach; ?>
		</div><!-- end #comments -->
		


	<?php else : ?>

	<?php endif; ?>



	<?php if ( comments_open() ) : ?>
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?><p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p><?php else : ?>

	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="respond">
		<h2><span><?php _e('Comment now!'); ?></span></h2>
		<?php if ( $user_ID ) : ?><p class="logged-as">Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account') ?>">Logout &raquo;</a></p><?php else : ?>

		<br /><br />

			<label for="author">Name</label><br />
			<input type="text" class="ipt-name" name="author" id="author" />
			
			<br /><br />

			<label for="email">Mail <strong>(will not be published)</strong></label><br />
			<input type="text" class="ipt-mail" name="email" id="email" />
			
			<br /><br />

			<label for="url">Website <strong>(optional)</strong></label><br />
			<input type="text" class="ipt-url" name="url" id="url" />
			
			<br /><br />

			<?php endif; ?>

			<?php do_action('comment_form', $post->ID); ?><br />
			
			<label for="comment">Your comment</label><br />
			<textarea class="textarea-comment" name="comment" id="comment" cols="31" rows="10"></textarea>
			
			<br /><br />
			
			<p class="btt-submit-wrap"><input type="submit" name="submit" id="submit" class="btt-submit" value="Post my comment" /></p>
			<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
	</form>
	<?php endif; // If registration required and not logged in ?>
	
	
<div id="trackbacks">
<h2><span>Trackbacks</span></h2>
	<ul>
		<?php foreach ($comments as $comment) : ?>
		<?php $comment_type = get_comment_type(); ?>
		<?php if($comment_type != 'comment') { ?>
		<li><?php comment_author_link() ?></li>
		<?php } ?>
		<?php endforeach; ?>
	</ul>
</div><!-- end #trackbacks -->
	
	
<?php else : // Comments are closed ?>
<p class="comments-closed"><?php _e('Sorry, the comment form is closed at this time.'); ?></p>
<?php endif; ?>
	
</div><!-- end #comment-template -->