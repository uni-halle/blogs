<?php
/**
 * The template used for displaying posts with video format
 *
 */
?>

<li id="post-<?php the_ID(); ?>" class="media">
    <div class="flex-video">
        <?php echo get_the_content(); ?>
    </div>
    <p><?php the_title(); ?></p>
</li>
