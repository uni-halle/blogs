<?php
/**
 * The template for displaying search results pages.
 *
 */

get_header(); ?>

    <?php if ( have_posts() ) : ?>

        <h1 class="page-title">
            <span><?php printf( __( 'Search Results for: %s', 'twentyfifteen' ), get_search_query() ); ?></span>
        </h1>

        <?php
        // Start the loop.
        while ( have_posts() ) : the_post(); ?>

            <?php
            /*
             * Run the loop for the search to output the results.
             * If you want to overload this in a child theme then include a file
             * called content-search.php and that will be used instead.
             */
            get_template_part( 'content', 'search' );

        // End the loop.
        endwhile;

    // If no content, include the "No posts found" template.
    else :
        get_template_part( 'content', 'none' );

    endif;
    ?>

<?php get_footer(); ?>
