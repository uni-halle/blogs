<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.WordPress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Choco
 */
get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php get_template_part( 'content', get_post_format() );?>

<?php endwhile; ?>

<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<div class="post-navigation clear-fix">
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'choco' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'choco' ) ); ?></div>
	</div><!-- .post-navigation -->
<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>