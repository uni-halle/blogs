<?php get_header(); ?>

	<div id="content">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
			<h2><?php the_title(); ?></h2>
			
			<?php the_content() ?>
			
		<?php endwhile; endif; ?>

	</div>
	
</div> <!-- end main -->

<?php get_footer(); ?>
