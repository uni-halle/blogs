<?php if ( have_comments() ) : ?>

	<?php if ( ! empty($comments_by_type['comment']) ) : ?>
	
		<h3 id="comments"><?php comments_number(__( 'No Comments', 'blogsmlu'), __('1 Comment', 'blogsmlu'), __('% Comments', 'blogsmlu'));?></h3>
		
		<div class="navigation">
			<div class="alignleft"><?php previous_comments_link() ?></div>
			<div class="alignright"><?php next_comments_link() ?></div>
		</div>
		
		<ol class="commentlist">
	        <?php wp_list_comments('type=comment&avatar_size=52'); ?>
	    </ol>
	
	<?php endif; ?>
	
	<?php if ( ! empty($comments_by_type['pings']) ) : ?>
	
		<h3 id="pings">Trackbacks/Pingbacks</h3>
		<ol class="pinglist">
	        <?php wp_list_comments('type=pings&callback=list_pings'); ?>
	    </ol>

    <?php endif; ?>
    
    <div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	
 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e('Comments are closed.','blogsmlu'); ?></p>

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<div id="respond">

	<h3><?php comment_form_title(__( 'Leave a Reply','blogsmlu'), __('Leave a Reply to %s','blogsmlu' )); ?></h3>
	
	<div class="cancel-comment-reply">
		<small><?php cancel_comment_reply_link(); ?></small>
	</div>
	
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
	<p><?php _e('You must be logged in to post a comment. ','blogsmlu'); ?><a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e('Login','blogsmlu'); ?></a></p>
	<?php else : ?>
	
	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
	
		<?php if ( $user_ID ) : ?>
		
		<p><?php _e('Logged in as ','blogsmlu'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Logout','blogsmlu'); ?>"><?php _e('Logout','blogsmlu'); ?> &raquo;</a></p>
		
		<?php else : ?>
		
			<p>
				<label for="author" class="visuallyhidden"><?php _e('Name', 'blogsmlu'); if ($req) _e(' (Pflicht)', 'blogsmlu'); ?></label>
				<input type="text" class="text" name="author" id="author" placeholder="<?php _e('Name', 'blogsmlu'); if ($req) _e(' (Pflicht)', 'blogsmlu'); ?>" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?>>
			</p>
			<p>
				<label for="email" class="visuallyhidden"><?php _e('Email (bleibt geheim)', 'blogsmlu'); if ($req) _e(' (Pflicht)', 'blogsmlu'); ?></label>
				<input type="email" class="text" name="email" id="email" placeholder="<?php _e('Email (bleibt geheim)', 'blogsmlu'); if ($req) _e(' (Pflicht)', 'blogsmlu'); ?>" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?>>
			</p>
			<p>
				<label for="url" class="visuallyhidden"><?php _e('Website', 'blogsmlu'); ?></label>
				<input type="url" class="text" name="url" id="url" placeholder="<?php _e('Website', 'blogsmlu'); ?>" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3">
			</p>
			<?php endif; ?>
			<p>
				<label for="comment" class="visuallyhidden"><?php _e('Kommentar', 'blogsmlu'); ?></label>
				<textarea name="comment" id="comment" placeholder="<?php _e('Kommentar', 'blogsmlu'); ?>" tabindex="4"></textarea>
			</p>
			<?php comment_id_fields(); ?>
			<p><input name="submit" class="button submit" type="submit" id="submit" tabindex="5" value="<?php _e('Kommentar Senden', 'blogsmlu'); ?>"></p>
		
		
		<?php do_action('comment_form', $post->ID); ?>
	
	</form>
	
	<?php endif; // If registration required and not logged in ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>
