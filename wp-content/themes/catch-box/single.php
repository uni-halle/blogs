<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Catch Themes
 * @subpackage Catch Box
 * @since Catch Box 1.0
 */

get_header(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<nav id="nav-single">
						<h3 class="assistive-text"><?php _e( 'Post navigation', 'catch-box' ); ?></h3>
						<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous', 'catch-box' ) ); ?></span>
						<span class="nav-next"><?php next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>', 'catch-box' ) ); ?></span>
					</nav><!-- #nav-single -->

					<?php get_template_part( 'content', 'single' ); ?>

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

<?php get_sidebar(); ?>

<?php get_footer(); ?>