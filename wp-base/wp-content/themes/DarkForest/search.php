<?php get_header(); ?>
  <div id="content">
    <div class="post">
	<h2>Search Results</h2>
  
  <?php if (have_posts()) : ?>
			  
	<?php while (have_posts()) : the_post(); ?>
	<div class="post-content" id="post-<?php the_ID(); ?>">
	
		  <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h3>
		  <?php the_excerpt(); ?>
	</div>
	<?php endwhile; ?>
	
	<div class="navigation">
	  <span class="previous-entries"><?php next_posts_link('Previous Entries') ?></span> <span class="next-entries"><?php previous_posts_link('Next Entries') ?></span>
	</div>
	
  <?php else : ?>
  	<h3>Sorry, nothing found.</h3>
    <?php endif; ?>
	</div><!--/content -->
  </div><!--/content -->
  
<?php get_sidebar(); ?>

<?php get_footer(); ?>