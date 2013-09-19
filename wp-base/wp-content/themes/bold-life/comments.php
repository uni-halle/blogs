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
        

<div class="comment">
    <div class="span-3">
        &nbsp;
    </div>
    <div class="span-15 last">
        <div class="entry">
        <div class="entry_comment">
        <?php if ($comments) : ?>
            <h2><?php comments_number('No Responses', 'One Response', '% Responses' );?> to &#8220;<?php the_title(); ?>&#8221;</h2>
            <ol class="commentlist">
            <?php wp_list_comments('type=all&avatar_size=50'); ?>
            
            </ol>
            <?php else : // this is displayed if there are no comments so far ?>

			<?php if ( comments_open() ) : ?>
                <!-- If comments are open, but there are no comments. -->
                No comments
        
            <?php else : // comments are closed ?>
                <!-- If comments are closed. -->
                Comments are closed.
        	
            <?php endif; ?>
       	<?php endif; ?>
       
		<?php if ( comments_open() ) : ?>
                <h2>Place your comment</h2>
                <?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
                <div class="commentform_hint">
                    <h3>You must be <a href="<?php echo wp_login_url( get_permalink() ); ?>">logged in</a> to post a comment.</h3>                                            
                </div>
                <?php else : ?>
                <div class="commentform_hint">
                    Please fill your data and comment below.
                </div>
                <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" class="commentform_form"  method="post" id="commentform">
                    <div class="commentform_left">
                    	<?php if ( is_user_logged_in() ) : ?>
    					<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p>
                        <?php else : ?>
                    
                        <div class="commentform_title">Name</div>
                        <input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
                        <div class="commentform_title">Email</div>
                        <input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
                        <div class="commentform_title">Website</div>
                        <input type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" />
                        <?php endif; ?>
                    </div>
                    <div class="commentform_right">
                        <div class="commentform_title">Your comment</div>
                        <textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea>
                        <input name="submit" type="submit" id="submit" tabindex="5" class="commentform_submit" value=" " />
                        <?php comment_id_fields(); ?>
                        <?php do_action('comment_form', $post->ID); ?>
                    </div>
                </form>
            	<?php endif; // If registration required and not logged in ?>
        <?php endif; // if you delete this the sky will fall on your head ?> 
    </div>
    </div>	
    </div>
</div>


