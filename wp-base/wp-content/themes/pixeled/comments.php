<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

			<p class="nocomments">Dieser Bereich ist passwortgesch&uuml;tzt. Bitte gib das Passwort ein.</p>

			<?php
			return;
		}
	}

	/* This variable is for alternating comment background */
	$oddcomment = 'class="alt" ';
?>

<!-- You can start editing here. -->

<?php if ($comments) : ?>

	<ol id="commentlist">

	<?php foreach ($comments as $comment) : ?>

		<li <?php echo $oddcomment; ?>id="comment-<?php comment_ID() ?>">


<a class="gravatar">
<?php 
$mygravatarurl = get_bloginfo('template_directory')."/images/gravatar-trans.png";

if (function_exists('get_avatar')) {
      echo get_avatar( $comment, 69, $mygravatarurl);
   } else {
      //alternate gravatar code for < 2.5
      $grav_url = "http://www.gravatar.com/avatar.php?gravatar_id=
         " . md5($email) . "&default=" . urlencode($default) . "&size=" . $size;
      echo "<img src='$grav_url'/>";
   }
?>
</a>

			<div class="commentbody">
			<cite><?php comment_author_link() ?></cite> 
			<?php if ($comment->comment_approved == '0') : ?>
			<em>Danke. Dein Kommentar wird bald &uuml;berpr&uuml;ft und freigegeben.</em>
			<?php endif; ?>
			<br />

			<small class="commentmetadata"><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('d M. Y') ?> um <?php comment_time() ?></a> <?php edit_comment_link('Bearbeiten','&nbsp;&nbsp;',''); ?></small>

			<?php comment_text() ?>
			</div><div class="cleared"></div><!-- clears the floats so the backgrounds show all the way down -->
		</li>

	<?php
		/* Changes every other comment to a different class */
		$oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : '';
	?>

	<?php endforeach; /* end for each comment */ ?>

	</ol>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Keine Kommentare zugelassen.</p>

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<h3 id="respond">Dein Senf dazu:</h3>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>Du musst <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">eingeloggt</a> sein um hier ein Kommentar abzugeben.</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p>Du bist als <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a> eingeloggt. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Ausoggen vom Account">Ausloggen &raquo;</a></p>

<?php else : ?>

<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
<label for="author"><small>Name <?php if ($req) echo "(erforderlich)"; ?></small></label></p>

<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
<label for="email"><small>E-Mail (wird nicht ver&ouml;ffentlicht, <?php if ($req) echo "erforderlich)"; ?></small></label></p>

<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
<label for="url"><small>Webseite</small></label></p>

<?php endif; ?>

<!--<p><small><strong>XHTML:</strong> Du kannst diese Tags nutzen: <code><?php echo allowed_tags(); ?></code></small></p>-->


<p><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>

<p><input name="submit" type="submit" id="submit" class="submitbutton" tabindex="5" value="Senden" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>
<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>


<?php endif; // if you delete this the sky will fall on your head ?>
