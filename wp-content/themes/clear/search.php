<?php get_header();?>
<div id="main">
	<div id="content">
      <h2>Search Results for "<?php the_search_query(); ?>"</h2>
	    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	        <div class="post" id="post-<?php the_ID(); ?>">
            <p>
              <?php the_time('M') ?>
              <?php the_time('d') ?>
              <?php the_time('Y') ?>
          </p>
            <h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
            <div class="meta">
				      <p>Published by <?php the_author_posts_link() ?>  under <?php the_category(',') ?> <?php edit_post_link(); ?></p>
			      </div>
			      <div class="entry">
              <?php the_content(__('Continue Reading &#187;')); ?>
              <?php wp_link_pages(); ?>
      			</div>
            <p class="comments">
              <?php comments_popup_link(__('No responses yet'), __('One response so far'), __('% responses so far')); ?>
            </p>	          
	        </div>
      <?php endwhile; else: ?>
          <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
      <?php endif; ?>
      <p align="center"><?php posts_nav_link(' - ','&#171; Prev','Next &#187;') ?></p>
	</div>
  <?php get_sidebar();?>
  <?php get_footer();?>