<?php get_header(); ?>
<!-- Container -->
<div class="CON">

<!-- Start SC -->
<div class="SC">

<?php include (TEMPLATEPATH . '/searchform.php'); ?>

<h2>Archives by Month:</h2>
<ul>
 <?php wp_get_archives('type=monthly'); ?>
</ul>

<h2>Archives by Subject:</h2>
<ul>
 <?php wp_list_categories(); ?>
</ul>

</div> 
<!-- End SC -->
<?php get_sidebar(); ?>


<!-- Container -->
</div>
<?php get_footer(); ?>
