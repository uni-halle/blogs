<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package bueno
 */

get_header(); ?>
	<div id="primary" class="span8 <?php echo esc_attr( of_get_option('blog_sidebar_pos') ) ?>">
		<div id="content" class="site-content" role="main">
	<?php
		// Include slider for latest posts home page
		get_template_part( 'templates/slider' ); 

		if (have_posts()) :
			
			if ( 'one' != of_get_option('blog_page_columns') ) {
				get_template_part( 'templates/blog-two-column' );
			} else {
				get_template_part( 'templates/blog-one-column' );
			}

			get_template_part('templates/post-nav');

		else: ?>
		
			<?php get_template_part( 'templates/no-results' ); ?>

    	<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>