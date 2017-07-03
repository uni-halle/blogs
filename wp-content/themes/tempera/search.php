<?php
/**
 * The template for displaying Search results pages.
 *
 * @package Cryout Creations
 * @subpackage Tempera
 * @since Tempera 1.0
 */

get_header(); ?>

		<section id="container" class="<?php echo tempera_get_layout_class(); ?>">
			<div id="content" role="main">
	<?php cryout_before_content_hook(); ?>
	
			<?php if ( have_posts() ) : ?>

				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'tempera' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				<div class="contentsearch"><?php get_search_form(); ?></div>
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

									<?php
				/* Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called loop-search.php and that will be used instead.
				 */
				 get_template_part( 'content/content', get_post_format() );
				?>
										<?php endwhile; ?>

				<?php if ( $temperas['tempera_pagination']=="Enable" ) tempera_pagination(); else tempera_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'tempera' ); ?></h1>
					</header><!-- .entry-header -->
					<h4><?php printf( __( 'No search results for: %s.', 'tempera' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
					<br><div class="contentsearch"><?php get_search_form(); ?></div>
				</article><!-- #post-0 -->
	
				<?php endif; ?>


			<?php cryout_after_content_hook(); ?>
			</div><!-- #content -->
		<?php tempera_get_sidebar(); ?>
		</section><!-- #primary -->

<?php get_footer(); ?>
