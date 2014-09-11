<?php

// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');

if ( ( function_exists('post_password_required') && post_password_required()) ||
     (!function_exists('post_password_required') && !empty($post->post_password) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) ) { 
    ?>
	<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'elegant-grunge') ?></p>
    <?php
	return;
}

?>

<!-- You can start editing here. -->
<?php
function elegant_grunge_comments_template($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li <?php echo $oddcomment; ?>id="comment-<?php comment_ID() ?>" <?php echo ($depth>1?'style="margin-left: '.(30+(($depth-1)*30)).'px"':'')?>>
        <div class="comment-content">
    		<div class="before-comment"></div>
    		<div class="comment">
    		<?php echo get_avatar( $comment, 32 ); ?>
    		<cite><?php comment_author_link() ?></cite> <?php _e('Says:', 'elegant-grunge') ?>
    		<?php if ($comment->comment_approved == '0') : ?>
    		<em><?php _e('Your comment is awaiting moderation.', 'elegant-grunge') ?></em>
    		<?php endif; ?>
    		<br />

    		<small class="commentmetadata"><a href="#comment-<?php comment_ID() ?>" title=""><?php printf(_c('%1$s at %2$s|date at time', 'elegant-grunge'), get_comment_date(__('F jS, Y', 'elegant-grunge')), get_comment_time()) ?></a> <?php edit_comment_link(__('edit', 'elegant-grunge'),'&nbsp;&nbsp;',''); ?></small>

    		<div class="comment-text"><?php comment_text() ?></div>
    		
    		
    		<?php if ( function_exists('comment_reply_link') ) : ?>
    		<div class="reply">
                <?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
            </div>
            <?php endif; ?>
    		
    		</div>
    		<div class="after-comment"><div></div></div>
		</div>
	</li>

    <?php
	/* Changes every other comment to a different class */
	$oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : '';
}


?>


<?php if ($comments) : ?>
	<h4 id="comments"><?php comments_number(__('No Responses', 'elegant-grunge'), __('One Response', 'elegant-grunge'), __('% Responses', 'elegant-grunge')) ?> <?php printf(__('to &#8220;%s&#8221;', 'elegant-grunge'), get_the_title()) ?></h4>

	<?php if ( function_exists('previous_comments_link') ) : ?>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<?php endif; ?>

    <div class="clear"></div>

	<ul class="commentlist">
    <?php 
    if ( function_exists('wp_list_comments') ) {
        wp_list_comments('callback=elegant_grunge_comments_template');
    } else {
    	foreach ($comments as $comment) {
            elegant_grunge_comments_template($comment, null, 0);
    	}
    }
	?>
	</ul>

    <?php if ( function_exists('previous_comments_link') ) : ?>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<?php endif; ?>
	
    <div class="clear"></div>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e('Comments are closed.', 'elegant-grunge') ?></p>

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<h4 id="respond"><?php _e('Leave a Reply', 'elegant-grunge') ?></h4>

<?php if ( function_exists('cancel_comment_reply_link') ) : ?>
<div class="cancel-comment-reply">
	<small><?php cancel_comment_reply_link(); ?></small>
</div>
<?php endif; ?>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p><?php printf(__('You must be %1$slogged in%2$s to post a comment.', 'elegant-grunge'), '<a href="'.get_option('siteurl').'/wp-login.php?redirect_to='.urlencode(get_permalink()).'">', '</a>') ?></p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p><?php printf(__('Logged in as %1$s. %2$sLog out &raquo;%3$s', 'elegant-grunge'), '<a href="'.get_option('siteurl').'/wp-admin/profile.php">'.$user_identity.'</a>', '<a href="'.(function_exists('wp_logout_url') ? wp_logout_url(get_permalink()) : get_option('siteurl').'/wp-login.php?action=logout" title="').'" title="'.__('Log out of this account', 'elegant-grunge').'">', '</a>') ?></p>

<?php else : ?>

<p><input type="text" class="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
<label for="author"><small><?php _e('Name', 'elegant-grunge') ?> <?php if ($req) _e("(required)", 'elegant-grunge'); ?></small></label></p>

<p><input type="text" class="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
<label for="email"><small><?php _e('Mail (will not be published)', 'elegant-grunge') ?> <?php if ($req) _e("(required)", 'elegant-grunge'); ?></small></label></p>

<p><input type="text" class="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
<label for="url"><small><?php _e('Website', 'elegant-grunge') ?></small></label></p>

<?php endif; ?>

<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->

<p><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>

<p><input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment', 'elegant-grunge') ?>" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
<?php if ( function_exists('comment_id_fields') ) comment_id_fields(); ?>
</p>
<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
