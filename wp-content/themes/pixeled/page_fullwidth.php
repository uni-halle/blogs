<?php
/*
Template Name: Page Full Width
*/
?>

<?php get_header(); ?>

<div id="main">

<div id="contentwrapper2">

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

<div class="topPost">
  <h2 class="topTitle"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
  <div class="topContent"><?php the_content('(weiterlesen...)'); ?></div>
<div class="cleared"></div>
</div> <!-- Closes topPost -->


<?php endwhile; ?>

<?php else : ?>

<div class="topPost">
  <h2 class="topTitle"><a href="<?php the_permalink() ?>">Nichts gefunden</a></h2>
  <div class="topContent"><p>Sorry, aber du suchst gerade nach etwas, was es hier nicht gibt. Versuche es doch mal mit <a href="#searchform">dieser Form</a>...</p></div>
</div> <!-- Closes topPost -->

<?php endif; ?>

</div> <!-- Closes contentwrapper2-->


<div class="cleared"></div>

</div><!-- Closes Main -->


<?php get_footer(); ?>