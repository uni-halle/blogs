<?php get_header();?>
<div id="main">
	<div id="content">
      <?php if(have_posts()) : ?>
        <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
        <?php /* If this is a category archive */ if (is_category()) { ?>
        <h2 class="post-title">
          Archive for the '<?php echo single_cat_title(); ?>' Category
        </h2>

        <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
        <h2 class="post-title">
          Archive for <?php the_time('F jS, Y'); ?>
        </h2>

        <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
        <h2 class="post-title">
          Archive for <?php the_time('F, Y'); ?>
        </h2>

        <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
        <h2 class="post-title">
          Archive for <?php the_time('Y'); ?>
        </h2>

        <?php /* If this is a search */ } elseif (is_search()) { ?>
        <h2 class="post-title">Search Results</h2>

        <?php /* If this is an author archive */ } elseif (is_author()) { ?>
        <h2 class="post-title">Author Archive</h2>

        <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
        <h2 class="post-title">Blog Archives</h2>

        <?php } ?>
      <?php endif; ?>
	    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	        <div class="post" id="post-<?php the_ID(); ?>">
            <h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
            <div class="meta">
				<p>
					<?php the_time('M') ?>
					<?php the_time('d') ?>
					<?php the_time('Y') ?>
					Published by <?php the_author_posts_link() ?>  under <?php the_category(',') ?> <?php edit_post_link(); ?>
				</p>
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