<?php
/**
 * The template used for displaying page content
 *
 */
?>

<article id="post-<?php the_ID(); ?>" class="row">

    <div class="small-12 columns">
        <?php if ( ! is_page( 'calendar' ) ) : ?>
        <h1 class="page-title"><?php the_title( '<span>', '</span>' ); ?></h1>
        <?php endif; ?>

        <?php if ( is_page( 'media' ) ) : /* conents of the media page */ ?>

            <div class="row">
                <?php
                $media_cats = array(
                    // 'parent'     => 25, /* Dev */
                    'parent'     => 3, /* Live */
                    'hide_empty' => 0
                );
                $categories = get_categories( $media_cats );
                foreach ( $categories as $category ) {
                    echo '<div class="[ medium-4  columns ]  media-category">
                            <a href="' . get_category_link( $category->term_id ) . '" class="' . $category->slug . '">
                                <span>' . $category->name . '</span>
                            </a>
                        </div>'
                ;}
                ?>
            </div>
            <div class="row  latest-uploads">
                <div class="small-12  columns">
                    <h1 class="page-title">
                        <span><?php _e( 'Latest Uploads', 'muhlenbergcenter' ); ?></span>
                    </h1>
                    <?php
                        $latestconditions = array(
                            'category_name'  => 'pictures,press,videos',
                            'post_type'      => 'post',
                            'post_status'    => 'publish',
                            'posts_per_page' => 3
                        );
                        $latestposts = new WP_Query( $latestconditions );
                        if ( $latestposts->have_posts() ) : ?>
                            <ul class="medium-block-grid-3">
                            <?php while( $latestposts->have_posts() ) : $latestposts->the_post(); ?>
                                <li id="post-<?php the_ID(); ?>" class="media">
                                    <?php if( has_post_thumbnail() ) : ?>
                                        <div class="thumbnail">
                                            <a href="<?php the_permalink(); ?>"
                                               title="<?php sprintf( _e( 'More about ', 'muhlenbergcenter' ), the_title() ); ?>">
                                                <?php the_post_thumbnail( array(285,180) ); ?>
                                            </a>
                                        </div>
                                    <?php /*else :
                                        echo '<div class="flex-video">' . get_the_content() . '</div>';*/
                                    endif; ?>
                                    <p>
                                        <a href="<?php the_permalink(); ?>"
                                           title="<?php sprintf( _e( 'More about ', 'muhlenbergcenter' ), the_title() ); ?>">
                                            <?php the_date(); ?>: <?php the_title(); ?>
                                        </a>
                                    </p>
                                </li>
                            <?php endwhile; ?>
                            </ul>
                        <?php endif; ?>
                </div>
            </div>

        <?php elseif ( is_page( 'newsletter' ) ) : /* conents of the newsletter page */ ?>

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

        <?php elseif ( is_page( 'contact' ) ) : /* contents of the contact page */ ?>

            <div class="row">
                <div class="medium-4  columns">
                    <?php if ( has_post_thumbnail() ) {
                        the_post_thumbnail( array(285,180) );
                    } ?>
                    <div class="contact-info">
                        <h3><?php _e('Address', 'muhlenbergcenter') ?></h3>
                        <ul>
                            <li><?php bloginfo( 'name' ); ?></li>
                            <li><?php echo get_post_meta( $post->ID, 'street', true ); ?></li>
                            <li><?php echo get_post_meta( $post->ID, 'place', true ); ?></li>
                        </ul>
                    </div>
                </div>

                <div class="medium-8  columns">
                    <?php the_content(); ?>
                </div>
            </div>

        <?php else : /* contents of all other pages */ ?>

            <div class="content">
                <?php the_content(); ?>
            </div>

        <?php endif; ?>
    </div>

</article>