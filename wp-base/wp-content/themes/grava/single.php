<?php get_header(); ?>
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="post" id="post-<?php the_ID(); ?>">
          <h2>
            <a href="<?php the_permalink() ?>" class="toggler" title="&raquo;<?php the_title(); ?>&laquo; lesen...">
              <?php the_title(); ?>
              <span><?php the_time('j. F Y'); ?> um <?php the_time('H:i'); ?> Uhr</span>
            </a>
          </h2>
          <div class="entry">
            <?php the_content('Den ganzen Beitrag lesen &raquo;&raquo;&raquo;'); ?>
            <?php wp_link_pages(array('before' => '<p><strong>Seiten:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
            <p class="postmetadataleft">
              Dieser Beitrag wurde am <?php the_time('l,') ?> den <?php the_time('j. F Y') ?> um <?php the_time('H:i') ?> Uhr 
              in den Kategorien <?php the_category(', ') ?> ver&ouml;ffentlicht<?php if (function_exists('the_tags')) the_tags(' und mit den Schlagworten ', ', ', ' getaggt'); ?>.
              Kommentare zu diesen Eintrag k&ouml;nnen durch den <?php comments_rss_link('RSS 2.0 Feed'); ?> verfolgt werden.
              
              <?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) { ?>
              Du kannst einen <a href="#respond">Kommentar schreiben</a>, oder einen <a href="<?php trackback_url(true); ?>" rel="trackback">Trackback</a> auf deiner Seite einrichten.
              
              <?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) { ?>
              Kommentare sind derzeit geschlossen, aber du kannst dennoch einen <a href="<?php trackback_url(true); ?> " rel="trackback">Trackback</a> auf deiner Seite einrichten.
              
              <?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) { ?>
              Du kannst einen Kommentar hinterlassen. Pingen ist im Augenblick nicht erlaubt.
              
              <?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) { ?>
              Kommentare und Pings sind derzeit nicht erlaubt.
              
              <?php } edit_post_link('Beitrag bearbeiten.',' ',''); ?>
            </p>
          </div>
          <?php comments_template(); ?>
        </div>

    <?php endwhile; else: ?>
      <h2><a href="<?php echo get_option('home'); ?>/">Fehler!</a></h2>
      <p>Du suchst gerade nach etwas, was zumindest hier nicht zu finden ist. Aber du kannst gerne noch einmal danach suchen:</p>
      <h3>Suche</h3>
      <?php include (TEMPLATEPATH . "/searchform.php"); ?>
    <?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
