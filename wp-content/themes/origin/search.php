<?php
/**
 * Search Template
 *
 * The search template is loaded when a visitor uses the search form to search for something
 * on the site.
 *
 * @package Origin
 * @subpackage Template
 */

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // origin_before_content ?>

	<div id="content">

		<?php do_atomic( 'open_content' ); // origin_open_content ?>

		<div class="hfeed">

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php do_atomic( 'before_entry' ); // origin_before_entry ?>

						<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

							<?php do_atomic( 'open_entry' ); // origin_open_entry ?>

							<h1 class="post-title entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

							<div class="byline"><a href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a> &middot; by <?php the_author_posts_link(); ?></div>

							<div class="entry-summary">
								
								<?php the_excerpt(); ?>
								
								<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'origin' ), 'after' => '</p>' ) ); ?>
								
							</div><!-- .entry-summary -->

							<?php do_atomic( 'close_entry' ); // origin_close_entry ?>

						</div><!-- .hentry -->

					<?php do_atomic( 'after_entry' ); // origin_after_entry ?>

				<?php endwhile; ?>

			<?php else : ?>

				<?php get_template_part( 'loop-error' ); // Loads the loop-error.php template. ?>

			<?php endif; ?>

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // origin_close_content ?>

		<?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template. ?>

	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // origin_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>