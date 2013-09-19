<?php get_header(); ?>

<div id="main">

<div id="contentwrapper">

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

<div class="topPost">
  <h2 class="topTitle"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
  <p class="topMeta">von <?php the_author_posts_link(); ?> am <?php the_time('d M. Y') ?>, gespeichert unter <?php the_category(', '); ?></p>
  <div class="topContent"><?php the_content('(weiterlesen...)'); ?></div>
  <span class="topComments"><?php comments_popup_link('Schreib ein Kommentar', '1 Kommentar', '% Kommentare'); ?></span>
  <span class="topTags"><?php the_tags('<em>:</em>', ', ', ''); ?></span>
  <span class="topMore"><a href="<?php the_permalink() ?>">mehr...</a></span>
<div class="cleared"></div>
</div> <!-- Closes topPost --><br/>

<?php endwhile; ?>

<?php else : ?>

<div class="topPost">
  <h2 class="topTitle"><a href="<?php the_permalink() ?>">Nichts gefunden</a></h2>
  <div class="topContent"><p>Sorry, aber du suchst gerade nach etwas, was es hier nicht gibt. Versuche es doch mal mit <a href="#searchform">dieser Form</a>...</p></div>
</div> <!-- Closes topPost -->

<?php endif; ?>

<div id="nextprevious">
<div class="alignleft"><?php posts_nav_link('','','&laquo; &auml;ltere Eintr&auml;ge') ?></div>
<div class="alignright"><?php posts_nav_link('','N&auml;chste Eintr&auml;ge &raquo;','') ?></div>
<div class="cleared"></div>
</div>
</div> <!-- Closes contentwrapper-->



<?php get_sidebar(); ?>
<div class="cleared"></div>

</div><!-- Closes Main -->


<?php get_footer(); ?>