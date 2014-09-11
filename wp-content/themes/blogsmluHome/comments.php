<?php if ( have_comments() ) : ?>

	<?php if ( ! empty($comments_by_type['comment']) ) : ?>
	
		<h3 id="comments"><?php comments_number('Keine Kommentare', '1 Kommentar', '% Kommentare' );?></h3>
		
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
		<p class="nocomments">Kommentare sind geschlossen.</p>

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<div id="respond">

	<h3><?php comment_form_title( 'Beitrag kommentieren', 'Kommentiere %s' ); ?></h3>
	
	<div class="cancel-comment-reply">
		<small><?php cancel_comment_reply_link(); ?></small>
	</div>
	
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
	<p>Du musst <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">angemeldet</a> sein, um zu kommentieren.</p>
	<?php else : ?>
	
	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
	
		<?php if ( $user_ID ) : ?>
		
		<p>Angemeldet als <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Abmelden">Abmelden &raquo;</a></p>
		
		<?php else : ?>
		
		<p>
		<label for="author"><?php _e('Dein Name'); ?></label>
		<input type="text" name="author" id="author" size="22" tabindex="1" /></p>
		
		<p>
		<label for="email"><?php _e('Deine E-Mail (bleibt geheim)'); ?></label>
		<input type="text" name="email" id="email" size="22" tabindex="2" /></p>
		
		<p>
		<label for="url"><?php _e('Deine Website'); ?></label>
		<input type="text" name="url" id="url" size="22" tabindex="3" /></p>
		
		<?php endif; ?>
		
		<p>
		<label for="comment"><?php _e('Dein Kommentar'); ?></label>
		<textarea name="comment" id="comment" cols="50" rows="10" tabindex="4"></textarea></p>
		
		<p><input name="submit" type="submit" class="submit" value="Absenden" /></p>
		
		<?php comment_id_fields(); ?>
		
		<?php do_action('comment_form', $post->ID); ?>
	
	</form>
	
	<?php endif; // If registration required and not logged in ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>