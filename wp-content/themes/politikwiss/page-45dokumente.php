<?php /* Template Name: Master 45 Dokumente*/ ?>
<?php get_header('45'); ?> 
<h1 class="sr-only">Hier finden Sie einen Überblick über die verfügbaren Dokumente</h1>
<div id="dockumente" class="container main_content_container">
    <?php if (have_posts() ) : while (have_posts()) : the_post(); ?>
    <?php the_content(); ?>
    <?php endwhile; else: ?>
    <?php endif; ?>
</div>
</main> <!-- Ende von Main -->
<?php get_footer('45'); ?>
