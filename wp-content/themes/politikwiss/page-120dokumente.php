<?php /* Template Name: Master 120 Dokumente*/ ?>
<?php get_header('120'); ?> 
<h1 class="sr-only">Hier finden Sie einen Überblick über die Dokumente des Masters 120</h1>
<div id="dockumente" class="container main_content_container">
<?php if (have_posts() ) : while (have_posts()) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; else: ?>
<p><?php _e('Sorry nix hier'); ?></p>
<?php endif; ?>
</div>
</main> <!-- Ende von Main -->
<?php get_footer('120'); ?>
