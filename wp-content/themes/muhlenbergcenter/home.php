<?php
/**
 * The home template file
 *
 */

get_header(); ?>

<div class="row home" data-equalizer data-equalize-on="medium">
    <div class="small-12 columns">
        <ul class="medium-block-grid-3">
    
        <?php
            /* Shortcode: 3 aktuelle Events mit Tag "home" ausgeben */
            echo do_shortcode(
                '[events_list scope="future" tag="home" limit="3" orderby="event_start_date" order="ASC"]
                    <li data-equalizer-watch>
                        <article class="event-teaser">
                            {has_image}
                            <div class="thumbnail">
                                <a href="#_EVENTURL" title="More about #_EVENTNAME">
                                    #_EVENTIMAGE
                                </a>
                            </div>
                            {/has_image}
                            {no_image}
                            <div class="thumbnail">
                                <a href="#_EVENTURL" title="More about #_EVENTNAME">
                                    #_CATEGORYIMAGE
                                </a>
                            </div>
                            {/no_image}
                            <p class="meta-info">
                                <span class="meta-info__category">#_CATEGORYNAME</span> |
                                <span class="meta-info__date">#Y-#m-#d</span>
                            </p>
                            <h2 class="event-teaser__title">#_EVENTLINK</h2>
                            <ul class="no-bullet">
                                <!--
                                <li>#_ATT{consultant_name}</li>
                                <li>#_EVENTDATES</li>
                                {has_time}<li>#_EVENTTIMES</li>{/has_time}
                                -->
                                <li>#_LOCATIONADDRESS</li>
                                <li>#_LOCATIONPOSTCODE #_LOCATIONTOWN</li>
                            </ul>
                            <p>
                                <a href="#_EVENTURL" title="More about #_EVENTNAME">More information</a>
                            </p>
                        </article>
                    </li>
                [/events_list]'
            );
        ?>
    
        </ul>
    </div>
</div>

<?php /* get the posts for person slider */
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
