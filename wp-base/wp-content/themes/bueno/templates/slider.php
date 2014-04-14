<?php
/**
 * The template part for displaying slider on Home page.
 *
 * @package bueno
 */
?>  
<?php if( (is_front_page()) && (of_get_option('sl_show') != 'no') ) { ?>
<section id="slider-wrapper">
    <?php
        $bueno_sl_num = of_get_option('sl_num');
        if ( "" != $bueno_sl_num ) { 
            $bueno_sl_num = (int)$bueno_sl_num; 
        } else { 
            $bueno_sl_num = 10;
        }
        $bueno_sl_args = array(
            'posts_per_page' => $bueno_sl_num,
            'ignore_sticky_posts' => 1
        );
        $bueno_sl_cat = of_get_option('sl_category', 'from_all');
        if ( 'from_all' != $bueno_sl_cat ) {
            $bueno_sl_args['cat'] = $bueno_sl_cat;
        }
        $bueno_slider_query = new WP_Query( $bueno_sl_args );
        $bueno_checkthumb = 0;
        while ( $bueno_slider_query->have_posts() ) : $bueno_slider_query->the_post();
            if(has_post_thumbnail( $post->ID )){
                $bueno_checkthumb = 1;
            }
        endwhile;
        if( $bueno_slider_query && ( 1 == $bueno_checkthumb ) ){
    ?>
<div class="flexslider">
    <ul class="slides">
    <?php    
            while ( $bueno_slider_query->have_posts() ) : $bueno_slider_query->the_post();
            $bueno_sl_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'slider-post-thumbnail');
    ?>
    <?php if(has_post_thumbnail( $post->ID )){
    ?>
        <li>
            <?php if ( 'false' != of_get_option('sl_as_link') ) { ?>
            <a href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>">
            <?php } ?> 
                <img src="<?php echo esc_url( $bueno_sl_image_url[0] ); ?>" alt="<?php the_title(); ?>" />
            <?php if ( 'false' != of_get_option('sl_as_link') ) { ?>
            </a>
            <?php } ?> 
            <?php 
                if ( 'show' == of_get_option('sl_caption')) {
            ?>
                <div class="slider-caption">
                    <?php 
                        if ( 'hide' != of_get_option('sl_capt_title')) { ?>
                        <div>
                            <h4><?php the_title(); ?></h4>
                        </div>
                    <?php 
                        } 
                        if ( ( 'hide' != of_get_option('sl_capt_exc')) && ( '0' != esc_attr(of_get_option('sl_capt_exc_length')) ) ) {
                             $bueno_exc_length = (int)esc_attr(of_get_option('sl_capt_exc_length'));
                             if ($bueno_exc_length <= 0) $bueno_exc_length = 20;
                    ?>
                    <div>
                        <div class="sl-capt-content"><?php
                            $bueno_excerpt = get_the_excerpt();
                            echo bueno_string_limit_words($bueno_excerpt,$bueno_exc_length);
                        ?></div>
                    </div>
                    <?php 
                        }
                    ?>
                    <?php if ( 'hide' != of_get_option('sl_capt_btn') ) { 
                        if ( '' != esc_attr(of_get_option('sl_capt_btn_txt')) ) {
                            $bueno_btn_txt = esc_attr(of_get_option('sl_capt_btn_txt'));
                        } else {
                            $bueno_btn_txt = __( 'Read more', 'bueno' );
                        }
                    ?>
                    <div class="sl-btn-wrap">
                        <a href="<?php the_permalink(); ?>" class="btn btn-primary" title="<?php the_title(); ?>"><?php echo $bueno_btn_txt; ?></a>
                    </div>
                    <?php } ?>
                </div>
            <?php       
                }
            ?>
        </li>
    <?php }  ?>   
 
    <?php endwhile; ?>
    </ul>
    <?php if ( ( 'false' != of_get_option('sl_direction_nav') ) || ( 'false' != of_get_option('sl_control') ) ) { ?>
    <div class="flex-controls-wrap visible-desktop"></div>
    <?php } ?>
</div>

<script type="text/javascript">
/* <![CDATA[ */
    jQuery(window).load(function() {
        jQuery('.flexslider').flexslider({
            animation: "<?php if (of_get_option('sl_effect') != '') { echo of_get_option('sl_effect'); } else { echo 'fade'; }; ?>",
            direction: "horizontal",
            slideshow: <?php if (of_get_option('sl_slideshow') != '') { echo of_get_option('sl_slideshow'); } else { echo 'true'; }; ?>,
            controlNav: <?php if (of_get_option('sl_control') != '') { echo of_get_option('sl_control'); } else { echo 'true'; }; ?>,
            directionNav: <?php if (of_get_option('sl_direction_nav') != '') { echo of_get_option('sl_direction_nav'); } else { echo 'true'; }; ?>,
            controlsContainer: jQuery(".flex-controls-wrap")
        }); 
    });
/* ]]> */
</script>
<?php } ?>
<?php 
    wp_reset_query();
    wp_reset_postdata();
?>
</section><!--#slider--> 
<?php } ?>