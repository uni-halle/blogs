<?php
/**
 */

$p_post = get_post();

$p_post->custom = get_post_custom($p_post->ID);;
$p_color    = (isset($p_post->custom['ptl-post-color']) && isset($p_post->custom['ptl-post-color'][0]))?$p_post->custom['ptl-post-color'][0]:"#f35";
$p_content  = $p_post->post_content;
$p_content  = apply_filters( 'the_content', $p_post->post_content );
get_header(); ?>
<link rel="stylesheet" href="<?php echo POST_TIMELINE_URL_PATH ?>public/css/ptl-single-page.css" type="text/css">
<div class="p-tl-cont timeline_section ptl-detail-pg">
    <div class="container-min container single-post-timeline">
            <?php the_title( '<h1 class="p-timeline-title text-center" style="color:'.$p_color.';">', '</h1>' ); ?>
            <div class="p-timeline-img text-center">
            <?php echo get_the_post_thumbnail(); ?>
            </div>
            <div class="p-timeline-content text-center">
                <?php echo $p_content; ?>
            </div><!-- .entry-content -->
            <?php

                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
            ?>
    </div>
</div>
<!-- .content-area -->
<?php get_footer();
