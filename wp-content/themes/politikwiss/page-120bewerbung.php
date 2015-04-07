<?php /* Template Name: Master 120 Bewerbung*/ ?>
<?php get_header('120'); ?> 
<section id="bewerbung" class="container main_content_container">
    <h1><span class="sr-only">Bewerbung</span></h1>
<?php if (have_posts() ) : while (have_posts()) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; else: ?>
<p><?php _e('Sorry nix hier'); ?></p>
<?php endif; ?>
</section>
</main> <!-- Ende von Main -->
<?php get_footer('120'); ?>



    
