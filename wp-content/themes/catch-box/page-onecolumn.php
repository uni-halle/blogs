<?php
/**
 * Template Name: One-column, no sidebar
 * Description: A Page Template that disables a sidebar to pages
 *
 * @package Catch Themes
 * @subpackage Catch Box
 * @since Catch Box 1.0
 */

get_header(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
        
		<?php 
        /** 
         * catchbox_after_content hook
         *
         */
        do_action( 'catchbox_after_content' ); ?>
            
	</div><!-- #primary -->
    
	<?php 
    /** 
     * catchbox_after_primary hook
     *
     */
    do_action( 'catchbox_after_primary' ); ?>    

<?php get_footer(); ?>