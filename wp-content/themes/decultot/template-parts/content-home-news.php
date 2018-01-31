<?php
/**
 * The template part for displaying news posts content on home page
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	if (has_post_thumbnail()) : ?>
		<figure>
			<?php the_post_thumbnail('medium'); ?>
		</figure>
	<?php endif; ?>

	<header class="entry-header">

		<?php

		the_title(sprintf('<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h3>');

		?>

	</header><!-- .entry-header -->

	<div class="excerpt-div">
		<p class="excerpt">
			<?php
			$post_excerpt = get_the_excerpt();
			if ('' != $post_excerpt) {
				echo $post_excerpt;
			}
			?>
		</p>
		<p class="read-more"><a href="<?php the_permalink() ?>"><?php _e('mehr', 'dct'); ?></a></p>
	</div>

</article><!-- #post-## -->
