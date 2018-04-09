<?php
/**
 * The template for displaying pages
 *
 */

get_header(); ?>

    <?php
    while (have_posts()) : the_post();

        get_template_part('content', 'page');

    endwhile;
    ?>

<?= get_footer(); ?>
