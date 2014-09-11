<?php
/**
 * The template for displaying Author Archive pages.
 */
?>

<?php get_header(); ?>
<?php get_template_part( 'menu' ) // load navigation menu ?>
    
    <div id="wrapper">
    
   	    <!--BLOG STARTS HERE-->
         <section id="blog_content" class="two_thirds">
         
			<article <?php post_class(); ?>>
                
				<?php
                  if(isset($_GET['author_name'])) :
                      $curauth = get_userdatabylogin($author_name);
                      else :
                      $curauth = get_userdata(intval($author));
                  endif;
                ?>
                
                <header>
                
                   <h2 class="main_title">
                      <?php _e('About', 'maja') ?> 
					  <?php echo $curauth->display_name; ?>
                   </h2>
                   
                   <div id="auth_desc"><?php echo $curauth->description; ?></div>
                                        
                   <?php if ($curauth->user_url): ?>
                      <a href="<?php echo $curauth->user_url; ?>">
                          <?php _e('Visit', 'maja'); ?> <?php echo $curauth->display_name; ?><?php _e("'s Website", 'maja'); ?>
                      </a>
                   <?php endif; ?>
                   
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