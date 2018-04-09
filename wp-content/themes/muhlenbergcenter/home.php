<?php
/**
 * The home template file
 *
 */

get_header(); ?>

<div class="row  home">
    <div class="small-12  columns">
        <?php
            $researchconditions = [
                'category_name'  => 'key-research-areas',
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'posts_per_page' => 3
            ];
            $researchtopics = new WP_Query($researchconditions);
            if ($researchtopics->have_posts()) : ?>

            <ul class="medium-block-grid-3  research-topics">
                <?php
                    while($researchtopics->have_posts()) : $researchtopics->the_post();
                    $pagelink = get_post_meta(get_the_ID(), 'Page Link', true);
                ?>
                <li class="research-topics__item">
                    <a href="<?= the_permalink(); ?>" class="research-topics__link" title="<?php sprintf(_e('More about ', 'muhlenbergcenter'), the_title()); ?>">
                        <?php if(has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail([285,180]); ?>
                        <?php endif ?>
                        <p class="research-topics__title"><?= the_title(); ?></p>
                    </a>
                </li>
                <?php endwhile ?>
            </ul>

        <?php endif;
            wp_reset_postdata();
        ?>
    </div>
</div>

<div class="row  home" data-equalizer data-equalize-on="medium">
    <div class="small-12  columns">
        <h1 class="page-title">
            <span><?= _e('Events', 'muhlenbergcenter'); ?></span>
        </h1>
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

<?= get_footer(); ?>
