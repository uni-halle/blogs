<?php get_header(); ?>
      <h2><a href="<?php echo get_option('home'); ?>/">Fehler 404 - nichts gefunden!</a></h2>
      <p>Du suchst gerade nach etwas, was zumindest hier nicht zu finden ist. Aber du kannst gerne nochmal danach suchen:</p>
      <?php include (TEMPLATEPATH . "/searchform.php"); ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
