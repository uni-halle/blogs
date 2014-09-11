<?php if ( have_comments() ) : ?>
	<h3 id="comments"><?php comments_number('Keine Kommentare', '1 Kommentar', '% Kommentare' );?></h3>

	<ol class="commentlist">
		<?php wp_list_comments(); ?>
	</ol>

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
		
		<p><input type="text" name="author" id="author" value="Name" size="22" tabindex="1" onblur="if(this.value=='')this.value='Name';" onfocus="if(this.value=='Name')this.value='';" /></p>
		
		<p><input type="text" name="email" id="email" value="E-Mail" size="22" tabindex="2" onblur="if(this.value=='')this.value='E-Mail';" onfocus="if(this.value=='E-Mail')this.value='';" /></p>
		
		<p><input type="text" name="url" id="url" value="Website" size="22" tabindex="3" onblur="if(this.value=='http://')this.value='Website';" onfocus="if(this.value=='Website')this.value='http://';" /></p>
		
		<?php endif; ?>
		
		<p><textarea name="comment" id="comment" cols="50" rows="10" tabindex="4"></textarea></p>
		
		<p><button name="submit" type="submit" id="submit" tabindex="5" value="Submit">Absenden</button></p>
		
		<?php comment_id_fields(); ?>
		
		<?php do_action('comment_form', $post->ID); ?>
	
	</form>
	
	<?php endif; // If registration required and not logged in ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>