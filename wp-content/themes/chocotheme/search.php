<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Choco
 */
get_header(); ?>
	<div class="list-page">
		<?php if ( have_posts() ) : ?>
			<h1 class="pagetitle"><?php _e( 'Search results for', 'choco' ); ?> &#8216;<?php the_search_query(); ?>&#8217;</h1>

			<?php
				while ( have_posts() ) : the_post();
					get_template_part( 'content', get_post_format() );
				endwhile;
			?>

			<?php if ( $wp_query->max_num_pages > 1 ) : ?>
				<div class="post-navigation clear-fix">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'choco' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'choco' ) ); ?></div>
				</div>
			<?php endif; ?>

		<?php else : ?>

			<h1 class="pagetitle"><?php _e( 'No posts found. Try a different search?', 'choco' ); ?></h1>
			<?php get_search_form(); ?>

		<?php endif; ?>
	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>