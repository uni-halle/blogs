<?php get_header(); ?>
<!-- Container -->
<div class="CON">

<!-- Start SC -->
<div class="SCS">

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<h1><?php the_title(); ?></a></h1>
<?php the_content("<p>__('Read the rest of this page &raquo;')</p>"); ?>
<?php edit_post_link(__('Edit'), '<p>', '</p>'); ?>
<?php endwhile; endif; ?>
</div> 
<!-- End SC -->
<?php get_sidebar(); ?>


<!-- Container -->
</div>

<?php get_footer(); ?>