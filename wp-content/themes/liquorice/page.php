<?php

  /**
  *@desc A page. See single.php is for a blog post layout.
  */

  get_header();

  if (have_posts()) : while (have_posts()) : the_post();
  ?>

    <div id="post-<?php the_ID(); ?>"  <?php post_class('postWrapper'); ?>>

      <h1 class="postTitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h1>
        
      <div class="post"><?php the_content(__('(more...)')); ?></div>
      <?php wp_link_pages('before=<p class="page-link">&after=</p>&next_or_number=number&pagelink=page %'); ?>
      <p class="postMeta"><?php edit_post_link(__('Edit'), ''); ?></p>
    </div>

  
  <?php comments_template(); // Get wp-comments.php template ?>
  <?php
  

  endwhile; else: ?>

    <p>Sorry, no pages matched your criteria.</p>

<?php
  endif;

  get_footer();
?>