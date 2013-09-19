<?php
/*
Template Name: Contributors
*/
?>
<?php get_header();?>
<div id="main">

	<?php include (TEMPLATEPATH . '/sidebar_left.php'); ?>

	<div id="content">

      <div class="post">
        <h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
        <div class="entry">
<h2>Contributors:</h2>
<ul>
<?php wp_list_authors(); ?>
</ul>

        </div>
        <p class="comments"></p>	          
      </div>      
	</div>
  <?php get_sidebar();?>
  <?php get_footer();?>