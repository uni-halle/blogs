<?php
/**
 * The default template for displaying single events
 *
 */
get_header(); ?>

<article id="post-<?php the_ID(); ?>" class="event">

    <?php the_content(); ?>

</article>

<?php get_footer(); ?>