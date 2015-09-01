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
    <?php if ( has_post_thumbnail() ) : ?>
    <a href="<?php the_permalink(); ?>">
        <?php the_post_thumbnail( 'thumb' ); ?>
    </a>
    <?php endif; ?>
    <p>
        <a href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
        </a>
    </p>
</li>
<?php else : /* End: Category Press */ ?>

<article id="post-<?php the_ID(); ?>" class="row">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="small-4 columns">
        <?php the_post_thumbnail( 'thumb' ); ?>
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