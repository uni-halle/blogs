<?php
/**
 * @package Duotone
 */
?>

<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class( 'image' ); ?>>
		<div class="nav prev"><?php next_post_link( '%link','&lsaquo;'); ?></div>
		<div id="image"><?php echo Duotone::get_singular_image(); ?></div>
		<div class="nav next"><?php previous_post_link( '%link','&rsaquo;'); ?></div>
	</div>
	<?php get_template_part( 'post' ); ?>

<?php endwhile; else : ?>

	<h2><?php _e( 'Not Found', 'duotone' ); ?></h2>
	<p><?php _e( "Sorry, but you are looking for something that isn't here.", 'duotone' ); ?></p>
	<?php get_search_form(); ?>
<?php endif; ?>

<div class="navigation">
	<div class="prev"><?php next_post_link( '%link', '&rsaquo;' ); ?></div>
	<div class="next"><?php previous_post_link( '%link', '&lsaquo;' ); ?></div>
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
