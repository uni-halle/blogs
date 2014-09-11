<?php // Do not delete these lines
if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
  die ('Bitte diese Seite nicht direkt aufrufen. Danke!');

if (!empty($post->post_password)) { // if there's a password
  if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) { // and it doesn't match the cookie
    ?>
        <p class="info">Auch die Kommentare sind durch das Passwort gesch&uuml;tzt.</p>
    <?php
    return;
  }
}

$oddcomment = 'class="odd" ';
?>
<?php if ($comments) : ?>
    <h3 id="comments">
      <?php comments_number('Keine Kommentare', 'Ein Kommentar', '% Kommentare' );?> zu: <br />&raquo;<?php the_title(); ?>&laquo;
    </h3>

    <ol id="commentlist">
    <?php foreach ($comments as $comment) : ?>
        <li <?php 
              if ($comment->comment_author_email == get_the_author_email()) 
                echo 'class="authorcomment" '; 
              else 
                echo $oddcomment; 
            ?> id="comment-<?php comment_ID() ?>">
            <?php if ($comment->comment_approved == '0') : ?>
            <p class="info">Achtung: Der Kommentar mu&szlig; erst noch freigegeben werden.</p>
            <?php endif; ?>
            <?php 
              $gravDef = get_bloginfo('stylesheet_directory') . '/imgs/gravatar.png';
              $gravUrl = "http://www.gravatar.com/avatar.php?gravatar_id=" . 
                          md5(get_comment_author_email()) . 
                          "&amp;default=" . urlencode($gravDef); ?>
            <img src="<?php echo $gravUrl; ?>" alt="Gravatar von <?php comment_author(); ?>" class="gravatar"/>
            <?php comment_text(); ?>
            <p class="commentmetadata">
              <cite><?php comment_author_link(); ?></cite>
              <a href="#comment-<?php comment_ID() ?>" title="#comment-<?php comment_ID() ?>">
              am <?php comment_date('j. F Y'); ?> um <?php comment_time('H:i'); ?> Uhr</a> 
              <?php edit_comment_link('Bearbeiten',' | ',''); ?>
            </p>
        </li>
    <?php 
        /* Changes every other comment to a different class */ 
        $oddcomment = ( empty( $oddcomment ) ) ? 'class="odd" ' : ''; 
    ?>
    <?php endforeach; // end for each comment ?>
    </ol>

 <?php else : // this is displayed if there are no comments so far ?>

  <?php if ('open' == $post->comment_status) : ?>
     <?php else : // comments are closed ?>
        <h3>Die Kommentarfunktion ist deaktiviert.</h3>
    <?php endif; ?>
<?php endif; ?>

<?php if ('open' == $post->comment_status) : ?>

  <h3 id="respond">Einen Kommentar schreiben</h3>
  <?php if ( get_option('comment_registration') && !$user_ID ) : ?>
    <p class="info">Du mu&szlig;t <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">angemeldet</a> sein, um kommentieren zu k&ouml;nnen.</p>
  <?php else : ?>
  
  <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
    <ol id="commentsformlist">
      <li>
        <label for="comment">Kommentar</label>
        <textarea name="comment" id="comment" cols="50" rows="10" tabindex="1"></textarea>
      </li>
    <?php if ( $user_ID ) : ?>
      <li>
        Angemeldet als: <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. 
        <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Von diesem Account abmelden">Abmelden &raquo;</a>
      </li>      
    <?php else : ?>
      <li>
        <label for="author">Name<?php if ($req) echo "*"; ?></label>
        <input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="2" />
      </li>
      
      <li>
        <label for="email">E-Mail-Adresse<?php if ($req) echo "*"; ?></label>
        <input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="3" />
      </li>
      <li>
        <label for="url">Webseite</label>
        <input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="4" />
      </li>
    <?php 
      /* Math Comment Spam Protection Plugin */
      if ( function_exists('math_comment_spam_protection') ) { 
        $mcsp_info = math_comment_spam_protection();
      ?>
      <li>
        <label for="mcspvalue">Summe von <?php echo $mcsp_info['operand1'] . ' + ' . $mcsp_info['operand2'] . ' ?' ?></label>
        <input type="text" name="mcspvalue" id="mcspvalue" value="" size="22" tabindex="5" />
        <input type="hidden" name="mcspinfo" value="<?php echo $mcsp_info['result']; ?>" />
      </li>
      <?php } ?>
    <?php endif; ?>
      <li id="lastcommentsformli">
        <input name="submit" type="submit" id="submit" tabindex="10" value="Absenden" title="Kommentar eintragen"/>
        <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
        <?php do_action('comment_form', $post->ID); ?>
      </li>
    </ol>
  </form>

<?php endif; // If registration required and not logged in ?>
<?php endif; // if you delete this the sky will fall on your head ?>