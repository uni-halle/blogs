<?php
/**
 * The template for displaying search results pages.
 *
 */

get_header(); ?>

    <?php if (have_posts()) : ?>

        <h1 class="page-title">
            <span><?php printf(__('Search Results for: %s', 'muhlenbergcenter'), get_search_query()); ?></span>
        </h1>

        <?php
        while (have_posts()) : the_post();

            get_template_part('content', 'search');

        endwhile;

    else :

        get_template_part('content', 'none');

    endif;
    ?>

<?= get_footer(); ?>
