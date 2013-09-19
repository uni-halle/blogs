<?php
 if ( !empty($post->post_password) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) : ?>
<div class="errorbox">
<?php _e('Enter your password to view comments.', 'philna'); ?>
</div>
<?php return; endif; ?>

<?php
$options = get_option('philna_options');
$trackbacks = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved = '1' AND (comment_type = 'pingback' OR comment_type = 'trackback') ORDER BY comment_date", $post->ID));
$count = 0;
?>
<script type="text/javascript">
/* <![CDATA[ */
ajaxCommentsURL = "<?php bloginfo('template_url'); ?>/comments-ajax.php";
/* ]]> */
</script>
<?php if ($comments || comments_open()):?>
<div id="comments">
<div id="cmtswitcher">

<a id="commenttab" class="curtab" href="javascript:void(0);">
<?php _e('Comments', 'philna');echo ' (';?><span id="allcmnub"><?php echo count($comments)-count($trackbacks);?></span>)</a>
<a id="trackbacktab" class="tab" href="javascript:void(0);">
<?php _e('Trackbacks', 'philna'); echo (' (' . count($trackbacks) . ')'); ?>
</a>

	<?php if(comments_open()) : ?>
		<span class="addcomment"><a href="#respond"><?php _e('Leave a comment', 'philna'); ?></a></span>
	<?php endif; ?>
	<?php if(pings_open()) : ?>
		<span class="addtrackback"><a href="<?php trackback_url(); ?>"><?php _e('Trackback', 'philna'); ?></a></span>
	<?php endif; ?>
	
	<div class="fixed"></div>

</div>
<div id="commentlist">

	<!-- comments START -->
	<ol id="thecomments" class="display">
	<?php
		if ($comments && count($comments) - count($trackbacks) > 0) {
			foreach ($comments as $comment) {
				if(!$comment->comment_type == 'pingback' && !$comment->comment_type == 'trackback') {
	?>

<li class="comment <?php if($comment->comment_author_email == get_the_author_email()) {echo 'admincomment';} else {echo 'regularcomment';} ?>" id="comment-<?php comment_ID() ?>">

				<div class="comment-meta">
					<? printf( __('%1$s at %2$s', 'philna'), get_comment_time(__('F jS, Y', 'philna')), get_comment_time(__('H:i', 'philna')) ); ?>
					 | #<span class="cmnub"><?php printf('%1$s', ++$count); ?></span>
					 
					 <?php edit_comment_link(__('Edit', 'philna'), ' | ', ''); ?>
				</div>

			<div class="comment-author">
				
					<?php if (function_exists('get_avatar') && get_option('show_avatars')) { echo get_avatar($comment, 32); } ?>				
				
					<?php if (get_comment_author_url()) : ?>
						<span class="fn"><a id="commentauthor-<?php comment_ID() ?>" href="<?php comment_author_url() ?>"><?php comment_author(); ?></a><?php if($options['xing_show']) get_xing(get_comment_author_email());?></span>
						<span class="reply" style="visibility:hidden;"><a href="#commentform" title="Notify this pumpkin." onclick="document.getElementById('comment').value += '&lt;a href=&quot;#comment-<?php comment_ID() ?>&quot;&gt;@<?php comment_author();?> &lt;/a&gt;\n'"><img src="<?php bloginfo('template_url'); ?>/img/reply.gif" alt="Notify"/></a></span>
					<?php else : ?>
						<span class="fn" id="commentauthor-<?php comment_ID() ?>"><?php comment_author(); ?><?php if($options['xing_show'])get_xing(get_comment_author_email());?></span>
						<span class="reply" style="display:none;"><a href="#commentform" title="Notify this pumpkin." onclick="document.getElementById('comment').value += '&lt;a href=&quot;#comment-<?php comment_ID() ?>&quot;&gt;@<?php comment_author();?> &lt;/a&gt;\n'"><img src="<?php bloginfo('template_url'); ?>/img/reply.gif" alt="Notify"/></a></span>
						
					<?php endif; ?>

			</div>
			<?php if ($comment->comment_approved == '0') : ?>
			<p><small class="waiting">Your comment is awaiting moderation.</small></p>
			<?php endif; ?>
			<?php comment_text(); ?>
		</li>
	<?php
				} // if pingback/trackback
			} // foreach
		} else {
	?>
	
		<li class="messagebox">
			<?php _e('No comments yet.Be the first ?', 'philna'); ?>
		</li>
	<?php
		}
	?>

	</ol>
	<!-- comments END -->

	<!-- trackbacks START -->
	<ol id="thetrackbacks" class="nodisplay">
		<?php if ($trackbacks) : $count = 0; ?>
			<?php foreach ($trackbacks as $comment) : ?>
				<li class="trackback">
				<div class="act">
						 | <?php edit_comment_link(__('Edit', 'philna'), '', ''); ?>
					</div>
					<div class="date">
						<? printf( __('%1$s at %2$s', 'philna'), get_comment_time(__('F jS, Y', 'philna')), get_comment_time(__('H:i', 'philna')) ); ?>
						 | <a href="#comment-<?php comment_ID() ?>"><?php printf('#%1$s', ++$count); ?></a>
					</div>					
					<div class="fixed"></div>
					<div class="title">
						<a href="<?php comment_author_url() ?>">
							<?php comment_author(); ?>
						</a>
					</div>
					<span class="trackbacks-content"><?php comment_text(); ?></span>
				</li>
			<?php endforeach; ?>

		<?php else : ?>
			<li class="messagebox">
				<?php _e('No trackbacks yet.', 'philna'); ?>
			</li>

		<?php endif; ?>
	</ol>
	<!-- trackbacks END -->
