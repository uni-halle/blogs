<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Diese Seite kann nicht direkt geladen werden!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

<p class="nocomments">Dieser Bereich ist passwortgesch&uuml;tzt. Bitte gib das Passwort ein um die Beitr&auml;ge zu sehen.</p>
<?php
			return;
		}
	}

	/* This variable is for alternating comment background */
	$oddcomment = 'class="alt" ';
?>
<!-- You can start editing here. -->
<?php if ($comments) : ?>
<?php if (!('open' == $post-> comment_status)) : ?>
<span class="closecomment">
<?php _e('Keine Kommentare zugelassen.')?>
</span>
<?php else : ?>
<?php endif; ?>

<h3 id="comments">
  <?php comments_number('Keine Antwort', 'Eine Antwort', '% Meinungen' );?>
  <?php _e('bis jetzt')?>, <a href="#respond"><?php _e('m&ouml;chtest du was dazu sagen?')?></a>
</h3>

<ol class="commentlist">
  <?php foreach ($comments as $comment) : ?>
  <li <?php echo $oddcomment; ?>id="comment-<?php comment_ID() ?>">
    <h5><cite><?php comment_author_link() ?> </cite> <?php _e('meint')?>: </h5>
      
      
    <?php comment_text() ?>
    <small class="commentmetadata"><?php the_time('d') ?> <?php the_time('M') ?>.  <?php the_time('Y') ?>, <?php comment_time() ?> </small> 
    <?php if ($comment->comment_approved == '0') : ?>
    <span class="moderate"><?php _e('Danke f&uuml;r deine Meinung. Sie wird bald freigegeben.')?>.</span>
    <?php endif; ?>
  </li>
  <?php
		/* Changes every other comment to a different class */
		$oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : '';
	?>
  <?php endforeach; /* end for each comment */ ?>
</ol>

<?php if (!('open' == $post-> comment_status)) : ?>
<p class="nocomments"><?php _e('Keine Kommentare zugelassen.')?>.</p>
<?php else : ?>
<?php endif; ?>


<?php else : // this is displayed if there are no comments so far ?>

<?php if ('open' == $post->comment_status) : ?>


<?php else : // comments are closed ?>
<p class="nocomments"><?php _e('Keine Kommentare zugelassen')?>.</p>

<?php endif; ?>

<?php endif; ?>
<?php if ('open' == $post->comment_status) : ?>
<h3 id="respond"><?php _e('Dein Senf dazu')?></h3>
<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p><?php _e('Du musst')?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>"><?php _e('eingeloggt')?></a> <?php _e('sein um hier posten zu d&uuml;rfen.')?>.</p>
<?php else : ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
  <?php if ( $user_ID ) : ?>
  <p><a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a> <?php _e('schreibt hier')?>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Vom Account abmelden"><?php _e('Abmelden')?> &raquo;</a></p>
  <?php else : ?>
  <p>
    <input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
    <label for="author"><small><?php _e('Name')?>
    <?php if ($req) echo "(ben&ouml;tigt)"; ?>
    </small></label>
  </p>
  <p>
    <input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
    <label for="email"><small><?php _e('E-Mail (wird nicht ver&ouml;ffentlicht,')?>
    <?php if ($req) echo "(ben&ouml;tigt)"; ?>
    </small></label>
  </p>
  <p>
    <input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
    <label for="url"><small><?php _e('Webseite')?></small></label>
  </p>
  <?php endif; ?>
  <!--<p><small><strong>XHTML:</strong> Du kannst folgende Tags verwenden: <code><?php echo allowed_tags(); ?></code></small></p>-->
  <p>
    <textarea name="comment" id="comment" cols="80%" rows="10" tabindex="4"></textarea>
  </p>
  <p>
<button value="submit" class="submitBtn"><span>Senf absenden.</span></button>
    <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
  </p>
  <?php do_action('comment_form', $post->ID); ?>
</form>
<?php endif; // If registration required and not logged in ?>
<?php endif; // if you delete this the sky will fall on your head ?>
