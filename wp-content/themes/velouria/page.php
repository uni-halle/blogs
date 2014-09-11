<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<div class="page">
		
			<h2><?php the_title(); ?></h2>
			
			<?php the_content(); ?>
	
		</div>
		
	<?php endwhile; endif; ?>
	  
	<?php edit_post_link('Edit this entry.', '<span class="edit">', '</span>'); ?>
	
<?php get_footer(); ?>