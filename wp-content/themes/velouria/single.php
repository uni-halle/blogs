<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post">
		
			<h2 class="article_title"><?php the_title(); ?></h2>
	
			<div class="entry">
			
				<?php the_content(); ?>
				
				<h3>What next?</h3>
		
				<ul class="turnpage">	
		
					<?php next_post_link('<li class="next">Next post: %link</li>'); ?>
					
					<?php previous_post_link('<li class="previous">Previous post: %link</li>'); ?>
		
				</ul>
				
			</div>
			
			<p class="postmetadata">This was posted on <?php the_time('l, F jS, Y') ?> at <?php the_time() ?>. You can follow any responses through the <?php comments_rss_link('RSS 2.0'); ?> feed.  <?php edit_post_link('Edit this entry.','',''); ?></p>
				
		</div>

		<?php comments_template(); ?>
	
	<?php endwhile; else: ?>
	
		<p>Sorry, no posts matched your criteria.</p>
	
	<?php endif; ?>
	
<?php get_footer(); ?>