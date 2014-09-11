<?php get_header(); ?>
				
		<div class="page">
			
			<p class="leader">Latest from the blog</p>
			
			<?php if (have_posts()) : ?>
		
				<?php while (have_posts()) : the_post(); ?>
			
					<div class="entry">
			
						<div class="excerpt">
				
							<?php the_excerpt(); ?> 
				
							<cite>&mdash; <a href="<?php the_permalink() ?>" rel="bookmark" title="Continue reading <?php the_title(); ?>"><?php the_title(); ?></a></cite>
					
						</div>
					
					</div>		
	
				<?php endwhile; ?>		
		
			<?php else : ?>
		
				<p>Sorry, but you are looking for something that isn't here.</p>

			<?php endif; ?>
	
			<p class="leader">Must reads</p>
	
			<div class="favs">
	
				<?php query_posts('tag=lead&showposts=10'); ?>
	
				<?php while (have_posts()) : the_post(); ?>
	
					<h5 class="must_read"><a href="<?php the_permalink() ?>" rel="bookmark" title="Continue reading <?php the_title(); ?>"><?php the_title(); ?></a></h5>
		
				<?php endwhile; ?>
			
		</div>
	
<?php get_footer(); ?>