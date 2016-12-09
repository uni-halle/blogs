<?php
/**
 * The template for displaying navigation menu for homepage.
 */
?>

	<?php $maja_option = maja_get_global_options(); ?>
    
    <div class="clear"></div>
          
    <?php
        if(function_exists('wp_nav_menu')):
            
         
            wp_nav_menu(
                array(
                'menu' =>'primary_nav',
                'container' =>'nav',
                'depth' => 0,
                'menu_id' => 'menu_list' )
            );
        else:
    ?>
   
        <ul>
            <li>
                <a class="selected" href="<?php echo home_url(); ?>" title="Home">Home</a>
            </li>
            <?php wp_list_pages('title_li=&depth=0');  ?>
        </ul>
     
    <?php endif; ?>  
                           
    <?php get_template_part( 'footer-menu' ) ?>

</section> <!--/menu-->