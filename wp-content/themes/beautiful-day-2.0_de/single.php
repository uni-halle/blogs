<?php get_header(); ?>

<div class="main">
  <div class="content">
    <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
    <div class="post" id="post-<?php the_ID(); ?>">
      <h1>
        <?php the_title(); ?>
      </h1>
      <div class="descr">
        <?php the_time('j. F Y') ?>
        von
        <?php the_author() ?>
      </div>
      <div class="entry">
        <?php the_content('Den ganzen Beitrag lesen &raquo;'); ?>
      </div>
      <p class="info">
        <?php the_tags('Tags: ', ', ', '<br />'); ?>
        Abgelegt in
        <?php the_category(', ') ?>
        <?php edit_post_link('Bearbeiten',' | ',''); ?>
      </p>
    </div>
    <?php comments_template(); ?>
    <?php endwhile; ?>
    <p align="center">
      <?php next_posts_link('&laquo; Vorherige Eintr&auml;ge') ?>
      <?php previous_posts_link('N&auml;chste Eintr&auml;ge &raquo;') ?>
    </p>
    <?php else : ?>
    <h2 align="center">Nicht gefunden</h2>
    <p align="center">Sorry, aber du suchst gerade nach etwas, was hier nicht ist.</p>
    <?php endif; ?>
  </div>
  <?php get_sidebar(); ?>
  <div class="clearer"></div>
</div>
<?php get_footer(); ?>
