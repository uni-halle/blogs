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
 * @package Esquire
 */

get_header(); ?>

	<?php if ( is_search() ) : ?>
	<div id="header">
		<h2>
			<?php printf( __( 'Matches for: &ldquo;%s&rdquo; &hellip;', 'esquire' ), '<span>' . get_search_query() . '</span>' ); ?>
		</h2>
	</div>
	<?php endif; ?>


	<div id="posts">
		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', get_post_format() ); ?>

			<?php endwhile; ?>

		<?php else : ?>

		<div class="hentry error404">
			<div class="postbody text">
				<h1><?php _e( 'Nothing Found', 'esquire' ); ?></h1>
				<div class="content">
					<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'esquire' ); ?></p>
				</div><!-- .content -->
			</div><!-- .postbody -->
		</div>

		<?php endif; ?>
	</div><!-- #posts -->

<?php get_footer(); ?>