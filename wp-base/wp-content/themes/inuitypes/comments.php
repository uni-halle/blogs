<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php echo get_option('bizzthemes_password_protected_name'); ?></p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<div id="comments_wrap">

<?php if ( have_comments() ) : ?>

	<h3><?php comments_number(''.get_option('bizzthemes_comment_responsesa_name').'', ''.get_option('bizzthemes_comment_responsesb_name').'', ''.get_option('bizzthemes_comment_responsesc_name').'' );?> &rarr; &#8220;<?php the_title(); ?>&#8221;</h3>

	<ol class="commentlist">
	
	    <?php wp_list_comments('avatar_size=48&callback=custom_comment'); ?>
	
	</ol>    

	<div class="navigation">
	
		<div class="alignleft"><?php previous_comments_link() ?></div>
		
		<div class="alignright"><?php next_comments_link() ?></div>
		
		<div class="fix"></div>
		
	</div>
	
	<br />
	
	<?php if ( $comments_by_type['pings'] ) : ?>
	
        <h3 id="pings"><?php echo get_option('bizzthemes_comment_trackbacks_name'); ?></h3>
		
            <ol class="commentlist">
            
			    <?php wp_list_comments('type=pings'); ?>
            </ol>
			
    <?php endif; ?>

<?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
	 
		<!-- If comments are closed. -->
		<p class="nocomments"><?php echo get_option('bizzthemes_comment_closed_name'); ?></p>

	<?php endif; ?>

<?php endif; ?>

</div> <!-- end #comments_wrap -->

<?php if ('open' == $post->comment_status) : ?>

<div id="respond">

    <h3><?php comment_form_title( ''.get_option('bizzthemes_comment_reply_name').'', ''.get_option('bizzthemes_comment_reply_name').' &rarr; %s' ); ?></h3>
	
    <div class="cancel-comment-reply">
	    
		<small><?php cancel_comment_reply_link(); ?></small>
    
	</div>

    <?php if ( get_option('comment_registration') && !$user_ID ) : ?>

        <p><?php echo get_option('bizzthemes_comment_mustbe_name'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php echo get_option('bizzthemes_comment_loggedin_name'); ?></a> <?php echo get_option('bizzthemes_comment_postcomment_name'); ?></p>

    <?php else : ?>
	
        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

            <?php if ( $user_ID ) : ?>

                <p><?php echo get_option('bizzthemes_comment_loggedin_name'); ?> &rarr; <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(); ?>" title="Log out of this account"><?php echo get_option('bizzthemes_comment_logout_name'); ?> &raquo;</a></p>

            <?php else : ?>

                <p class="commpadd"><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
				<label for="author"><small><?php echo get_option('bizzthemes_comment_name_name'); ?> <?php if ($req) _e('*'); ?></small></label></p>

                <p class="commpadd"><input type="text" name="email" id="email" value="<?php echo $comment_auth_email; ?>" size="22" tabindex="2" />
				<label for="email"><small><?php echo get_option('bizzthemes_comment_mail_name'); ?> <?php if ($req) _e('*'); ?></small></label></p>

				<p class="commpadd"><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
			    <label for="url"><small><?php echo get_option('bizzthemes_comment_website_name'); ?></small></label></p>

            <?php endif; ?>

        <p class="commpadd"><textarea name="comment" id="comment" style="width:100%" rows="10" cols="10" tabindex="4"></textarea></p>

        <p style="padding:5px 0px 10px 0px;"><input name="submit" type="submit" id="submit" tabindex="5" value="<?php echo get_option('bizzthemes_comment_addcomment_name'); ?>" />

		    <?php comment_id_fields(); ?>

		</p>

		<?php do_action('comment_form', $post->ID); ?>

        </form>

    <?php endif; // If logged in ?>

    <div class="fix"></div>
	
</div> <!-- end #respond -->

<?php endif; // if you delete this the sky will fall on your head ?>
