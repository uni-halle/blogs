<?php get_header(); ?>

<div class="home fix">
  <div class="main">
    <div class="fix">


			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<h2><?php the_title(); ?></h2>
				<div class="entry" id="post-<?php the_ID(); ?>">
					<?php the_content('<p class="serif">Den Rest weiterlesen &raquo;</p>'); ?>

					<?php wp_link_pages(array('before' => '<p><strong>Seiten:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

				</div>

			<?php endwhile; endif; ?>
		<?php edit_post_link('Bearbeite diesen Eintrag.', '<p>', '</p>'); ?>


<!-- page navi -->
<div class="pagenavi">
<?php if(function_exists('wp_pagenavi')) { wp_pagenavi('', '', '', '', 3, false);} ?>
</div>
<!-- page navi end -->



  </div>
  </div>



          <div class="sidebarwrapper">
           <?php include (TEMPLATEPATH . '/left.php'); ?>
           <?php include (TEMPLATEPATH . '/right.php'); ?>
          </div>

</div>



<?php include (TEMPLATEPATH . '/ancillary.php'); ?>
<?php get_footer(); ?>