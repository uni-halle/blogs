<?php
/**
 * The template part for displaying post thumbnail.
 *
 * @package bueno
 */
?> 
<?php if(!is_singular()) : ?>
	<?php if ( has_post_thumbnail() ) {	?>
		<?php if ( 'noimg' != of_get_option('post_image_size') ) {	?>
			<?php if ( 'one' != of_get_option('blog_page_columns') ) { ?>
			<a class="thumb-link" href="<?php the_permalink(); ?>" title="<?php esc_attr_e('Permalink to:', 'bueno');?> <?php the_title(); ?>"><figure class="featured-thumbnail thumbnail"><?php the_post_thumbnail('featured-thumb'); ?></figure></a>
			<?php } else { ?>
			<a class="thumb-link" href="<?php the_permalink(); ?>" title="<?php esc_attr_e('Permalink to:', 'bueno');?> <?php the_title(); ?>"><figure class="featured-thumbnail thumbnail"><?php the_post_thumbnail('large-thumb'); ?></figure></a>
			<?php } ?>
		<?php } ?>
	<?php }	?>
<?php else :?>
	<?php 
		$hide_th = 0;
		global $multipage, $numpages, $page;
		if ($multipage && (1 < $page) ) {
			$hide_th = 1;
		}
		if ( has_post_thumbnail() && ( 1 != $hide_th  ) ) {	?>
		<?php if ( 'noimg' != of_get_option('single_image_size') ) {	?>
			<figure class="featured-thumbnail thumbnail"><?php the_post_thumbnail('large-thumb'); ?></figure>
		<?php } ?>
	<?php }	?>
<?php endif; ?>