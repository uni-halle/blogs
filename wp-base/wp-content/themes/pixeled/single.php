<?php get_header(); ?>

<div id="main">

<div id="contentwrapper">

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

<div class="topPost">
  <h2 class="topTitle"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
  <p class="topMeta">von <?php the_author_posts_link(); ?> am <?php the_time('d M. Y') ?>, gespeichert unter <?php the_category(', '); ?></p>
  <div class="topContent"><?php the_content('(weiterlesen...)'); ?></div>
  <span class="topTags"><?php the_tags('<em>:</em>', ', ', ''); ?></span>
<div class="cleared"></div>
</div> <!-- Closes topPost -->

<div id="numberofcomments"><?php comments_number('Schreib ein Kommentar...', '1 Kommentar', '% Kommentare' );?></div>
<div id="comment">
<?php comments_template(); ?>
</div> <!-- Closes Comment -->

<?php endwhile; ?>

<?php else : ?>

<div class="topPost">
  <h2 class="topTitle"><a href="<?php the_permalink() ?>">Nichts gefunden</a></h2>
  <div class="topContent"><p>Sorry, aber du suchst gerade nach etwas, was es hier nicht gibt. Versuche es doch mal mit <a href="#searchform">dieser Form</a>...</p></div>
</div> <!-- Closes topPost -->

<?php endif; ?>

</div> <!-- Closes contentwrapper-->


<?php get_sidebar(); ?>
<div class="cleared"></div>

</div><!-- Closes Main -->


<?php get_footer(); ?>