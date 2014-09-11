<?php
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
?>

<div id="comments">
	<div id="commentlist">
<?php
	if ( !empty($post->post_password) ) {
		if ( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) {
		return;
		}
	}

if ($comments) {
	$ping_count = $comment_count = 0;
	foreach ($comments as $comment){
		if(get_comment_type() == "comment"){ ?>
		<div class="index-comment comment">
			<div class="ic-avatar"><?php echo get_avatar(get_comment_author_email(), '37'); ?></div>
			<div class="ic-text">
				<div class="ic-meta ic-author"><?php echo get_comment_author_link(); ?></div>
				<div class="ic-content"><?php comment_text() ?></div>
				<div class="ic-meta ic-date"><?php echo get_comment_date('j F y'); ?> at <?php echo get_comment_date('H:i'); ?></div>
			</div>
		</div>
<?php	$comment_count++;
		} else { ?>
		<div class="index-comment">
			<a name="#comment-<?php comment_ID() ?>">&nbsp;</a>
			<div class="ic-text nofloat">
				<div class="ic-meta"><?php echo get_comment_author_link(); ?> at <?php echo get_comment_date('H:i'); ?> on <?php echo get_comment_date('j F Y'); ?></div>
				<div><?php comment_text() ?></div>
			</div>
		</div>
<?php 	$ping_count++;
		}
	}
}
?>
	</div>
	<span id="respond"></span>
	<?php if($post->comment_status == 'open' && get_option('slf_ajax') == 1 && ($comment_count + $ping_count) != 0) { ?>
		<div class="index-comment"><textarea class="respondtext single">Write a comment..</textarea></div><?php 
	} ?>
	<div id="comment_form" class="index-comment" style="display: none">
	<?php if($post->comment_status != 'open') { ?>
		Comment form currently closed..
	<?php } else { ?>
		<form id="commentform" action="<?php bloginfo('wpurl') ?>/wp-comments-post.php" method="post">
		<?php if($user_ID) { ?>
			<div class="form_login ic-avatar">
				<?php $user_email = $wpdb->get_var("select user_email from $wpdb->users where ID = '$user_ID'"); ?>
				<?php echo get_avatar($user_email, '37'); ?>
			</div>
		<?php } else { ?>
			<div class="form_input">
				<input id="author" name="author" type="text" class="focus" />
				<label for="author">Name</label>
			</div>
			<div class="form_input">
				<input id="email" name="email" type="text" />
				<label for="email">Email</label>
			</div>
			<div class="form_input">
				<input id="url" name="url" type="text" />
				<label for="url">Website</label>
			</div>
		<?php } ?>
			<div class="form_comment">
				<textarea name="comment" class="focus <?php if($user_ID) { echo "commenttextright"; } ?>"></textarea>
			</div>
			<div class="form_submit<?php if ($user_ID) { echo "_right"; } ?>">
				<input type="submit" name="submit" value="Comment" class="submit" />
			</div>
			<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
		</form>
	<?php } ?>
	</div>
</div>