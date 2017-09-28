<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Catch Themes
 * @subpackage Catch Box
 * @since Catch Box 1.0
 */

get_header(); ?>

				<?php 
				// Start the loop.
				while ( have_posts() ) : the_post();

					// Include the single post content template.
					get_template_part( 'content', 'single' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}

					// Previous/next post navigation.
					the_post_navigation( array(
						'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next <span class="nav-icon">&rarr;</span>', 'catch-box' ) . '</span> ' .
							'<span class="screen-reader-text">' . __( 'Next post:', 'catch-box' ) . '</span> ' .
							'<span class="post-title">%title</span>',
						'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( '<span class="nav-icon">&larr;</span> Previous', 'catch-box' ) . '</span> ' .
							'<span class="screen-reader-text">' . __( 'Previous post:', 'catch-box' ) . '</span> ' .
							'<span class="post-title">%title</span>',
					) );

				// End of the loop.
				endwhile;
				?>

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