<?php
/**
 * @package WordPress
 * @subpackage MLUphysik Theme
 * Template Name: Full-Width
 */
?>

<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="entry full-width clearfix">
<h1><?php the_title(); ?></h1>	
<?php the_content(); ?>
</div>
<!-- END .post -->
<div class="full-width">
<?php comments_template(); ?>
</div>
<?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>