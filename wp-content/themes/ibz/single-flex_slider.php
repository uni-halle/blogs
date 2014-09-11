<?php
/**
 * The template for displaying Nivo Slider.
 * It can be loaded by placing [nivo-slider] shortcode 
 * inside your page.
 */
?>

<?php get_header(); ?>
<?php get_template_part( 'menu' ) // load navigation menu ?>

    <div id="wrapper">
    
        <!--CONTENT STARTS HERE-->
         <div class="content">
         
              <div class="flex_container">
                <div class="flexslider">
                    <ul class="slides">
                
                        <?php 
                            query_posts( array( 'post_type' => 'flex_slider', 'posts_per_page' => 20, 'order' => 'ASC' )); 
                            global $post;
                        ?> 
                
                        <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
                                    
                            <li>
                                <?php the_post_thumbnail('slider-thumbnail'); ?>
                                <?php echo get_post_meta($post->ID, 'slider-thumbnail', true); ?>
                                <?php $flex_caption = get_post_meta($post->ID, 'slider_caption', true); ?>
                                <?php if($flex_caption != '') { ?>
                                    <p class="flex-caption"><?php echo $flex_caption; ?></p>
                                <?php } ?>
                            </li>
                                                
                        <?php endwhile; endif; ?>
                        <?php wp_reset_query(); ?>
                        
                    </ul>
                </div>
         	</div>

         </div>
        <!--CONTENT ENDS HERE-->
     
<?php get_footer(); ?>