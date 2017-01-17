<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>


<section class="single-page">


    <div class="row single-table">
        <div class="single-table-row">

            <?php

                if ( has_post_thumbnail() ) {

                    ?><div class="sp-image"><?php twentysixteen_post_thumbnail(); ?></div>
                            <div class="sp-headline">
                            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                        </div>
                    <?php
                }
                else {
                    ?>
                    <div class="sp-headline">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    </div>
                    <?php
                }
            ?>


        </div>
    </div>


    <div class="single-content">

        <?php





        the_content();

        wp_link_pages( array(
            'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
            'after'       => '</div>',
            'link_before' => '<span>',
            'link_after'  => '</span>',
            'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
            'separator'   => '<span class="screen-reader-text">, </span>',
        ) );
        ?>



    </article><!-- #post-## -->


</section>



