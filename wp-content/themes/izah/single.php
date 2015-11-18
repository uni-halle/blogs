<?php
/**
 * The template for displaying all single posts and attachments
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @package Clean_Traditional
 * @since CleanTraditional 1.0
 */

get_header(); ?>


<?php if ( has_nav_menu( 'main' ) ) : ?>
	<nav id="site-navigation" class="navigation-main cell position-0 width-2">
		<?php
			// Primary navigation menu.
			wp_nav_menu( array(
				'menu_class'     => 'nav-menu',
				'theme_location' => 'main',
			) );
		?>
	</nav>
<?php endif; ?>


<main id="main" class="site-main cell position-2 width-4">
	
	<?php if ( have_posts() ) : ?>
	
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
			/*
			 * Include the Post-Format-specific template for the content.
			 * If you want to override this in a child theme, then include a file
			 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
			 */
			get_template_part( 'template-parts/content', get_post_format() );
			
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
	
            // Previous/next post navigation.
            /*
            the_post_navigation( array(
                'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Beitrag', 'cleantraditional' ) . ':</span> ' .
                    '<span class="screen-reader-text">' . __( 'Nächster Beitrag:', 'cleantraditional' ) . '</span> ' .
                    '<span class="post-title">%title</span>',
                'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Beitrag', 'cleantraditional' ) . ':</span> ' .
                    '<span class="screen-reader-text">' . __( 'Vorheriger Beitrag:', 'cleantraditional' ) . '</span> ' .
                    '<span class="post-title">%title</span>', 
            ) );
		    */	
		// End the loop.
		endwhile;
	
	// If no content, include the "No posts found" template.
	else :
		get_template_part( 'template-parts/content', 'none' );
	
	endif;
	?>
	
</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
