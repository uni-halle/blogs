<?php get_header();?>
<div id="main">
	<div id="content">
	    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	        <div class="post" id="post-<?php the_ID(); ?>">
            <h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			      <div class="entry">
              <?php the_content(__('Continue Reading &#187;')); ?>
              <?php wp_link_pages(); ?>
              <?php $sub_pages = wp_list_pages( 'sort_column=menu_order&depth=1&title_li=&echo=0&child_of=' . $id );?>
              <?php if ($sub_pages <> "" ){?>
              <p class="meta">This page has the following sub pages.</p>
              <ul>
                <?php echo $sub_pages; ?>
              </ul>
              <?php }?>
            </div>
            <p class="comments">
              <?php comments_popup_link(__('No responses yet'), __('One response so far'), __('% responses so far')); ?>
            </p>
	          <?php comments_template(); // Get wp-comments.php template ?>
	        </div>
      <?php endwhile; else: ?>
          <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
      <?php endif; ?>
      <p align="center"><?php posts_nav_link(' - ','&#171; Prev','Next &#187;') ?></p>
	</div>
  <?php get_sidebar();?>
  <?php get_footer();?>