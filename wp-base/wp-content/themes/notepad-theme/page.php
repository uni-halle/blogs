<?php get_header(); ?>

	<div id="content">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post">
			<h2 class="post-title"><?php the_title(); ?></h2>
			<?php the_content('More'); ?>
			<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		</div>
		<!--/post -->

	<?php endwhile; endif; ?>
	
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>

	<?php comments_template(); ?>

	</div>
	<!--/content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>