<?php
/*
Template Name: Special
*/
?>

<?php get_header(); ?>
<div id="content">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="post" id="post-<?php the_ID(); ?>">

<h1 class="pagetitle"><?php the_title(); ?></h1>

<div class="special">
<?php the_content(); ?>
</div><!-- End Special -->
</div><!-- End Post -->

<?php endwhile; else: ?><p class="sorry"><?php _e("Sorry, but you are looking for something that isn't here. Try something else.",'avenue'); ?></p>
<?php endif; ?>

</div><!-- End Content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>