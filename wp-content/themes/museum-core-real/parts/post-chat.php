<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<?php get_template_part( 'parts/part', 'title' ); ?>

	<?php tha_entry_before(); ?>
	<section class="entry alt">
		<?php tha_entry_top(); ?>

		<?php the_content(__('Read more &raquo;','museum-core')); ?>
		<?php get_template_part( 'parts/part', 'link-pages' ); ?>

		<?php tha_entry_bottom(); ?>
	</section>
	<?php tha_entry_after(); ?>

	<div class="icon icon-comments-alt pull-left" title="<?php esc_attr_e( 'Chat', 'museum-core' ); ?>"></div><?php get_template_part( 'parts/part', 'postmetadata' ); ?>

</article>