<?php
/**
 * The template for displaying 404 pages (Not Found).
 */
?>

<?php get_header(); ?>
<?php get_template_part( 'menu' ) // load page specific navigation menu ?>
    
    <div id="wrapper">
    
         <section class="content">

			<header>
            
                <h2 class="main_title">
					<?php _e('404 - page not found', 'maja'); ?>
                </h2>
                
                <h3 class="subtitle">
					<?php _e("Sorry, the page you're looking for has gotten lost!", 'maja'); ?>
                </h3>
                
                <div class="separator no_wrapper"></div>

			</header>
                        
            <div class="one_third aligncenter">
            	<?php get_search_form(); ?>
            </div>
                    
        </section> <!--/content-->
          
<?php get_footer(); ?>