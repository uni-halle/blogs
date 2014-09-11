<?php get_header(); ?>

<div id="content-container">
<div id="content">
<div id="body">
	
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
		<h2><?php the_title(); ?></h2>
			<div class="entry">
				<?php the_content(__('<p class="serif">Read the rest of this page &raquo;</p>', 'elegant-grunge')); ?>

				<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages:', 'elegant-grunge').'</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			</div>
		</div>
		<?php endwhile; endif; ?>
		<?php edit_post_link(__('Edit this entry.', 'elegant-grunge'), '<p>', '</p>'); ?>

</div>

<?php if ( get_option('page_setup') != 'no-sidebar' ) get_sidebar(); ?>
</div>

<div class="clear"></div>
</div>

<?php get_footer(); ?>