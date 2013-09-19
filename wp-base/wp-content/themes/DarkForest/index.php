<?php get_header(); ?>

<div class="blog-body">
  <div id="content">
  
  <?php if (have_posts()) : ?>
  
  	<?php while (have_posts()) : the_post(); ?>
  
    <div class="post" id="post-<?php the_ID(); ?>">
	  <div class="post-date"><span class="post-month"><?php the_time('M') ?></span> <span class="post-day"><?php the_time('d') ?></span></div>
	  <div class="post-title">
	  <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
		<span class="post-cat"><?php the_category(', ') ?></span> <span class="post-comments"><?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></span>

	  </div>
	  <div class="entry">
		<?php the_content('Read the rest of this entry &raquo;'); ?>
	  </div>
      <div>Tags : <?php the_tags('<span class="tag block">', ', ', '</span>'); ?></div>
      <center><div class="post-end"></div></center>
	</div><!--/post -->
	
	<?php endwhile; ?>
	
	<div class="navigation">
	  <span class="previous-entries"><?php next_posts_link('Previous Entries') ?></span> <span class="next-entries"><?php previous_posts_link('Next Entries') ?></span>
	</div>
	
	<?php else : ?>
	
		<h2>Not Found</h2>
		<p>Sorry, but you are looking for something that isn't here.</p>
		
  <?php endif; ?>
	
  </div><!--/content -->
  
<?php get_sidebar(); ?>

</div>
<?php get_footer(); ?>
