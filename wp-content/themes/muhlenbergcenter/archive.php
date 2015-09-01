<?php
/**
 * The template for displaying archive pages (e.g. category archives)
 *
 */

get_header(); ?>

<div class="row">
    <?php if ( have_posts() ) : ?>
    
        <div class="small-12 columns">
            <?php
                the_archive_title( '<h1 class="page-title"><span>', '</span></h1>' );
            ?>
        </div>
    
        <?php
        // Start the Loop.
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
