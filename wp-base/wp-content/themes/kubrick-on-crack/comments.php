<?
global $options;
foreach ($options as $value) {
    if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
}
?>
<?php echo $koc_google_adsense; ?>


<div id="join-the-disc">
<div id="comments"><?php comments_number('No Comments Yet - be the First!', 'One Comment So Far...', '% Comments Already!' );?></div>	

<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) { ?>
	<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
<?php
	return;
}

// add a microid to all the comments
function comment_add_microid($classes) {
	$c_email=get_comment_author_email();
	$c_url=get_comment_author_url();
	if (!empty($c_email) && !empty($c_url)) {
		$microid = 'microid-mailto+http:sha1:' . sha1(sha1('mailto:'.$c_email).sha1($c_url));
		$classes[] = $microid;
	}
	return $classes;	
}
add_filter('comment_class','comment_add_microid');





// show the comments
if ( have_comments() ) : ?>
	
	<ul class="commentlist" id="singlecomments">	
	<?php wp_list_comments(array('avatar_size'=>40, 'reply_text'=>'Reply')); ?>
	</ul>
	
	
	
	
	
	
	<div class="navigation">
		<div class="alignleft old"><?php previous_comments_link('older comments') ?></div>
		<div class="alignright new"><?php next_comments_link('newer comments') ?></div>
	</div>

<div class="myLinks">
	  <a href="http://fearlessflyer.com">Fearlessflyer.com</a>
	  <a href="http://themereviewer.net">Themereviewer.net</a>
	  <a href="http://petmunch.com">Petmunch.com</a>  
	  </div><!--myLinks-->
	
	
 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) :
		// If comments are open, but there are no comments.
	else : 
		// comments are closed 
	endif;
endif; 

if ('open' == $post-> comment_status) : 

// show the form
?>
<div id="respond"><h3 id="response-title"><?php comment_form_title(); ?></h3>
<div id="cancel-comment-reply">
	<small><?php cancel_comment_reply_link(); ?></small>
</div>



<?php if ( get_option('comment_registration') && !$user_ID ) : ?>

<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>

<?php else : ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>.
<a href="<?php echo wp_logout_url(urlencode($_SERVER['REQUEST_URI'])); ?>" title="Log out of this account">Logout &raquo;</a></p>

<?php else : ?>
<div class="comment-form-labels">Name <small><?php if ($req) echo "Required:"; ?></small></div>
<p><input type="text" name="author" id="author"  size="81" value="<?php echo $comment_author; ?>" class="comment-form-input-fields"  tabindex="1" /></p>

<div class="comment-form-labels">Email <small> <?php if ($req) echo "Required:"; ?></small></div>
<p><input type="text" name="email" id="email"  size="81" value="<?php echo $comment_author_email; ?>" class="comment-form-input-fields" tabindex="2" /> </p>

<div class="comment-form-labels">Website <small>Optional</small></div>
<p><input type="text" name="url" id="url"  size="81" value="<?php echo $comment_author_url; ?>" class="comment-form-input-fields"  tabindex="3" /></p>

<?php endif; ?>

<div>
<?php comment_id_fields(); ?>
<input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" /></div>

<div class="comment-form-labels">Comment <small>Required:</small></div>
<p><textarea name="comment" id="comment" class="comment-form-input-fields"  cols="61" rows="15" tabindex="4"></textarea></p>

<?php if (get_option("comment_moderation") == "1") { ?>
 <p><small><strong>Please note:</strong> Comment moderation is enabled and may delay your comment. There is no need to resubmit your comment.</small></p>
<?php } ?>

<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit" /></p>
<?php do_action('comment_form', $post->ID); ?>

</form>
<?php endif; ?>
</div>
<?php endif; ?>

</div><!--join-the-disc-->