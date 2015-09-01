<?php
/**
 * The template used for displaying posts with gallery format
 *
 */
?>

<li id="post-<?php the_ID(); ?>" class="media">
    <?php if ( has_post_thumbnail() ) : ?>
    <a href="<?php the_permalink(); ?>">
        <?php the_post_thumbnail( 'thumb' ); ?>
    </a>
    <?php endif; ?>
    <p>
        <a href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
        </a>
    </p>
</li>
