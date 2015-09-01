<?php
/**
 * The main template file
 *
 */

get_header(); ?>

<div class="row">

    <?php if ( have_posts() ) :

        // Start the loop.
        while ( have_posts() ) : the_post();

            get_template_part( 'content', get_post_format() );

        // End the loop.
        endwhile;

        // If no content, include the "No posts found" template.
        else :
            get_template_part( 'content', 'none' );

    endif;
    ?>

</div>

<?php get_footer(); ?>
