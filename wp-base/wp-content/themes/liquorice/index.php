<?php

  get_header();

  if (have_posts()): ?>
	
  <ol id="posts">
  <?php while (have_posts()) : the_post(); ?>

    <li id="post-<?php the_ID(); ?>" <?php post_class('postWrapper'); ?>>
   

      <h2 class="postTitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
      
      <p class="date"><small><?php the_time('F j, Y'); ?> by <?php the_author(); ?></small></p>
	
      <div class="post">
      <?php the_post_thumbnail(); ?>

	  <?php the_content(__('(more...)')); ?></div>
      <?php wp_link_pages('before=<p class="page-link">&after=</p>&next_or_number=number&pagelink=page %'); ?>
      <p class="postMeta">Category <?php the_category(', ') ?> | Tags: <?php the_tags(', '); ?> | <?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></p>
	
      <hr class="noCss" />
    </li>

    <?php comments_template(); // Get wp-comments.php template ?>

    <?php endwhile; ?>

  </ol>

<?php else: ?>

  <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>

  <?php endif; ?>


 <div class="pagination-older"><?php next_posts_link('Older Entries'); ?></div>
<div class=" pagination-newer"><?php previous_posts_link('Newer Entries'); ?></div> 


    



  <?php
  get_footer();
?>