<?php
/**
 * The main template file
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
	<nav id="site-navigation" class="navigation-main cell position-0 width-2" role="navigation">
		<?php
			// Primary navigation menu.
			wp_nav_menu( array(
				'menu_class'     => 'nav-menu',
				'theme_location' => 'main',
			) );
		?>
	</nav>
<?php endif; ?>


<main id="main" class="site-main cell position-2 width-4" role="main">
	<?php // show title of posts-page, if post-page isn't standard  
		if ( is_home() && ! is_front_page() ) : ?>
		<header class="page-header">
			<h1 class="page-title"><?php echo apply_filters(' the_title' , get_page( get_option( 'page_for_posts' ) )->post_title ); ?></h1>
		</header>
	<?php endif; ?>
	
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
