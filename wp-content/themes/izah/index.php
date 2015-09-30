<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 */

get_header(); ?>

	<nav id="site-navigation" class="main-navigation cell position-0 width-3" role="navigation">
	Navigation
		<?php
			// Primary navigation menu.
			wp_nav_menu( array(
				'menu_class'     => 'nav-menu',
				'theme_location' => 'primary',
			) );
		?>
	</nav><!-- .main-navigation -->

<main id="main" class="site-main cell position-5 width-7" role="main">
	Inhalt
	<?php if ( have_posts() ) : ?>
	
		<?php if ( is_home() && ! is_front_page() ) : ?>
			<header>
				<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
			</header>
		<?php endif; ?>
	
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
	
			/*
			 * Include the Post-Format-specific template for the content.
			 * If you want to override this in a child theme, then include a file
			 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
			 */
			get_template_part( 'content', get_post_format() );
	
		// End the loop.
		endwhile;
	
		// Previous/next page navigation.
		the_posts_pagination( array(
			'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
			'next_text'          => __( 'Next page', 'twentyfifteen' ),
			'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
		) );
	
	// If no content, include the "No posts found" template.
	else :
		get_template_part( 'content', 'none' );
	
	endif;
	?>
</main><!-- .site-main -->

<aside id="sidebar" class="sidebar cell position-13 width-3">
	aktuelle Neuigkeiten
	<?php get_sidebar(); ?>
</aside><!-- .sidebar -->

<?php get_footer(); ?>
