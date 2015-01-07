<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Choco
 */
get_header(); ?>
	<div class="post">
		<h1 class="post-title"><?php _e( 'Not Found', 'choco' ); ?></h1>
		<div class="entry">
			<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'choco' ); ?></p>

			<?php get_search_form(); ?>
		</div><!-- .entry -->
	</div><!-- .post -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>