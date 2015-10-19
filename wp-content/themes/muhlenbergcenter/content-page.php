<?php
/**
 * The template used for displaying page content
 *
 */
?>

<article id="post-<?php the_ID(); ?>" class="row">

    <?php if ( is_page( 'media' ) ) : /* conents of the media page */ ?>
    <div class="small-12 columns">
        <h1 class="page-title"><?php the_title( '<span>', '</span>' ); ?></h1>
        <div class="row">
            <?php
            $media_cats = array(
                'parent'     => 25,
                //'parent'     => 3,
                'hide_empty' => 0
            );
            $categories = get_categories( $media_cats );
            foreach ( $categories as $category ) {
                echo '<div class="medium-4 columns media-category">
                        <a href="' . get_category_link( $category->term_id ) . '" class="' . $category->slug . '">
                            <span>' . $category->name . '</span>
                        </a>
                    </div>'
            ;}
            ?>
        </div>
        <div class="row latest-uploads">
            <div class="small-12 columns">
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
                                <?php if( has_post_thumbnail() ) {
                                    the_post_thumbnail( 'thumb' );
                                }
                                else {
                                    echo '<div class="flex-video">' . get_the_content() . '</div>';
                                } ?>
                                <p>
                                    <a href="<?php the_permalink(); ?>"><?php the_date(); ?>: <?php the_title(); ?></a>
                                </p>
                            </li>
                        <?php endwhile; ?>
                        </ul>
                    <?php endif; ?>
            </div>
        </div>
    </div>

    <?php else : /* contents of all other pages */ ?>
    <div class="small-12 columns">
        <?php if ( ! is_page( 'calendar' ) ) : ?>
        <h1 class="page-title"><?php the_title( '<span>', '</span>' ); ?></h1>
        <?php endif; ?>

        <div class="content">
            <?php the_content(); ?>
        </div>
    </div>
    <?php endif; ?>

</article>