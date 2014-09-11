<?php
/**
 * The Template for displaying single portfolio posts.
 */
?>

<?php get_header(); ?>
<?php get_template_part( 'menu' ) // load navigation menu ?>

    <div id="wrapper">
    
        <!--CONTENT STARTS HERE-->
         <div class="content">
     
			<?php 
                $maja_option = maja_get_global_options();
                global $post;
            ?>
            
            <ul class="portfolio_group col3_portfolio">
            
                <?php $wpbp = new WP_Query(array( 'post_type' => 'portfolio', 'posts_per_page' =>'-1') ); ?>
                
                    <?php if ($wpbp->have_posts()) : while ($wpbp->have_posts()) : $wpbp->the_post(); // The Loop ?>
                    
                        <?php 
                            $large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' ); 
                            $large_image = $large_image[0];
                            $item_url = get_post_meta($post->ID, 'portfolio_video_url', true); // get video url from meta box
                        ?>
                
                        <li>
                            
                            <?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) : ?>
                                
                                <?php if($item_url != '') { // if 'video url' box is empty display an image ?>  
                                
                                    <a class="portfolio_thumbnail" rel="prettyPhoto[gallery]" href="<?php echo $item_url; ?>"><?php the_post_thumbnail('portfolio-thumbnail-3col'); ?></a> 
                                    
                               <?php } else { // if there is an 'video url' display the video ?>
                               
                                    <a class="portfolio_thumbnail" rel="prettyPhoto[gallery]" href="<?php echo $large_image ?>"><?php the_post_thumbnail('portfolio-thumbnail-3col'); ?></a>	
                                                                            
                                <?php } ?> 
                                                                  
                            <?php endif; ?>	
                            
                            <h5><?php the_title(); ?></h5>
                            <?php the_content(); ?>
                                
                        </li>
                	
                    <?php endwhile; endif; // END the Wordpress Loop ?>
                    
                <?php wp_reset_query(); // Reset the Query Loop?>
            
            </ul>
 
         </div>
        <!--CONTENT ENDS HERE-->
     
<?php get_footer(); ?>