<?php
/**
 * The template for displaying all single posts and attachments
 *
 */

get_header(); ?>

<div class="row">

    <?php
    while ( have_posts() ) : the_post();

        if ( has_post_format( 'gallery' ) ) : ?>
        <div class="small-12 columns">
            <?php the_title( '<h1 class="page-title"><span>', '</span></h1>' );?>
            <div class="row">
                <div class="small-12 columns">
                    <?php the_content();?>
                </div>
            </div>
        </div>
        <?php else : ?>
        
        <?php get_template_part( 'content', get_post_format() );?>
        
        <?php endif;

    endwhile;
    ?>

</div>

<?php get_footer(); ?>
