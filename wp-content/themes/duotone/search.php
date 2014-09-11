<?php
/**
 * @package Duotone
 */
?>

<?php get_header(); rewind_posts(); ?>

<div class="search">

	<?php if ( have_posts() ) : ?>
		<h2><?php _e( 'Search Results', 'duotone' ); ?></h2>

		<div class="nav">
			<div class="prev"><?php next_posts_link( __( '&larr; Older Entries', 'duotone' ) ); ?></div>
			<div class="next"><?php previous_posts_link( __( 'Newer Entries &rarr;', 'duotone' ) ); ?></div>
		</div>

		<ul class="thumbnails">
			<?php while ( have_posts() ) : the_post(); ?>
				<li id="post-<?php the_ID(); ?>">
					<a href="<?php the_permalink(); ?>" title="Link to <?php the_title_attribute(); ?>"><?php echo Duotone::get_archive_image(); ?></a>
				</li>
			<?php endwhile; ?>
		</ul>

		<div class="nav">
			<div class="prev"><?php next_posts_link( __( '&larr; Older Entries', 'duotone' ) ); ?></div>
			<div class="next"><?php previous_posts_link( __( 'Newer Entries &rarr;', 'duotone' ) ); ?></div>
		</div>

	<?php else : ?>

		<h2><?php _e( 'Not Found', 'duotone' ); ?></h2>
		<?php get_search_form(); ?>

	<?php endif; ?>
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
