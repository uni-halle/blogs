<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
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
	<header class="page-header">
		<?php
			the_archive_title( '<h1 class="page-title">', '</h1>' );
			the_archive_description( '<div class="taxonomy-description">', '</div>' );
		?>
	</header><!-- .page-header -->
	
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
	
		// End the loop.
		endwhile;
	
		// Previous/next page navigation.
		the_posts_pagination( array(
			'prev_text'          => '<span class="meta-nav screen-reader-text">' . __( 'vorherige Seite', 'cleantraditional' ) . '</span>',
			'next_text'          => '<span class="meta-nav screen-reader-text">' . __( 'nächste Seite', 'cleantraditional' ) . '</span>',
			'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Seite', 'cleantraditional' ) . '</span>',
		) );
	
	// If no content, include the "No posts found" template.
	else :
		get_template_part( 'template-parts/content', 'none' );
	
	endif;
	?>
	
</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>