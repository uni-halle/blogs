<?php get_header(); ?>

<div id="coreContent">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <div class="post hentry single" id="post-<?php the_ID(); ?>">
      <div class="postContent">
        <h2 class="entry-title"><?php the_title(); ?></h2>
        <div class="entry-content">
  				<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
        </div>
      </div>
   </div>

	<?php endwhile; endif; ?>

	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>

</div>



<?php get_footer(); ?>
