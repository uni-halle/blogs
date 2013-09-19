<?php get_header(); ?>
	
	<div id="content">
	
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
			                
            <h2><?php the_title(); ?></h2>
				
				<div class="entry">
					<?php the_content(); ?>
				</div><!--entry-->  				
				
			</div><!--post-->				
		
		<?php endwhile; endif; ?>
	
	</div><!--content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>