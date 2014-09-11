<?php
/**
 * The template for displaying Archive pages.
 */
?>

<?php get_header(); ?>
<?php get_template_part( 'menu' ) // load page specific navigation menu ?>
   
    <div id="wrapper">
    
   	    <!--BLOG STARTS HERE-->
         <section id="blog_content" class="two_thirds">
         
			<article <?php post_class(); ?>>
                 
                <header>            
                    <h3 class="main_title">
						<?php _e('Archive of', 'maja'); ?> '<?php wp_title(' ', true, ' '); ?>'.
                    </h3>
                    <div class="separator"></div> 
                </header>        

                <ul class="lists-arrow float">
                
                    <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
                        <li>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                    
                </ul>
                                   
                <?php else : ?>
                    <h2><?php _e('Not found', 'maja'); ?></h2>
                    <p><?php _e("Sorry, we can't find that.", 'maja'); ?></p> 
                <?php endif; ?>
                
			</article> <!--/blog_entry-->      
                   
        </section> <!--/blog_content-->
  
  		<?php get_sidebar(); ?>
        <!--BLOG ENDS HERE-->
        
<?php get_footer(); ?>