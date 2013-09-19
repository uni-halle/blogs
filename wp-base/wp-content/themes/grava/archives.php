<?php
/*
Template Name: Blog-Archiv
*/
?>
<?php get_header(); ?>

        <h2><a href="#hometop">Archiv nach Kategorien</a></h2>
        <ul>
          <?php wp_list_categories('show_count=1&title_li='); ?>
        </ul>
        <!--
        <h2><a href="#hometop">Archiv nach Tags</a></h2>
        <ul>
          <?php wp_tag_cloud('smallest=10&largest=20&unit=px&number=0'); ?>
        </ul>
        -->
        <h2><a href="#hometop">Archiv nach Monaten</a></h2>
        <ul>
          <?php wp_get_archives('show_post_count=1&type=monthly'); ?>
        </ul>
        
        <h2><a href="#hometop">Alle Seiten</a></h2>
        <ul>
          <?php wp_list_pages('title_li=' ); ?>
        </ul>
      
        <h2><a href="#hometop">Alle Beitr&auml;ge (chronologisch)</a></h2>
        <ul>
          <?php wp_get_archives('type=postbypost'); ?>
        </ul>
        
<?php get_sidebar(); ?>
<?php get_footer(); ?>
