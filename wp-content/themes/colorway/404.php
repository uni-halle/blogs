<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage ColorWay
 * @since ColorWay 1.0
 */
get_header();
?>
    <div class="row content">
        <div class="content-wrap">
            
            <div class="fullwidth">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'colorway'); ?></h1>
                </header><!-- .page-header -->
                <div class="page-content">
                    <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'colorway'); ?></p>

                    <?php get_search_form(); ?>

                </div><!-- .page-content -->
            </div>
        </div>
    </div><!-- .wrap -->
</div>
</div>

<?php
get_footer();
