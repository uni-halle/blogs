<?php get_header();?>
<div id="main">
	<div id="content">
	    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	        <div class="post" id="post-<?php the_ID(); ?>">
            
            <h1 class="title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h1>
            <div class="meta">
		<p>
              <?php the_time('M') ?>
              <?php the_time('d') ?>
              <?php the_time('Y') ?>
 <?php edit_post_link(); ?></p>
			      </div>
			      <div class="entry">
              <?php the_content(__('Continue Reading &#187;')); ?>
              <?php wp_link_pages(); ?>
			  <div class="tags"><?php the_tags('Tags: ', ', ', '<br />'); ?></div>
      			</div>



            <p class="comments">
              <?php comments_popup_link(__('No responses yet'), __('One response so far'), __('% responses so far')); ?>
            </p>
	          <?php comments_template(); // Get wp-comments.php template ?>
	        </div>
      <?php endwhile; else: ?>
          <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
      <?php endif; ?>

      
	</div>
  <?php get_sidebar();?>
  <?php get_footer();?>