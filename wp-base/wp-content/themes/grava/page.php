<?php get_header(); ?>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="post" id="post-<?php the_ID(); ?>">
        <h2>
          <a href="<?php the_permalink() ?>" class="toggler" title="&raquo;<?php the_title(); ?>&laquo; lesen...">
            <?php the_title(); ?>  
          </a>
         </h2>
          <div class="entry">
            <?php the_content('<p class="serif"><strong>Den ganzen Beitrag lesen...</strong></p>'); ?>
            <?php wp_link_pages(array('before' => '<p><strong>Seiten:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
            <?php edit_post_link('Seite bearbeiten', '<p class="postmetadata">', '</p>'); ?>
          </div>
        </div>
    <?php endwhile; else: ?>
      <h2><a href="<?php echo get_option('home'); ?>/">Fehler!</a></h2>
      <p>Du suchst gerade nach etwas, was zumindest hier nicht zu finden ist. Aber du kannst gerne noch einmal danach suchen:</p>
      <h3>Suche</h3>
      <?php include (TEMPLATEPATH . "/searchform.php"); ?>
    <?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
