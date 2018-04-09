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

        <footer class="page-footer <?php if (is_home() || is_front_page()) {
                echo ' page-footer--home';
            } else {
                echo ' page-footer--main';
            } ?>" role="contentinfo">

            <?php if (is_home() || is_front_page()) : ?>
                <?php /* get the posts for person slider */
                $personSliderConditions = [
                    'category_name' => 'personenslider',
                    'post_type'     => 'post',
                    'post_status'   => 'publish',
                    'orderby'       => 'date',
                    'nopaging'      => true
                ];
                $the_query = new WP_Query($personSliderConditions);
                if ($the_query->have_posts()) :
                ?>
                <div class="person-slider  visible-for-medium-up">
                    <div class="small-12  columns">
                        <ul data-orbit data-options="animation: slide;
                                                     timer: false;
                                                     animation_speed: 800;
                                                     navigation_arrows: true;
                                                     next_class: person-slider__next;
                                                     prev_class: person-slider__prev;" class="person-slider__list">
                            <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                            <li class="person-slider__item">
                                <div class="person-slider__content">
                                    <div class="person-slider__text">
                                        <?= the_content(); ?>
                                    </div>
                                    <?php if (has_post_thumbnail()) : ?>
                                    <div class="person-slider__image">
                                        <?php the_post_thumbnail('full'); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
                <?php
                    endif;
                    wp_reset_postdata();
                ?>
            <?php endif; ?>

            <div class="footer-content">

            <?php if (is_active_sidebar('sidebar-footer-1')) {
                dynamic_sidebar('sidebar-footer-1');
            }
            if (is_active_sidebar('sidebar-footer-2')) {
                dynamic_sidebar('sidebar-footer-2');
            }
            if (is_active_sidebar('sidebar-footer-3')) {
                dynamic_sidebar('sidebar-footer-3');
            } ?>

            <div class="medium-4  small-12  columns">
                <a href="http://uni-halle.de" target="_blank" rel="noopener">
                    <img src="<?= esc_url(get_template_directory_uri()); ?>/img/mlu-signet.png" alt="Martin-Luther-Universität Halle-Wittenberg">
                </a>
                <p class="copyright">&copy; <?php $current_year = date("Y"); echo $current_year; ?> <?= bloginfo('name'); ?></p>
            </div>

            </div>
        </footer>

    <?= wp_footer(); ?>

    </body>
</html>