</div>
</div><!-- #comments -->
<?php endif; ?>


<?php if (!comments_open()) : // If comments are closed. ?>
	<div class="messagebox">
		<?php _e('Comments are closed.', 'philna'); ?>
	</div>
<?php elseif ( get_option('comment_registration') && !$user_ID ) : // If registration required and not logged in. ?>
	<div class="messagebox">
		<?php if (function_exists('wp_login_url')) {$login_link = wp_login_url();} else { $login_link = site_url('wp-login.php', 'login'); } ?>
		<?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'philna'), $login_link); ?>
	</div>

<?php else : ?>
<div id="respond">
	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
	

		<?php if ($user_ID) : ?>
			<?php if (function_exists('wp_logout_url')) {$logout_link = wp_logout_url();} else { $logout_link = site_url('wp-login.php?action=logout', 'login'); } ?>
			<div class="row"><?php _e('Logged in as', 'philna'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><strong><?php echo $user_identity; ?></strong></a>. <a href="<?php echo $logout_link; ?>" title="<?php _e('Log out of this account', 'philna'); ?>"><?php _e('Logout &raquo;', 'philna'); ?></a></div>

		<?php else : ?>
			<?php if ( $comment_author != "" ) : ?>
				<script type="text/javascript" charset="utf-8">
				//<![CDATA[
					var changeMsg = "<?php _e('Change &raquo;','philna'); ?>";
					var closeMsg = "<?php _e('Close &raquo;','philna'); ?>";
				//]]>
				</script>
				<div class="row welcome">
					<?php echo get_avatar($comment_author_email, 32); printf(__('Welcome back <strong>%s</strong>.', 'philna'), $comment_author) ?>
					<span id="show_author_info"><a href="javascript:void(0);"><?php _e('Change &raquo;','philna'); ?></a></span>
					</div>
			<?php endif; ?>

			<div id="author_info"<?php if ( $comment_author != "" ) echo 'style="display:none"';?>>
				<span class="row">
					<input type="text" name="author" id="author" class="textfield" value="<?php echo $comment_author; ?>" size="24" tabindex="1" />
					<label for="author" class="small"><?php _e('Name', 'philna'); ?> <?php if ($req) _e('(required)', 'philna'); ?></label>
				</span>
				<span class="row">
					<input type="text" name="email" id="email" class="textfield" value="<?php echo $comment_author_email; ?>" size="24" tabindex="2" />
					<label for="email" class="small"><?php _e('E-Mail (will not be published)', 'philna');?> <?php if ($req) _e('(required)', 'philna'); ?></label>
				</span>
				<span class="row">
					<input type="text" name="url" id="url" class="textfield" value="<?php echo $comment_author_url; ?>" size="24" tabindex="3" />
					<label for="url" class="small"><?php _e('Website', 'philna'); ?></label>
				</span>
			</div>
		<?php endif; ?>

	<!-- comment input -->
		<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/edit_button.js"></script>
<script type="text/javascript">edToolbar('comment');</script>
	<div class="row cmarea">

		<textarea name="comment" id="comment" tabindex="4" rows="8" cols="50"></textarea>
		<div id="commentload" class="center" style="display:none;">
		<img src="<?php bloginfo('template_url'); ?>/img/ajaxload.gif" alt="loading" /> <?php _e('Submitting Comment, Wait me a second...','philna')?>
		</div>
	</div>
<div id="submitbox">
			<a class="feed" href="<?php bloginfo('comments_rss2_url'); ?>"><?php _e('Comments feed', 'philna'); ?></a>
<script type="text/javascript">
/* <![CDATA[ */
//ctrl+enter
document.getElementById("comment").onkeydown = function (moz_ev){
var ev = null;
	if (window.event){
	ev = window.event;
	}else{
	ev = moz_ev;
	}
	if (ev != null && ev.ctrlKey && ev.keyCode == 13)
	{
	document.getElementById("submit").click();
	}
}
/* ]]> */
</script>	
			<input name="submit" type="submit" id="submit" class="button" tabindex="5" value="<?php _e('Submit Comment', 'philna'); ?>" />
		<?php if (function_exists('highslide_emoticons')) : ?>
			<div id="emoticon"><?php highslide_emoticons(); ?></div>
		<?php endif; ?>
		<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
	<?php do_action('comment_form', $post->ID); ?>
<div class="fixed"></div>
</div>
</form>
</div>
<?php endif; ?>
