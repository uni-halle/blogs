<?php
/**
 * The template for displaying pages.
 */
?>

<?php get_header(); ?><ul class="pagnav">
<?php /*get_template_part( 'menu' ); */// load navigation menu 
wp_list_pages(array('child_of'=>5,'sort_column'=>'menu_order','title_li'=>'')); 




?>
</ul>
</section>


    
    <div id="wrapper">
    
        <!--CONTENT STARTS HERE-->    	
    	<section class="content">
			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
     
                <?php the_content(); ?>
                <?php wp_link_pages('before=<div class="one"><div class="separator"></div><p class="link_pages">&after=</p></div>&pagelink=<span class="links">%</span>'); ?>
                                    
            <?php endwhile ?>
                
            <?php else : ?>
            
                <div class="one">
                	<header>
                        <h2><?php _e('Not found', 'maja'); ?></h2>
                        <p><?php _e("Sorry, we can't find that.", 'maja'); ?></p> 
                    </header>
                </div>
            <?php endif; ?>
        
        
        <!-- footer added for IBZ-->
         <div class="ibz-footer-outer" >
          <div class="ibz-footer-inner" >
		 	<a href="http://www.uni-halle.de" target="_blank"><img src="www.ibz.uni-halle.de/files/2012/11/logo-mlu.gif" alt="Logo Martin-Luther-Universität Halle-Wittenberg" /></a>
		 	<a href="http://www.leopoldina.org" target="_blank"><img src="www.ibz.uni-halle.de/files/2012/11/logo-leopoldina.gif" alt="Logo Leopoldina" /></a>
          </div>
         </div>


        
        
            
        </section> 
        <!--CONTENT ENDS HERE-->
        
                
<?php get_footer(); ?>