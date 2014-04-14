<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Template Name: Blog Template
 * Description: A Page Template that disables a blog
 *
 * @package Catch Themes
 * @subpackage Catch_Box
 * @since Catch Box 2.3.1
 */

get_header(); ?>

			<?php 
			global $more, $wp_query, $post, $paged;
			$more = 0;
				
			if ( get_query_var( 'paged' ) ) {
				
				$paged = get_query_var( 'paged' );
				
			}
			elseif ( get_query_var( 'page' ) ) {
				
				$paged = get_query_var( 'page' );
				
			}
			else {
				
				$paged = 1;
				
			}
			
			$blog_query = new WP_Query( array( 'post_type' => 'post', 'paged' => $paged ) );
			$temp_query = $wp_query;
			$wp_query = null;
			$wp_query = $blog_query;

			if ( $blog_query->have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title"><?php the_title(); ?></h1>
				</header>

				<?php while ( $blog_query->have_posts() ) : $blog_query->the_post();  ?>

					<?php
						/* Include the Post-Format-specific template for the content.
						 * If you want to overload this in a child theme then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );
					?>

				<?php endwhile; ?>
                
                <?php catchbox_content_query_nav( 'nav-below' ); ?>
                
			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'catchbox' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'catchbox' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>                

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