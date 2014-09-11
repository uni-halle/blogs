<?php get_header(); ?>

	<div id="content">

		<h2 class="pagetitle"><?php _e('Search Results','Mimbo'); ?></h2>

		<ul class="archive-list clearfloat">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<?php include (TEMPLATEPATH . '/archivelist.php'); ?>

		<?php endwhile; ?>
		
		</ul>
		
		<?php include (TEMPLATEPATH . '/pagination.php'); ?>
        
	<?php else : ?>
		
        <h2 class="pagetitle"><?php _e('Search Results','Mimbo'); ?></h2>
		<p><?php _e('No posts found.','Mimbo'); ?></p>

	<?php endif; ?>

</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>