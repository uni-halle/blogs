<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package bueno
 */

get_header(); ?>

	<div id="primary" class="span8 <?php echo esc_attr( of_get_option('blog_sidebar_pos') ) ?>">
		<div id="content" class="site-content" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'bueno' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->

			<?php if ( 'one' != of_get_option('blog_page_columns') ) {
				get_template_part( 'templates/blog-two-column' );
			} else {
				get_template_part( 'templates/blog-one-column' );
			} 
			get_template_part('templates/post-nav'); ?>

		<?php else : ?>

			<?php get_template_part( 'templates/no-results' ); ?>

		<?php endif; ?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>