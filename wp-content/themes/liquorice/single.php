<?php

  /**
  *@desc A single blog post See page.php is for a page layout.
  */

  get_header();

  if (have_posts()) : while (have_posts()) : the_post();
  ?>

    <div id="post-<?php the_ID(); ?>"  <?php post_class('postWrapper'); ?>>

      <h1 class="postTitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h1>
      <p class="date"><small><?php the_date(); ?> by <?php the_author(); ?></small></p>

      <div class="post">
	  <?php the_post_thumbnail(); ?>
	<?php the_content(__('(more...)')); ?></div>
	<?php wp_link_pages('before=<p class="page-link">&after=</p>&next_or_number=number&pagelink=page %'); ?>
	  <p class="postMeta">Category <?php the_category(', ') ?> | Tags: <?php the_tags(', '); ?> </p>
      <hr class="noCss" />

    </div>
    <div class="post-link">
     <div class="pagination-newer"><?php previous_post_link('%link'); ?></div> 
      <div class="pagination-older"><?php next_post_link('%link'); ?></div>
	</div>
	<?php

  comments_template();

  endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php
  endif;

  get_footer();

?>