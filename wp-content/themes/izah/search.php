<?php
/**
 * The template for displaying search results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
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

		<header class="page-header">
			<h1 class="page-title">
				<?php 
					global $wp_query;
					printf( __( '%d Suchergebnisse f&uuml;r: <em>%s</em>', 'cleandtraditional' ), 
							$wp_query->found_posts,
							get_search_query()
					);
				?>
			</h1>
		</header>
		
		<?php get_search_form(); ?>

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post(); ?>

			<?php
			/*
			 * Run the loop for the search to output the results.
			 * If you want to overload this in a child theme then include a file
			 * called content-search.php and that will be used instead.
			 */
			get_template_part( 'template-parts/content', 'search' );

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