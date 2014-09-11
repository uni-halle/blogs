<?php
	if ( 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) )
		die ( 'Please do not load this page directly. Thanks.' );
?>
<div class="fixed"></div>
<div id="comments">

<?php
	if ( !empty($post->post_password) ) :
		if ( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) :
?>
				<div class="nopassword"><?php _e( 'This post is protected. Enter the password to view any comments.', 'win7blog' ) ?></div>
			</div><!-- .comments -->
<?php
		return;
	endif;
endif;
?>

<?php if ( have_comments() ) : ?>
	<?php global $comment_ids, $win7blog_options; $comment_ids = array(); $exclude_child_comments = true;
		if ( $win7blog_options["show_child_floor"] == 'on' ) $exclude_child_comments = false;
		if ( $exclude_child_comments == false ) {
			foreach ( $comments as $comment ) {
				if (get_comment_type() == "comment") {
					++$comment_count;
					$comment_ids[get_comment_id()] = ++$comment_i;
				}
			}
		} else {
			foreach ( $comments as $comment ) {
				if (get_comment_type() == "comment") {
					++$comment_count;
					if( $comment->comment_parent == 0 )
						$comment_ids[get_comment_id()] = ++$comment_i;
				}
			}
		}
	?>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>

	<div id="comments-list" class="comments">
		<h3><?php printf($comment_count > 1 ? __('%d Responses', 'win7blog') : __('One Response', 'win7blog'), $comment_count) ?>
			<span style="font-size:14px;"><a href="#respond"><?php echo __('Leave a comment', 'win7blog') ?></a></span>
		</h3>
		<ol><?php wp_list_comments('type=comment&callback=win7blog_comment&end-callback=win7blog_end_comment'); ?></ol>
	</div>
				
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
<?php endif // REFERENCE: if have_comments() ?>

<?php if ( 'open' == $post->comment_status ) : ?>
	<div class="fixed"></div>
	<div id="respond">
	
		<h4><?php comment_form_title(__('Leave a Reply','win7blog'), __('Leave a Reply to %s','win7blog'), true); ?></h4>
		<p id="cancel-comment-reply"><?php cancel_comment_reply_link(__('Cancel Reply','win7blog')) ?></p>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
		<p id="login-req"><?php printf(__('You must be <a href="%s" title="Log in">logged in</a> to post a comment.', 'win7blog'),
		get_bloginfo('wpurl') . '/wp-login.php?redirect_to=' . get_permalink() ) ?></p>
<?php else : ?>

		<form id="commentform" action="<?php bloginfo('wpurl') ?>/wp-comments-post.php" method="post">

<?php if ( $user_ID ) : ?>
			<p><?php _e('Logged in as ', 'win7blog') ?><a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>， <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account"><?php _e('Log out ', 'win7blog') ?>&raquo;</a></p>
<?php else : ?>
			<div class="form-input">
	            <input id="author" name="author" class="text" type="text" value="<?php echo $comment_author ?>" size="30" maxlength="50" tabindex="3" />
	            <label class="form-label"><?php _e('Name ', 'win7blog');if ($req) _e('(required)', 'win7blog'); ?></label>
	          </div>

			<div class="form-input">
				<input id="email" name="email" class="text" type="text" value="<?php echo $comment_author_email ?>" size="30" maxlength="50" tabindex="4" />
				<label class="form-label"><?php _e('Mail (will not be published) ', 'win7blog');if ($req) _e('(required)', 'win7blog'); ?></label>
	          </div>

			<div class="form-input">
				<input id="url" name="url" class="text" type="text" value="<?php echo $comment_author_url ?>" size="30" maxlength="50" tabindex="5" />
				<label class="form-label"><?php _e('Website ', 'win7blog') ?></label>
	         </div>
<?php endif // REFERENCE: * if ( $user_ID ) ?>

			<div class="form-textarea">
				<textarea id="comment" name="comment" class="text required" cols="45" rows="8" tabindex="6" onkeydown="javascript: return ctrlEnter(event);"></textarea>
			</div>
			<div class="form-submit"><input id="submit" name="submit" class="button" type="submit" value="<?php _e( 'Submit', 'win7blog' ) ?>" tabindex="7" />
				<span><?php echo '(Ctrl + Enter)' ?></span>
			</div>
			<?php comment_id_fields(); ?>
			<div class="form-option"><?php do_action( 'comment_form', $post->ID ) ?></div>
			
		</form><!-- #commentform -->

		<script language=javascript>
		/* <![CDATA[ */
			function ctrlEnter(e){ 
				var theEvent = e?e:window.event;
				if(theEvent.ctrlKey && theEvent.keyCode==13) document.getElementById("submit").click(); 
			}
		/* ]]> */
		</script>
 
	</div><!-- .respond -->
	
<?php endif // REFERENCE: if ( get_option('comment_registration') && !$user_ID ) ?>
<?php endif // REFERENCE: if ( 'open' == $post->comment_status ) ?>

</div><!-- #comments -->
