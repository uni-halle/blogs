<?php
/**
 * The template used for displaying posts with gallery format
 *
 */
?>

<li id="post-<?php the_ID(); ?>" class="media">
    <div class="thumbnail">
        <a href="<?php the_permalink(); ?>"
           title="<?php sprintf( _e( 'More about ', 'muhlenbergcenter' ), the_title() ); ?>">
            <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( array(285,180) ); ?>
            <?php else : ?>
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/teaser_pictures.jpg"
                     alt="teaser image" />
            <?php endif; ?>
        </a>
    </div>
    <p>
        <a href="<?php the_permalink(); ?>"
           title="<?php sprintf( _e( 'More about ', 'muhlenbergcenter' ), the_title() ); ?>">
            <?php the_title(); ?>
        </a>
    </p>
</li>
