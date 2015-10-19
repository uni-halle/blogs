<?php
/**
 * The template part for displaying a message that posts cannot be found
 *
 */
?>

<section class="small-12 columns">
    <h1 class="page-title">
        <span><?php _e( 'Nothing Found', 'muhlenbergcenter' ); ?></span>
    </h1>

    <div class="content">

        <?php if ( is_search() ) : ?>

            <p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'muhlenbergcenter' ); ?></p>
            <?php get_search_form(); ?>

        <?php else : ?>

            <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'muhlenbergcenter' ); ?></p>
            <?php get_search_form(); ?>

        <?php endif; ?>

    </div>
</section>
