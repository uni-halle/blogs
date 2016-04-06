<?php
/**
 * The template for displaying the footer
 * Contains the closing of the "page-main" and all content after.
 *
 */
?>

            </main>

        </div>
        <!-- End: Page Wrap -->

        <footer class="page-footer" role="contentinfo">
            <div class="row footer-content">

            <?php if (is_active_sidebar('sidebar-footer-1')) : ?>
                <?php dynamic_sidebar('sidebar-footer-1'); ?>
            <?php endif; ?>

            <?php if (is_active_sidebar('sidebar-footer-2')) : ?>
                <?php dynamic_sidebar('sidebar-footer-2'); ?>
            <?php endif; ?>

            <?php if (is_active_sidebar('sidebar-footer-3')) : ?>
                <?php dynamic_sidebar('sidebar-footer-3'); ?>
            <?php endif; ?>

            <div class="medium-4  small-12  columns">
                <a href="http://uni-halle.de" target="_blank">
                    <img src="<?= esc_url(get_template_directory_uri()); ?>/img/mlu-signet.png" alt="Martin-Luther-Universität Halle-Wittenberg">
                </a>
                <p class="copyright">&copy; <?php $current_year = date("Y"); echo $current_year; ?> <?= bloginfo('name'); ?></p>
            </div>

            </div>
        </footer>

    <?= wp_footer(); ?>

    </body>
</html>
