<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Choco
 */
get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<div class="post-navigation clear-fix">
		<div class="nav-previous">
			<?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'choco' ) . '</span> %title' ); ?>
		</div>
		<div class="nav-next">
			<?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'choco' ) . '</span>' ); ?>
		</div>
	</div><!-- .post-navigation -->

	<?php get_template_part( 'content', get_post_format() );?>

	<div class="post-navigation clear-fix">
		<div class="nav-previous">
			<?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'choco' ) . '</span> %title' ); ?>
		</div>
		<div class="nav-next">
			<?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'choco' ) . '</span>' ); ?>
		</div>
	</div><!-- .post-navigation -->

	<?php comments_template(); ?>

<?php endwhile; else: ?>
	<p><?php _e( 'Sorry, but nothing matched your search criteria.', 'choco' ); ?></p>
<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>