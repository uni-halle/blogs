<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 */
?>

<?php if ( is_category( 'press' ) ) : /* Begin: Category Press */ ?>
<li id="post-<?php the_ID(); ?>" class="media">
    <div class="thumbnail">
        <a href="<?php the_permalink(); ?>"
           title="<?php sprintf( _e( 'More about ', 'muhlenbergcenter' ), the_title() ); ?>">
            <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( array(285,180) ); ?>
            <?php else : ?>
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/teaser_press.jpg"
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

<?php elseif ( is_category( 'board-of-directors' ) ) : /* Begin: Category Borad of Directors */ ?>
<li id="post-<?php the_ID(); ?>" class="person">
    <?php if ( has_post_thumbnail() ) : ?>
    <a href="<?php the_permalink(); ?>" title="<?php sprintf( _e( 'More about ', 'muhlenbergcenter' ), the_title() ); ?>">
        <?php the_post_thumbnail( array(285,180) ); ?>
    </a>
    <?php endif; ?>
    <h3 class="person__name">
        <a href="<?php the_permalink(); ?>" class="person__link" title="<?php sprintf( _e( 'More about ', 'muhlenbergcenter' ), the_title() ); ?>">
            <?php the_title(); ?><br/>
            <span><?php echo get_post_meta( $post->ID, 'position', true ); ?></span>
        </a>
    </h3>
    <ul class="no-bullet">
        <li><?php echo get_post_meta( $post->ID, 'email', true ); ?></li>
        <li>Phone: <?php echo get_post_meta( $post->ID, 'phone', true ); ?></li>
        <li>
            <a href="<?php the_permalink(); ?>" title="<?php sprintf( _e( 'More about ', 'muhlenbergcenter' ), the_title() ); ?>">
                <?php _e('read more', 'muhlenbergcenter'); ?>
            </a>
        </li>
    </ul>
</li>
<?php else : /* End: Category Borad of Directors */ ?>

<article id="post-<?php the_ID(); ?>">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="medium-4 columns">
        <?php the_post_thumbnail( array(285,180) ); ?>
    </div>
    <?php endif; ?>
    <div class="medium-8 columns">
        <?php
            if ( is_single() ) :
                the_title( '<h1>', '</h1>' );
            else :
                the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
            endif;
        ?>


        <div class="content">
            <?php
                the_content( sprintf(
                    __( 'Continue reading %s', 'muhlenbergcenter' ),
                    the_title( '<span class="screen-reader-text">', '</span>', false )
                ) );
            ?>
        </div>
    </div>
</article>

<?php endif; /* End: Other Posts */ ?>