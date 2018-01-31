<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		if ( has_post_thumbnail() ) : ?>
			<figure>
				<?php the_post_thumbnail('medium'); ?>
			</figure>
	<?php endif; ?>


	<header class="entry-header">

		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<span class="sticky-post"><?php _e( 'Featured', 'twentysixteen' ); ?></span>
		<?php endif; ?>

		<?php

				the_title( '<h2 class="entry-title">', '</h2>' );
		?>

	</header><!-- .entry-header -->

	<p class="excerpt">
		<?php
			$post_excerpt = get_the_excerpt();
			if ( '' != $post_excerpt ) : ?>
					<?php echo $post_excerpt; ?>
			<?php endif;
		?>
	</p>


</article><!-- #post-## -->
