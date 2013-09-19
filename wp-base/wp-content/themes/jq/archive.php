<?php get_header(); ?>
<div id="content" class="clearfix">
<?php get_sidebar(); ?>
<div id="left">          
<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
<!-- archive header -->
<?php /* If category archive */ if (is_category()) { ?>
<h2 class="archive-title">CAT | <?php single_cat_title(); ?></h2>
<?php /* If tag archive */ } elseif( is_tag() ) { ?>
<h2 class="archive-title">TAG | <?php single_tag_title(); ?></h2>
<?php /* If daily archive */ } elseif (is_day()) { ?>
<h2 class="archive-title">Archive for <?php the_time('F jS, Y'); ?></h2>
<?php /* If monthly archive */ } elseif (is_month()) { ?>
<h2 class="archive-title">Archive for <?php the_time('F Y'); ?></h2>
<?php /* If yearly archive */ } elseif (is_year()) { ?>
<h2 class="archive-title">Archive for <?php the_time('Y'); ?></h2>
<?php /* If paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
<h2 class="archive-title">Blog Archive</h2>
<?php } ?>
<?php include (TEMPLATEPATH . "/wp-loop.php"); ?>
</div>
</div>
<?php get_footer(); ?>
