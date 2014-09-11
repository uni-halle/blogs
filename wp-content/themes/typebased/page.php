<?php get_header(); ?>

	<div class="wrap background">
		
		<div id="content" class="left-col wrap">
		
		<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		
		<!--- Post Starts -->
		
			<div class="post wrap page">
				
				<div class="post-content right-col">
					<h2><?php the_title(); ?></h2>
					<?php the_content(); ?>	
					
					<?php endwhile; ?>

					<?php endif; ?>

					<?php comments_template(); ?>
					
				</div>
				
			</div>
			
			<!--- Post Ends -->
			
			
		</div>
		
		<?php get_sidebar(); ?>
		
	</div>
	
</div>
	
<?php get_footer(); ?>