<?php get_header(); ?>

<?php if (have_posts() ) : while (have_posts()) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; else: ?>
<p><?php _e('Sorry nix hier'); ?></p>
<?php endif; ?>

<?php get_footer(); ?>