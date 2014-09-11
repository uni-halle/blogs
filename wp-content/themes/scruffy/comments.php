<?php // Do not delete these lines
if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) die ('Please do not load this page directly. Thanks!');
if (!empty($post->post_password)) { // if there's a password
	if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
?>

<h2><?php _e('Password Protected'); ?></h2>
<p><?php _e('Enter the password to view comments.'); ?></p>

<?php return;
	}
}

	/* This variable is for alternating comment background */

$oddcomment = 'alt';

?>

<!-- You can start editing here. -->

<?php if ('open' == $post->comment_status) : ?>

	<h3><strong><?php comments_number('No Comments', 'One Comment', '% Comments' );?></strong> on "<?php the_title(); ?>"</h3>
	
<?php endif; ?>

<?php if ($comments) : ?>

	<ol class="commentlist">
		
		<?php foreach ($comments as $comment) : ?>
		
		<?php $comment_type = get_comment_type(); ?>
		<?php if($comment_type == 'comment') { ?>

			<li class="<?php echo $oddcomment; ?> <?php if($comment->user_id == $post->post_author) { echo "authorcomment"; }?>" id="comment-<?php comment_ID() ?>">
	
				<div class="commentmeta clearfix">
					
					<div class="gravatar">
					
						<?php echo get_avatar( $comment, $size = '40', $default = '<path_to_url>' ); ?>
						
					</div><!-- /gravatar -->
			
					<span class="commentauthor"><?php comment_author_link() ?></span><br />
					<span class="commentdate"><?php comment_date('d/m/Y') ?> <?php _e('at');?> <?php comment_time() ?></span>
					<span class="commentpermalink"><a href="#comment-<?php comment_ID(); ?>">Permalink</a></span>
					
					<?php edit_comment_link('Edit Comment','',''); ?>
			
				</div><!-- /commentmeta -->
				
				<div class="commententry">
					
					<?php if ($comment->comment_approved == '0') : ?>
						<p class="moderate"><?php _e('Your comment is awaiting moderation.'); ?></p>
					<?php endif; ?>
					
					<?php comment_text() ?>
				</div><!-- /commententry -->

			</li>

			<?php if ('alt' == $oddcomment) $oddcomment = ''; else $oddcomment = 'alt'; /* Changes every other comment to a different class */ ?>
		
		<?php } else { $trackback = true; } /* End of is_comment statement */ ?>		
		<?php endforeach; /* end for each comment */ ?>
		
	</ol>
	
	<!-- START TRACKBACKS -->

	<?php if ($trackback == true) { ?>
		
		<h3>Trackbacks</h3>
		
		<ol>
			<?php foreach ($comments as $comment) : ?>
			
				<?php $comment_type = get_comment_type(); ?>
				
				<?php if($comment_type != 'comment') { ?>
					<li class="trackback" id="comment-<?php comment_ID() ?>">
	
						<div class="commentmeta">
							<span class="commentauthor"><?php comment_author_link() ?></span>
							<span class="commentdate"><?php comment_date('d/m/Y') ?> <?php _e('at');?> <?php comment_time() ?></span>
						</div><!-- /commentmeta -->
					
						<div class="commententry">
							<p><?php comment_excerpt() ?></p>
						</div><!-- /commententry -->

					</li>
				<?php } ?>
			
			<?php endforeach; ?>
		</ol>
		
	<?php } ?>
	
	<!-- END TRACKBACKS -->
	
<?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		
		<!-- If comments are open, but there are no comments. -->
	
	<?php else : // comments are closed ?>

		<!-- If comments are closed. -->
		<h3>Comments are closed.</h3>

	<?php endif; ?>
	
<?php endif; ?>

<!-- START COMMENT FORMS -->

<?php if ('open' == $post->comment_status) : ?>
	
	<div id="formsblock" class="clearfix">
	<div id="respond"></div>
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>

		<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p>

	<?php else : ?>

		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

			<?php if ( $user_ID ) : ?>

				<h3 class="hi">
					Hi <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>, leave a comment:
					<small class="floatr">(Not <?php echo $user_identity; ?>? <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account"> Logout &raquo;</a>)</small>
				</h3>
			
			<?php else : ?>
			
				<h3 class="hi">Hi Stranger, leave a comment:</h3>

				<p>
					<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="40" tabindex="1" />
					<label for="author">Name <?php if ($req) echo "(required)"; ?></label>
				</p>

				<p>
					<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="40" tabindex="2" />
					<label for="email">Mail (will not be published) <?php if ($req) echo "(required)"; ?></label>
				</p>

				<p>
					<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="40" tabindex="3" />
					<label for="url">Website</label>
				</p>

			<?php endif; ?>

			<p>
				<textarea name="comment" id="comment" cols="60" rows="10" tabindex="4"></textarea>
			</p>
			
			<div id="tagbox">
				<strong>ALLOWED XHTML TAGS:</strong>
				<p><?php echo allowed_tags(); ?></p>
			</div>
			
			<p>
				<input class="floatl" name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" />
				<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
			</p>

			<?php do_action('comment_form', $post->ID); ?>

		</form>
		
		<a class="comments floatr" href="<?php bloginfo('comments_rss2_url') ?>" title="#">Subscribe to Comments</a>

	<?php endif; // If registration required and not logged in ?>
	
	</div><!-- /formsblock -->

<?php endif; // if you delete this the sky will fall on your head ?>