<?php // Do not delete these lines
if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
die ('Please do not load this page directly. Thanks!');

if (!empty($post->post_password)) { // if there's a password
if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie ?>

<p class="nocomments"><?php _e('This post is password protected.','avenue'); ?><p>
<?php return;}} /* This variable is for alternating comment background */ $oddcomment = 'alt'; ?>

<!-- You can start editing here. -->
<?php if ($comments) : ?>
<p class="comtitle" id="comments"><?php comments_number(__('No Responses','avenue'), __('One Response','avenue'), __('% Responses','avenue') );?></p>

<ol class="commentlist">
<?php $count = 0; foreach ($comments as $comment) : if (get_comment_type() == "comment") : $count++; ?>

<li id="comment-<?php comment_ID() ?>"<?php global $comment; if ( ($comment->comment_author_email == get_the_author_email()) && ($comment->user_id != 0) ) {echo ' class="adminbody"';} else { echo " class='commentbody'"; } ?>>

<?php global $comment; if ( ($comment->comment_author_email == get_the_author_email()) && ($comment->user_id != 0) ) {echo ' <div class="adminhead">';} else { echo " <div class='comhead'>"; } ?>

<?php if(function_exists('get_avatar')) { echo get_avatar( $comment, 40 ); } ?>
<div class="commentcount"><?php echo $count; ?></div>

<span class="authorlink"><?php comment_author_link() ?></span>&nbsp;<?php comment_type('', '(via Trackback)', '(via Pingback)'); ?>
<span class="editlink"><?php edit_comment_link('&raquo Edit &laquo'); ?></span>
<?php if ($comment->comment_approved == '0') : ?>

<em><?php _e('(Your comment is awaiting moderation)','avenue') ?></em>
<?php endif; ?><br />

<a class="commentlink" href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('l, j. F Y','avenue') ?></a>
</div><?php comment_text() ?></li>

<?php /* Changes every other comment to a different class */  
if ('alt' == $oddcomment) $oddcomment = ''; else $oddcomment = 'alt'; ?>
<?php endif; endforeach; /* end for each comment */ ?>
</ol>

<?php else : // this is displayed if there are no comments so far ?>
<?php if ('open' == $post-> comment_status) : ?><?php else : // comments are closed ?>

<?php endif; ?><?php endif; ?>


<?php if ($comments) : ?>

<ol class="commentlist">
<?php foreach ($comments as $comment) : if ((get_comment_type() == "trackback") || (get_comment_type() == "pingback")) : $count++?>


<li class="trackbody" id="comment-<?php comment_ID() ?>">
<div class='trackhead'><div class="commentcount"><?php echo $count; ?></div>

<span class="authorlink"><?php comment_author_link() ?></span>&nbsp;<?php comment_type('', '(via Trackback)', '(via Pingback)'); ?>
<span class="editlink"><?php edit_comment_link(' &raquo Edit &laquo'); ?></span><br />

<a class="commentlink" href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('l, j. F Y','avenue') ?></a>
</div><?php comment_text() ?></li>

<?php /* Changes every other comment to a different class */  
if ('alt' == $oddcomment) $oddcomment = ''; else $oddcomment = 'alt'; ?>
<?php endif; endforeach; /* end for each comment */ ?>
</ol>

<?php else : // this is displayed if there are no comments so far ?>
<?php if ('open' == $post->comment_status) : ?><?php else : // comments are closed ?>

<?php endif; ?><?php endif; ?>


<?php if ('open' == $post-> comment_status) : ?>
<h6 id="respond"><?php _e('Leave a Reply','avenue'); ?></h6>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p class="login"><?php _e('You must be logged in to post a comment.','avenue'); ?></p>

<?php else : ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<?php if ( $user_ID ) : ?><?php else : ?>

<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
<label for="author"><small><?php _e('Name'); ?>&nbsp;<?php if ($req) _e('(required)','avenue'); ?></small></label></p>

<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
<label for="email"><small><?php _e('E-Mail (will not be published, ','avenue'); ?><?php if ($req) _e('required)','avenue'); ?></small></label></p>

<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
<label for="url"><small><?php _e('Website (optional)','avenue'); ?></small></label></p>
<?php endif; ?>

<!--<p><small><strong>XHTML:</strong> You can use these tags:&nbsp;<?php echo allowed_tags(); ?></small></p>-->

<p><textarea name="comment" id="comment" cols="50" rows="8" tabindex="4"></textarea></p>
<p><input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit','avenue'); ?>" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" /></p>

<?php do_action('comment_form', $post->ID); ?></form>
<?php endif; // If registration required and not logged in ?>
<?php endif; // if you delete this the sky will fall on your head ?>