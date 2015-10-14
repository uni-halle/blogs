<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Catch Themes
 * @subpackage Catch Box
 * @since Catch Box 1.0
 */

get_header(); ?>

			<article id="post-0" class="post error404 not-found">
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'Error 404 - Page Not Found.', 'catch-box' ); ?></h1>
				</header>

				<div class="entry-content">
					<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'catch-box' ); ?></p>
                    <h2><?php _e( 'This might be because:', 'catch-box' ); ?></h2>
               	 	<p><?php _e( 'You have typed the web address incorrectly, or the page you were looking for may have been moved, updated or deleted.', 'catch-box' ); ?></p>
                	<h2><?php _e( 'Please try the following options instead:', 'catch-box' ); ?></h2>
                	<p><?php _e( 'Check for a mis-typed URL error, then press the refresh button on your browser or Use the search box below.', 'catch-box' ); ?></p>
					<?php get_search_form(); ?>                  
				</div><!-- .entry-content -->
                
			</article><!-- #post-0 -->

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