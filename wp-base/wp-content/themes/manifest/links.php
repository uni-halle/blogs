<?php
/*
Template Name: Links
*/
?>
<?php get_header(); ?>

<div id="coreContent">
  <div id="links" class="single hentry">  
    <div class="postContent">
    <h2 class="entry-title"><?php the_title(); ?></h2>
    <div class="entry-content">
    
    <ul id="linkList">
    <?php wp_list_bookmarks('show_images=0&title_before=<h3>&title_after=</h3>&show_description=1&link_after=<br />'); ?>
    </ul>
    </div>
    </div>
  </div>

</div>

<?php get_footer(); ?>
