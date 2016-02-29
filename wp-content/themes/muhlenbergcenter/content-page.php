<?php
/**
 * The template used for displaying page content
 *
 */
?>

<div id="post-<?php the_ID(); ?>" class="row">

    <div class="small-12 columns">
        <?php if (!is_page('calendar')) : ?>
        <h1 class="page-title"><?php the_title('<span>', '</span>'); ?></h1>
        <?php endif; ?>

        <?php if (is_page('media')) : /* conents of the media page */ ?>

            <div class="row">
                <?php
                $media_cats = array(
                    // 'parent'     => 25, /* Dev */
                    'parent'     => 3, /* Live */
                    'hide_empty' => 0
                );
                $categories = get_categories($media_cats);
                foreach ($categories as $category) {
                    echo '<div class="[ medium-4  columns ]  media-category">
                            <a href="' . get_category_link($category->term_id) . '" class="' . $category->slug . '">
                                <span>' . $category->name . '</span>
                            </a>
                        </div>'
                ;}
                ?>
            </div>
            <div class="row  latest-uploads">
                <div class="small-12  columns">
                    <h1 class="page-title">
                        <span><?php _e('Latest Uploads', 'muhlenbergcenter'); ?></span>
                    </h1>
                    <?php
                        $latestconditions = array(
                            'category_name'  => 'pictures,press,videos',
                            'post_type'      => 'post',
                            'post_status'    => 'publish',
                            'posts_per_page' => 3
                        );
                        $latestposts = new WP_Query($latestconditions);
                        if ($latestposts->have_posts()) : ?>
                            <ul class="medium-block-grid-3">
                            <?php while($latestposts->have_posts()) : $latestposts->the_post(); ?>
                                <li id="post-<?php the_ID(); ?>" class="media">
                                    <?php if(in_category(['pictures', 'press'])) : ?>
                                        <div class="thumbnail">
                                            <a href="<?php the_permalink(); ?>"
                                               title="<?php sprintf(_e('More about ', 'muhlenbergcenter'), the_title()); ?>">
                                                <?php if(has_post_thumbnail()) : ?>
                                                        <?php the_post_thumbnail([285,180]); ?>
                                                <?php elseif(in_category('press')) : ?>
                                                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/teaser_press.jpg" alt="teaser image" />
                                                <?php elseif(in_category('pictures')) : ?>
                                                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/teaser_pictures.jpg" alt="teaser image" />
                                                <?php endif; ?>
                                            </a>
                                        </div>
                                    <?php else : ?>
                                        <?php 
                                            /*
                                               Check if custom field 'Video-ID' is used
                                               if true return content
                                            */
                                            $youtube = get_post_meta(get_the_ID(), 'Video-ID', true);
                                            if (!empty($youtube)) :
                                        ?>
                                        <div class="thumbnail">
                                            <div class="flex-video">
                                                <iframe src="https://www.youtube-nocookie.com/embed/<?php echo $youtube ?>?rel=0"
                                                        class="video-player"
                                                        allowfullscreen>
                                                </iframe>
                                            </div>
                                       </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <p class="meta-info">
                                        <span class="meta-info__category">
                                        <?php $categories = get_the_category();
                                        if (!empty($categories)) {
                                            echo esc_html($categories[0]->name);
                                        }
                                        ?>
                                        </span> |
                                        <span class="meta-info__date"><?php the_date(); ?></span>
                                    </p>
                                    <p>
                                        <?php if(in_category('videos')) : ?>
                                            <?= the_title(); ?>
                                        <?php else : ?>
                                        <a href="<?php the_permalink(); ?>"
                                           title="<?php sprintf(_e('More about ', 'muhlenbergcenter'), the_title()); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                        <?php endif; ?>
                                    </p>
                                </li>
                            <?php endwhile; ?>
                            </ul>
                        <?php endif; ?>
                </div>
            </div>

        <?php elseif (is_page('newsletter')) : /* conents of the newsletter page */ ?>

            <div class="content">
                <?php the_content(); ?>

                <form method="post" action="http://entwicklung.lenz/muhlenberg-center/wp-content/plugins/newsletter/do/subscribe.php" data-abide>
                    <div class="row">
                        <div class="large-6 columns">
                            <label>
                                <?php _e('E-Mail-Address', 'muhlenbergcenter') ?>
                                <input type="email" name="ne" size="30" required>
                                <small class="error">Invalid entry</small>
                            </label>
                            <button type="submit" class="small"><?php _e('Subscribe', 'muhlenbergcenter') ?></button>
                        </div>
                    </div>
                </form>
            </div>

        <?php elseif (is_page('contact')) : /* contents of the contact page */ ?>

            <div class="row">
                <div class="medium-4  columns">
                    <?php if (has_post_thumbnail()) {
                        the_post_thumbnail([285,180]);
                    } ?>
                    <div class="contact-info">
                        <h3><?php _e('Address', 'muhlenbergcenter') ?></h3>
                        <ul>
                            <li><?php bloginfo('name'); ?></li>
                            <li><?php echo get_post_meta($post->ID, 'street', true); ?></li>
                            <li><?php echo get_post_meta($post->ID, 'place', true); ?></li>
                        </ul>
                    </div>
                </div>

                <div class="medium-8  columns">
                    <?php the_content(); ?>
                </div>
            </div>

        <?php elseif (is_page('event-archive')) : /* contents of the event archive */ ?>

            <div class="row">
                <div class="small-12  columns">
                    <?php
                        echo do_shortcode(
                            '[events_list_grouped mode="yearly" scope="past"]
                                <div class="row event-item">
                                    {has_image}
                                    <div class="medium-4 columns">
                                        <a href="#_EVENTURL" title="More about #_EVENTNAME">
                                            #_EVENTIMAGE
                                        </a>
                                    </div>
                                    {/has_image}
                                    {no_image}
                                    <div class="medium-4 columns">
                                        <a href="#_EVENTURL" title="More about #_EVENTNAME">
                                            #_CATEGORYIMAGE
                                        </a>
                                    </div>
                                    {/no_image}
                                    <div class="medium-8 columns">
                                        <p class="event-item__date">#_EVENTDATES</p>
                                        <p class="event-item__category">#_CATEGORYNAME</p>
                                        <h2 class="event-item__title">#_EVENTLINK</h2>
                                        <div class="event-item__consultant">#_ATT{consultant_name}</div>
                                    </div>
                                </div>
                            [/events_list_grouped]'
                        );
                    ?>
                </div>
            </div>

        <?php else : /* contents of all other pages */ ?>

            <div class="content">
                <?php the_content(); ?>
            </div>

        <?php endif; ?>
    </div>

</div>