<?php
/**
 * The home template file
 *
 */

get_header(); ?>

<div class="row home" data-equalizer>

    <?php
        /* Shortcode: 3 aktuelle Events mit Tag "home" ausgeben */
        echo do_shortcode(
            '[events_list scope="future" tag="home" limit="3"]
                <article class="medium-4 columns event teaser" data-equalizer-watch>
                    {has_image}#_EVENTIMAGE{/has_image}
                    {no_image}#_CATEGORYIMAGE{/no_image}
                    <h2>#_EVENTLINK</h2>
                    <ul class="no-bullet">
                        <li>#_EVENTDATES</li>
                        {has_time}<li>#_EVENTTIMES</li>{/has_time}
                        <li>#_LOCATIONADDRESS</li>
                        <li>#_LOCATIONPOSTCODE, #_LOCATIONTOWN</li>
                    </ul>
                    <p>
                        <a href="upcoming-events/">Show all upcoming events</a>
                    </p>
                </article>
            [/events_list]'
        );
    ?>

</div>

<?php /* get the slider posts */
$personSliderConditions = array (
    'category_name' => 'personenslider',
    'post_type'     => 'post',
    'post_status'   => 'publish',
    'orderby'       => 'date',
    'nopaging'      => true
);
$the_query = new WP_Query( $personSliderConditions );
if ( $the_query->have_posts() ) :
?>
<div class="row persons visible-for-medium-up">
    <div class="small-12 columns">
        <ul data-orbit data-options="animation:slide;
                                     timer: false;
                                     animation_speed: 800;
                                     navigation_arrows: true;
                                     caption_class: persons-text;
                                     next_class: persons-next;
                                     prev_class: persons-prev;">
            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
            <li>
                <div class="persons-text">
                    <?php the_content(); ?>
                </div>
                <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'full' ); ?>
                <?php endif; ?>
            </li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>
<?php
    endif;
    wp_reset_postdata();
?>

<?php get_footer(); ?>
