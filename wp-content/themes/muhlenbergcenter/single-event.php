<?php
/**
 * The default template for displaying single events
 *
 */
get_header(); ?>

<article id="post-<?php the_ID(); ?>" class="event">

    <?php the_content(); ?>

    <?php 
        /*
           Check if custom field 'Program' is used
           if true return content
        */
        $program = get_post_meta(get_the_ID(), 'Program', true);
        if (!empty($program)) :
    ?>

    <section class="row additional-info program">
        <div class="small-12 columns">
            <h2 class="additional-info__title">
                <span class="additional-info__title-inner"><?= _e('Program', 'muhlenbergcenter'); ?></span>
            </h2>

            <div class="additional-info__switch">
                <?= _e('show', 'muhlenbergcenter'); ?>
            </div>

            <?php
                /*
                   Get program content from post in category 'event-program'
                   Get specific post by value of custom field 'Program' in event
                */
                $program_post = [
                    'name'           => $program,
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'category_name'  => 'event-program',
                    'posts_per_page' => 1
                ];
                $program_content = new WP_Query($program_post);
                if ($program_content->have_posts()) :
                while ($program_content->have_posts()) : $program_content->the_post();
            ?>
            <div class="additional-info__content program">
                <?= the_content(); ?>
            </div>
            <?php
                endwhile;
                endif;
                wp_reset_postdata();
            ?>

        </div>
    </section>

    <?php endif; ?>

    <?php 
        /*
           Check if custom field 'Venue' is used
           if true return content
        */
        $venue = get_post_meta(get_the_ID(), 'Venue', true);
        if (!empty($venue)) :
    ?>

    <section class="row additional-info venue">
        <div class="small-12 columns">
            <h2 class="additional-info__title">
                <span class="additional-info__title-inner"><?= _e('Venue', 'muhlenbergcenter'); ?></span>
            </h2>

            <div class="additional-info__switch">
                <?= _e('show', 'muhlenbergcenter'); ?>
            </div>

            <?php
                /*
                   Get program content from post in category 'event-venue'
                   Get specific post by value of custom field 'Venue' in event
                */
                $venue_post = [
                    'name'           => $venue,
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'category_name'  => 'event-venue',
                    'posts_per_page' => 1
                ];
                $venue_content = new WP_Query($venue_post);
                if ($venue_content->have_posts()) :
                while ($venue_content->have_posts()) : $venue_content->the_post();
            ?>
            <div class="row additional-info__content program">
                <?php if(has_post_thumbnail()) : ?>
                <div class="medium-4 columns">
                    <?= the_post_thumbnail(); ?>
                </div>
                <div class="medium-8 columns">
                <?php else : ?>
                <div class="small-12 columns">
                <?php endif; ?>
                    <h3 class="additional-info__subtitle">
                        <?= the_title(); ?>
                    </h3>
                    <?= the_content(); ?>
                </div>
            </div>
            <?php
                endwhile;
                endif;
                wp_reset_postdata();
            ?>

        </div>
    </section>

    <?php endif; ?>

    <?php 
        /*
           Check if custom field 'Accommodation' is used
           if true return content
        */
        $accommodation = get_post_meta(get_the_ID(), 'Accommodation', true);
        if (!empty($accommodation)) :
    ?>

    <section class="row additional-info accommodation">
        <div class="small-12 columns">
            <h2 class="additional-info__title">
                <span class="additional-info__title-inner"><?= _e('Accommodation', 'muhlenbergcenter'); ?></span>
            </h2>

            <div class="additional-info__switch">
                <?= _e('show', 'muhlenbergcenter'); ?>
            </div>

            <?php
                /*
                   Get program content from post in category 'event-accommodation'
                   Get specific post by value of custom field 'Accommodation' in event
                */
                $accommodation_post = [
                    'name'           => $accommodation,
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'category_name'  => 'event-accommodation',
                    'posts_per_page' => 1
                ];
                $accommodation_content = new WP_Query($accommodation_post);
                if ($accommodation_content->have_posts()) :
                while ($accommodation_content->have_posts()) : $accommodation_content->the_post();
            ?>
            <div class="row additional-info__content accommodation">
                <?php if(has_post_thumbnail()) : ?>
                <div class="medium-4 columns">
                    <?= the_post_thumbnail(); ?>
                </div>
                <div class="medium-8 columns">
                <?php else : ?>
                <div class="small-12 columns">
                <?php endif; ?>
                    <h3 class="additional-info__subtitle">
                        <?= the_title(); ?>
                    </h3>
                    <?= the_content(); ?>
                </div>
            </div>
            <?php
                endwhile;
                endif;
                wp_reset_postdata();
            ?>

        </div>
    </section>

    <?php endif; ?>

    <?php 
        /*
           Check if custom field 'Directions' is used
           if true return content
        */
        $directions_map = get_post_meta(get_the_ID(), 'Directions Map', true);
        $directions = get_post_meta(get_the_ID(), 'Directions', true);
        if (!empty($directions)) :
    ?>

    <section class="row additional-info directions">
        <div class="small-12 columns">
            <h2 class="additional-info__title">
                <span class="additional-info__title-inner"><?= _e('Directions', 'muhlenbergcenter'); ?></span>
            </h2>

            <div class="additional-info__switch">
                <?= _e('show', 'muhlenbergcenter'); ?>
            </div>

            <div class="row additional-info__content directions">
                <?php
                    /*
                       Get program content from post in category 'event-directions'
                       Get specific post by value of custom field 'Directions' in event
                    */
                    $directions_post = [
                        'name'           => $directions,
                        'post_type'      => 'post',
                        'post_status'    => 'publish',
                        'category_name'  => 'event-directions',
                        'posts_per_page' => 1,
                    ];
                    $directions_content = new WP_Query($directions_post);
                    if ($directions_content->have_posts()) :
                    while ($directions_content->have_posts()) : $directions_content->the_post();
                ?>
                    <div class="medium-4 columns">
                        <?php 
                            /* Check for custom field 'Directions Map'
                               if it is used return map, else return directions icon
                            */
                            if (!empty($directions_map)) :
                        ?>
                        <iframe src="https://www.google.com/maps/d/embed?mid=<?= $directions_map ?>" width="285"
                                height="175" class="iframe  iframe--map"></iframe>
                        <?php else : ?>
                        <img src="<?= esc_url(get_template_directory_uri()); ?>/img/symbol-directions.png" alt="symbol directions" />
                        <?php endif; ?>
                    </div>
                    <div class="medium-8 columns">
                        <?= the_content(); ?>
                    </div>
                <?php
                    endwhile;
                    endif;
                    wp_reset_postdata();
                ?>
            </div>

        </div>
    </section>

    <?php endif; ?>

    <?php 
        /*
           Check if custom field 'Contact' is used
           if true return content
        */
        $contact = get_post_meta(get_the_ID(), 'Contact', true);
        if (!empty($contact)) :
    ?>

    <section class="row additional-info contact">
        <div class="small-12 columns">
            <h2 class="additional-info__title">
                <span class="additional-info__title-inner"><?= _e('Contact', 'muhlenbergcenter'); ?></span>
            </h2>

            <div class="additional-info__switch">
                <?= _e('show', 'muhlenbergcenter'); ?>
            </div>

            <div class="row additional-info__content contact">
                <?php
                    /*
                       Get program content from post in category 'event-contact'
                    */
                    $contact_post = [
                        'name'           => $contact,
                        'post_type'      => 'post',
                        'post_status'    => 'publish',
                        'category_name'  => 'event-contact',
                        'posts_per_page' => -1,
                    ];
                    $contact_content = new WP_Query($contact_post);
                    if ($contact_content->have_posts()) :
                    while ($contact_content->have_posts()) : $contact_content->the_post();
                ?>
                    <div class="small-12 columns">
                        <?= the_content(); ?>
                    </div>
                <?php
                    endwhile;
                    endif;
                    wp_reset_postdata();
                ?>
            </div>

        </div>
    </section>

    <?php endif; ?>

</article>

<?= get_footer(); ?>