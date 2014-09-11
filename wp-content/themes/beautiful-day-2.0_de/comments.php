<?php // Do not delete these lines
 if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
  die ('Bitte diese Seite nicht direkt aufrufen. Danke!');

        if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_'.$cookiehash] != $post->post_password) {  // and it doesn't match the cookie
    ?>

<p class="nocomments">
  <?php _e("Auch die Kommentare sind durch das Passwort gesch&uuml;tzt."); ?>
<p>
  <?php
    return;
            }
        }

  /* This variable is for alternating comment background */
  $oddcomment = "graybox";
?>
  <!-- You can start editing here. -->
  <?php if ($comments) : ?>
<h2 id="comments">
  <?php comments_number('Keine Reaktion', 'Eine Reaktion', '% Reaktionen' );?>
</h2>
<ol class="commentlist">
  <?php foreach ($comments as $comment) : ?>
  <li class="<?=$oddcomment;?>" id="comment-<?php comment_ID() ?>"> <strong>
    <?php comment_author_link() ?>
    </strong><br/>
    <p class="commentmetadata"><a href="#comment-<?php comment_ID() ?>" title="">
      <?php comment_date('j. F Y') ?>
      at
      <?php comment_time() ?>
      </a>
      <?php edit_comment_link('Bearbeiten',' ~ ',''); ?>
    </p>
    <?php comment_text() ?>
  </li>
  <?php /* Changes every other comment to a different class */ 
   if("graybox" == $oddcomment) {$oddcomment="";}
   else { $oddcomment="graybox"; }
  ?>
  <?php endforeach; /* end for each comment */ ?>
</ol>
<?php else : // this is displayed if there are no comments so far ?>
<?php if ('open' == $post-> comment_status) : ?>
<!-- If comments are open, but there are no comments. -->
<?php else : // comments are closed ?>
<!-- If comments are closed. -->
<br/>
<p>Kommentarfunktion ist deaktiviert</p>
<?php endif; ?>
<?php endif; ?>
<?php if ('open' == $post-> comment_status) : ?>
<h3 id="respond">Einen Kommentar schreiben</h3>
<form action="<?php echo get_settings('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
  <p>
    <input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" class="styled" />
    <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
    <input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" />
    <label for="author">Name</label>
  </p>
  <p>
    <input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" class="styled" />
    <label for="email">eMail (wird nicht ver&ouml;ffentlicht)</label>
  </p>
  <p>
    <input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" class="styled" />
    <label for="url">Webseite</label>
  </p>
  <!--<p><strong>XHTML:</strong> You can use these tags: <?php echo allowed_tags(); ?></p>-->
  <p>
    <textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4" class="styled"></textarea>
  </p>
  <?php if ('none' != get_settings("comment_moderation")) { ?>
  <p><small><strong>Bitte beachten:</strong> Die Kommentar-Moderation ist eingeschaltet, deshalb k&ouml;nnte Ihr Beitrag etwas sp&auml;ter ver&ouml;ffentlicht werden. Sie brauchen Ihren Kommentar nicht mehrmals abzugeben.</small></p>
  <?php } ?>
  <p>
    <input name="submit" type="submit" id="submit" tabindex="5" value="senden" />
  </p>
</form>
<?php // if you delete this the sky will fall on your head
endif; ?>
