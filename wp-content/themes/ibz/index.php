<?php
/**
 * The main template - used for displaying Blog page.
 */
?>
<?php define ('WPCF7_AUTOP', false ); ?>
<?php get_header(); ?>
<?php get_template_part( 'menu' ) // load navigation menu ?>

    <div id="wrapper">

   	    <!--BLOG STARTS HERE-->
         <section id="blog_content" class="two_thirds">

            <?php if(have_posts()) : while(have_posts()) : the_post(); // The Loop ?>

				<article <?php post_class('index'); ?>>
                        
					<?php get_template_part( 'metadata-blog' ); // get blog metadata ?>
                                        
                    <div class="blog_text">	
						 <?php the_content(__('Read All', 'maja')); ?>
                    </div> <!--/blog_text-->
                    
				</article> <!--/article-->  
                                       
				<?php endwhile; ?>
                
                <?php else : ?>
                
                	<header>
                        <h2 class="main_title"><?php _e('No posts yet!', 'maja'); ?></h2>
                        <div class="space"></div>
                    </header>
                    
                <?php endif; ?>
                
                <div class="separator no_margin"></div>
              
 				<?php get_template_part( 'paged-nav' ) // load numbered page navigation ?>
                   
        </section> <!--/blog_content-->
  
  		<?php get_sidebar(); ?>
        <!--BLOG ENDS HERE-->
        
<?php get_footer(); ?>