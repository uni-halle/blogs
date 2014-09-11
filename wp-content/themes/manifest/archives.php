<?php
/*
Template Name: Archives
*/
?>

<?php get_header(); ?>

<div id="coreContent">
  <div id="archives" class="single hentry">  
    <h2 class="entry-title"><?php the_title(); ?></h2>
    
    <div id="date">
      <h3>Months</h3>
      <ul>
        <?php wp_get_archives('type=monthly'); ?>
      </ul>
    </div>
    
    <div id="categoryTags">
      <h3>Categories</h3>
      <ul class="categories">
      <?php wp_list_categories('title_li='); ?>
      </ul>

      <h3>Tags</h3>
      <?php wp_tag_cloud('format=list'); ?>

    </div>
  </div>

</div>

<?php get_footer(); ?>
