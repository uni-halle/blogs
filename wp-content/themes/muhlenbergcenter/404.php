<?php
/**
 * The template for displaying 404 pages (not found)
 *
 */

get_header(); ?>

<div class="row">

    <section class="small-12 columns">
        <h1 class="page-title">
            <span><?= _e( 'Oops! That page can&rsquo;t be found.', 'muhlenbergcenter' ); ?></span>
        </h1>

        <div class="content">
            <p><?= _e( 'It looks like nothing was found at this location. Maybe try a search?', 'muhlenbergcenter' ); ?></p>

            <?= get_search_form(); ?>
        </div>
    </section>

</div>

<?= get_footer(); ?>
