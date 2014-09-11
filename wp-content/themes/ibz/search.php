<?php
/**
 * The template for displaying Search Results pages.
 */
?>

<?php get_header(); ?>
<?php get_template_part( 'menu' ) // load navigation menu ?>
    
    <div id="wrapper">
    
   	    <!--BLOG STARTS HERE-->
         <section id="blog_content" class="two_thirds">
         
         	<header>
                <h2 class="main_title">
                    <?php _e('Results for', 'maja'); ?> '<?php echo($s); ?>'.
                </h2>
                <div class="separator"></div> 
            </header>
                     
            <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
         
                <article <?php post_class('search_results'); ?>>
                    
					<?php get_template_part( 'metadata-blog' ); // get blog metadata ?>
                    
                    <div class="blog_text">	
                        <?php the_excerpt(); ?>
                    </div> <!--/blog_text-->
                    
                </article> <!--/blog_entry-->  
            
			<?php endwhile; ?>

            <?php else : ?>
            
                <h2><?php _e('Not found', 'maja'); ?></h2>
                <p><?php _e("Sorry, we can't find that.", 'maja'); ?></p>  
                          
            <?php endif; ?>           
            
            <div class="separator"></div>
                
 			<?php get_template_part( 'paged-nav' ) // load numbered page navigation ?>
                   
        </section> <!--/blog_content-->
  
  		<?php get_sidebar(); ?>
        <!--BLOG ENDS HERE-->
        
<?php get_footer(); ?>