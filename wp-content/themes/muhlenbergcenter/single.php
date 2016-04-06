<?php
/**
 * The template for displaying all single posts and attachments
 *
 */

get_header(); ?>

<div class="row">

    <?php while (have_posts()) : the_post(); ?>
        <div class="small-12 columns">
            <p class="page-title">
                <span>
                    <?php 
                        $current_category = get_the_category();
                        $cat_name = $current_category[0]->name;
                        if ($cat_name == 'Videos') {
                            echo 'Video';
                        }
                        elseif ($cat_name == 'Publications') {
                            echo 'Publication';
                        }
                        elseif ($cat_name == 'Projects') {
                            echo 'Project';
                        }
                        elseif ($cat_name == 'Networks') {
                            echo 'Network';
                        }
                        else {
                            echo $cat_name;
                        }
                    ?>
                </span>
            </p>

            <div class="row">
                <div class="medium-4 columns">
                    <?php if (in_category(['pictures', 'press']) && has_post_thumbnail()) : ?>
                        <?= the_post_thumbnail([285,180]); ?>
                    <?php elseif (in_category('pictures')) : ?>
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/teaser_pictures.jpg"
                             alt="teaser image" />
                    <?php elseif (in_category('press')) : ?>
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/teaser_press.jpg"
                             alt="teaser image" />
                    <?php elseif (in_category('videos')) : ?>
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
                                <iframe src="https://www.youtube-nocookie.com/embed/<?= $youtube ?>?rel=0"
                                        class="video-player" allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                            <?php 
                                /*
                                    Check if video information is used (length, date)
                                    if one is used return content
                                */
                                $video_length = get_post_meta(get_the_ID(), 'Video-Length', true);
                                $video_date = get_post_meta(get_the_ID(), 'Video-Date', true);
                                if (!empty($video_length) | !empty($video_date)) :
                            ?>
                            <div class="video-meta">
                                <h3 class="video-meta__title">Video Information</h3>
                                <ul class="video-meta__list">
                                    <?php if (!empty($video_length)) : ?>
                                    <li class="video-meta__list-item">Length: <?= $video_length ?></li>
                                    <?php endif; ?>
                                    <?php if (!empty($video_date)) : ?>
                                    <li class="video-meta__list-item">Recorded on: <?= $video_date ?></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php elseif (has_post_thumbnail()) : ?>
                        <?= the_post_thumbnail([285,180]); ?>
                    <?php endif; ?>

                    <?php if (in_category(['board-of-directors', 'board-of-advisors', 'staff'])) : ?>
                    <div class="contact-info">
                        <h3><?= _e('Contact Information', 'muhlenbergcenter') ?></h3>
                        <ul>
                            <li><?= get_post_meta($post->ID, 'email', true); ?></li>
                            <li>Phone: <?= get_post_meta($post->ID, 'phone', true); ?></li>
                        </ul>
                    </div>
                    <?php endif; ?>

                </div>
                <div class="[ medium-8  columns ]  single-conent">
                    <h1 class="single-content__title"><?= the_title(); ?></h1>

                    <?php if (in_category(['board-of-directors', 'board-of-advisors', 'staff'])) : ?>
                    <p><?= get_post_meta($post->ID, 'position', true); ?></p>
                    <?php endif; ?>

                    <?php if (in_category(['publications', 'projects', 'networks'])) : ?>
                    <p class="event-item__consultant"><?= get_post_meta($post->ID, 'author', true); ?></p>
                    <?php endif; ?>

                    <?= the_content(); ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>

</div>

<?= get_footer(); ?>
