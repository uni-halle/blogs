<?php get_header(); ?>
  <?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
        <div class="post" id="post-<?php the_ID(); ?>">
          <h2>
            <a href="#post-<?php the_ID(); ?>" class="toggler" title="&raquo;<?php the_title(); ?>&laquo; lesen...">
              <?php the_title(); ?>
              <span><?php the_time('j. F Y'); ?> um <?php the_time('H:i'); ?> Uhr</span>
            </a>
          </h2>
          <div class="entry">
            <?php the_content('Den ganzen Beitrag lesen &#187;'); ?>
            <p class="postmetadata">
              <?php if (function_exists('the_tags')) the_tags('Tags: ', ', ', '<br />'); ?>
              Kategorie: <?php the_category(', ') ?> |
              <?php comments_popup_link('Keine Kommentare', 'Ein Kommentar', '% Kommentare'); ?> |
              <a href="<?php the_permalink() ?>" rel="bookmark" title="Permalink f&uuml;r: <?php the_title(); ?>">Permalink</a>
              <?php edit_post_link('Bearbeiten',' | ',' '); ?>
            </p>
          </div>
        </div>
      <?php endwhile; ?>

      <div id="navigation">
          <?php next_posts_link('<img src="' . get_bloginfo('stylesheet_directory') . '/imgs/arrow_left.png" class="button" alt="&Auml;ltere Beitr&auml;ge" title="&Auml;ltere Beitr&auml;ge" />') ?>
          <?php previous_posts_link('<img src="' . get_bloginfo('stylesheet_directory') . '/imgs/arrow_right.png" class="button" alt="Neuere Beitr&auml;ge" title="Neuere Beitr&auml;ge" />') ?>
      </div>
      
  <?php else : ?>
      <h2><a href="<?php echo get_option('home'); ?>/">Fehler!</a></h2>
      <p>Du suchst gerade nach etwas, was zumindest hier nicht zu finden ist. Aber du kannst gerne noch einmal danach suchen:</p>
      <h3>Suche</h3>
      <?php include (TEMPLATEPATH . "/searchform.php"); ?>
  <?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
