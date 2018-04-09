<?php
/**
 * The template part for displaying results in search pages
 *
 */
?>

<article class="row  event-item">

    <?php if (has_post_thumbnail()) : ?>
    <div class="medium-4  columns">
        <?= the_post_thumbnail(); ?>
    </div>

    <div class="medium-8  columns">
    
    <?php else : ?>
    <div class="small-12  columns">
    <?php endif ?>

        <h2 class="event-item__title">
            <?php the_title(sprintf('<a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a>'); ?>
        </h2>

        <?= the_excerpt(); ?>
    </div>

</article>
