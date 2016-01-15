<?php
/**
 * The template for displaying archive pages (e.g. category archives)
 *
 */

get_header(); ?>

    <div class="row">
    <?php if ( have_posts() ) : ?>

        <div class="small-12 columns">
            <h1 class="page-title">
                <span><?php single_cat_title(); ?></span>
            </h1>
        </div>
    </div>

    <?php if( is_category( array( 'pictures', 'press', 'videos', 'board-of-directors' ) ) ) : ?>
    <div class="row">
        <div class="small-12 columns">
            <ul class="medium-block-grid-3">
    <?php endif; ?>

    <?php
    // Start the Loop.
    while ( have_posts() ) : the_post();

        get_template_part( 'content', get_post_format() );

    // End the loop.
    endwhile; ?>

    <?php if( is_category( array( 'pictures', 'press', 'videos', 'board-of-directors' ) ) ) : ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>
    
    <?php // If no content, include the "No posts found" template.
    else :
        get_template_part( 'content', 'none' );
    
    endif;
    ?>

<?php get_footer(); ?>
