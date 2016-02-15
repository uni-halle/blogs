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
                <span class="additional-info__title-inner"><?php _e('Program', 'muhlenbergcenter'); ?></span>
            </h2>

            <div class="additional-info__switch">
                <?php _e('show', 'muhlenbergcenter'); ?>
            </div>

            <?php
                /*
                   Get program content from post in category 'event-program'
                   Get specific post by value of custom field 'Program' in event
                */
                $program_post = array(
                    'name'           => $program,
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'category_name'  => 'event-program',
                    'posts_per_page' => 1
                );
                $program_content = new WP_Query($program_post);
                if ($program_content->have_posts()) :
                while ($program_content->have_posts()) : $program_content->the_post();
            ?>
            <div class="additional-info__content program">
                <?php the_content(); ?>
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
                <span class="additional-info__title-inner"><?php _e( 'Accommodation', 'muhlenbergcenter' ); ?></span>
            </h2>

            <div class="additional-info__switch">
                <?php _e('show', 'muhlenbergcenter'); ?>
            </div>

            <?php
                /*
                   Get program content from post in category 'event-accommodation'
                   Get specific post by value of custom field 'Accommodation' in event
                */
                $accommodation_post = array(
                    'name'           => $accommodation,
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'category_name'  => 'event-accommodation',
                    'posts_per_page' => 1
                );
                $accommodation_content = new WP_Query($accommodation_post);
                if ($accommodation_content->have_posts()) :
                while ($accommodation_content->have_posts()) : $accommodation_content->the_post();
            ?>
            <div class="row additional-info__content accommodation">
                <?php if(has_post_thumbnail()) : ?>
                <div class="medium-4 columns">
                    <?php the_post_thumbnail(); ?>
                </div>
                <?php endif; ?>
                <div class="medium-8 columns">
                    <h3 class="additional-info__subtitle">
                        <?php the_title(); ?>
                    </h3>
                    <?php the_content(); ?>
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
        $directions = get_post_meta(get_the_ID(), 'Directions', true);
        if (!empty($directions)) :
    ?>

    <section class="row additional-info directions">
        <div class="small-12 columns">
            <h2 class="additional-info__title">
                <span class="additional-info__title-inner"><?php _e( 'Directions', 'muhlenbergcenter' ); ?></span>
            </h2>

            <div class="additional-info__switch">
                <?php _e('show', 'muhlenbergcenter'); ?>
            </div>

            <div class="row additional-info__content directions">
                <?php
                    /*
                       Get program content from post in category 'event-directions'
                    */
                    $directions_post = array(
                        'name'           => $directions,
                        'post_type'      => 'post',
                        'post_status'    => 'publish',
                        'category_name'  => 'event-directions',
                        'posts_per_page' => 1,
                    );
                    $directions_content = new WP_Query($directions_post);
                    if ($directions_content->have_posts()) :
                    while ($directions_content->have_posts()) : $directions_content->the_post();
                ?>
                    <div class="medium-4 columns">
                        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/symbol-directions.png" alt="symbol directions" />
                    </div>
                    <div class="medium-8 columns">
                        <?php the_content(); ?>
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

    <section class="row additional-info directions">
        <div class="small-12 columns">
            <h2 class="additional-info__title">
                <span class="additional-info__title-inner"><?php _e( 'Contact', 'muhlenbergcenter' ); ?></span>
            </h2>

            <div class="additional-info__switch">
                <?php _e('show', 'muhlenbergcenter'); ?>
            </div>

            <div class="row additional-info__content contact">
                <?php
                    /*
                       Get program content from post in category 'event-contact'
                    */
                    $contact_post = array(
                        'name'           => $contact,
                        'post_type'      => 'post',
                        'post_status'    => 'publish',
                        'category_name'  => 'event-contact',
                        'posts_per_page' => -1,
                    );
                    $contact_content = new WP_Query($contact_post);
                    if ($contact_content->have_posts()) :
                    while ($contact_content->have_posts()) : $contact_content->the_post();
                ?>
                    <div class="small-12 columns">
                        <?php the_content(); ?>
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

<?php get_footer(); ?>