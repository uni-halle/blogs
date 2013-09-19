<?php get_header(); ?>
    <?php if (have_posts()) : ?>
        <h2><a href="#hometop">
          Suchergebnisse f&uuml;r &raquo;<?php the_search_query(); ?>&laquo;
        </a></h2>
        <?php while (have_posts()) : the_post(); ?>
        <div class="post">
          <h3 id="post-<?php the_ID(); ?>">
            <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
              <?php the_title(); ?>
          </a></h3>
          <div class="entry">
            <?php the_excerpt() ?>
            <p class="postmetadata">
              <?php if (function_exists('the_tags')) the_tags('Tags: ', ', ', '<br />'); ?>
              Kategorie: <?php the_category(', ') ?> |
              <?php comments_popup_link('Keine Kommentare', 'Ein Kommentar', '% Kommentare'); ?>
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
