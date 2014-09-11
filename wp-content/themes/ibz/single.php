<?php
/**
 * The Template for displaying all single posts.
 */
?>

<?php get_header(); ?>
<?php get_template_part( 'menu' ) // load navigation menu ?>
       
    <div id="wrapper">
    
   	    <!--BLOG STARTS HERE-->
         <section id="blog_content" class="two_thirds">

			<article <?php post_class('single_post'); ?>>
            
            	<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
            
					<?php get_template_part( 'metadata-blog' ); // get blog metadata ?>
                    
                    <div class="blog_text">	                      
						<?php the_content(); ?>
                   		<?php wp_link_pages('before=<div class="separator"></div><p class="link_pages">&after=</p>&pagelink=<span class="links">%</span>'); ?>
                    </div> <!--/blog_text-->
                    
                    <div class="left clear tags">
						<?php the_tags(__('Tags: ', 'maja')); ?>
                    </div>
                                        
                    <?php endwhile; ?>
                    
                    <?php else : ?>
                    
                        <h2><?php _e('Not found', 'maja'); ?></h2>
                        <p><?php _e("Sorry, we can't find that.", 'maja'); ?></p> 
                        
                    <?php endif; ?>
                    
                    <div class="space"></div> 
                                        
                    <div id="comments">
                        <?php comments_template(); ?>                 
                    </div>
                                    
			</article> <!--/article --> 
            
            <div class="separator"></div>

			<nav>
                <ul class="pages">
                
                    <li title="<?php _e('Previous post', 'maja') ?>">
						<?php previous_post_link('%link', __('&laquo; Previous post', 'maja'), ''); ?>
                    </li>  
                    
                    <li title="<?php _e('Next post', 'maja') ?>">
						<?php next_post_link('%link', __('Next post &raquo;', 'maja'), ''); ?>
                    </li>
                    
                </ul> 
            </nav> 
            
        </section> <!--/blog_content-->
  
  		<?php get_sidebar(); ?>
        <!--BLOG ENDS HERE-->
        
<?php get_footer(); ?>