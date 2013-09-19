<?php get_header(); ?>

	<div id="content">

		<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
	
		<div <?php post_class() ?>>
			
			<p class="date"><?php the_time('j.'); ?>
				<span><?php the_time('M'); ?></span>
				<?php the_time('Y'); ?>
			</p>
	       	
	       	<h1 id="post-<?php the_ID(); ?>"><?php the_title(); ?></h1>
	      	<p class="author">Verfasst von <?php the_author_posts_link(); ?></p>
	
	        	<div class="entrytext">
	           		<?php the_content('<p>| Weiterlesen ...</p>'); ?>
	           		<?php link_pages('<p><strong>Seiten:</strong> ', '</p>', 'number'); ?>
					
					<p class="postmetadata category-line"><?php the_category(', ') ?></p>
					<p class="postmetadata tags-line"><?php the_tags('', ', ', ''); ?></p>
					<p class="postmetadata"><?php edit_post_link('Bearbeiten ', '', ''); ?></p>
					
					<?php if ( function_exists('socialshareprivacy') ) { socialshareprivacy(); } ?>
					
					<?php authorbox(); ?>
					
				</div>
			
		</div>
	
	<?php comments_template('', true); ?>
	
	<div class="pagenavigation2">
		<div class="alignright"><?php next_post_link('%link'); ?></div>
		<div class="alignleft"><?php previous_post_link('%link'); ?></div>
	</div>
	
	<?php endwhile; ?>
	
	<?php else: ?>
	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
	<?php endif; ?>
	
</div>

<?php get_sidebar('Sidebar'); ?>

<?php get_footer(); ?>


