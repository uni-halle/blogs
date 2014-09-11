<?php get_header(); ?>

	<div id="content_box">
	
		<div id="content" class="page">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			<h1><?php the_title(); ?></h1>
			<div class="entry">
				<?php the_content('<p>Read the rest of this page &rarr;</p>'); ?>
				<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
			</div>
			
			<?php endwhile; endif; ?>

		</div>

		<?php get_sidebar(); ?>

	</div>

<?php get_footer(); ?>