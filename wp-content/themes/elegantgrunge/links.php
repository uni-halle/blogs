<?php
/*
Template Name: Links
*/
?>

<?php get_header(); ?>
<div id="content-container">

<div id="content">

<h2><?php _e('Links', 'elegant-grunge')?></h2>
<ul>
<?php wp_list_bookmarks(); ?>
</ul>

</div>
</div>

<div class="clear"></div>
</div>

<?php get_footer(); ?>
