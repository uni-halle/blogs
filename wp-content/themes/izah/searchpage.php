<?php
/**
 * Template Name: Suchseite
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
	<header class="page-header">
		<h1 class="page-title">
			<?php echo esc_attr( bloginfo( 'name' ) ) 
				. ' '
 				. __( 'durchsuchen', 'cleantraditional' ); ?>
		</h1>
	</header>
	
	<?php get_search_form(); ?>
</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
