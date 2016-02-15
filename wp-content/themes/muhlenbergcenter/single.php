<?php
/**
 * The template for displaying all single posts and attachments
 *
 */

get_header(); ?>

<div class="row">

    <?php while ( have_posts() ) : the_post(); ?>
        <div class="small-12 columns">
            <p class="page-title">
                <span>
                    <?php 
                        $current_category = get_the_category();
                        $cat_name = $current_category[0]->name;
                        echo $cat_name;
                    ?>
                </span>
            </p>

            <div class="row">
                <div class="medium-4 columns">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( array(285,180) ); ?>
                    <?php elseif ( in_category('pictures') ) : ?>
                        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/teaser_pictures.jpg"
                             alt="teaser image" />
                    <?php elseif ( in_category('videos') ) : ?>
                        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/teaser_videos.jpg"
                             alt="teaser image" />
                    <?php elseif ( in_category('press') ) : ?>
                        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/teaser_press.jpg"
                             alt="teaser image" />
                    <?php endif; ?>

                    <?php if ( in_category('board-of-directors') ) : ?>
                    <div class="contact-info">
                        <h3><?php _e('Contact Information', 'muhlenbergcenter') ?></h3>
                        <ul>
                            <li><?php echo get_post_meta( $post->ID, 'email', true ); ?></li>
                            <li>Phone: <?php echo get_post_meta( $post->ID, 'phone', true ); ?></li>
                        </ul>
                    </div>
                    <?php endif; ?>

                </div>
                <div class="[ medium-8  columns ]  single-conent">
                    <h1 class="single-content__title"><?php the_title(); ?></h1>

                    <?php if ( in_category('board-of-directors') ) : ?>
                    <p><?php echo get_post_meta( $post->ID, 'position', true ); ?></p>
                    <?php endif; ?>

                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>

</div>

<?php get_footer(); ?>
