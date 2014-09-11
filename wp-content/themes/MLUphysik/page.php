<?php
/**
 * @package WordPress
 * @subpackage MLUphysik Theme
 */
?>
<?php get_header(); ?>
<!-- page.php -->
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="post clearfix">

    <div class="entry clearfix">	
    
    <h1><?php the_title(); ?></h1>

    <?php the_content(); ?>
	</div>
	<!-- END entry -->
    
    <?php comments_template(); ?>  
    
</div>
<!-- END post -->

<?php endwhile; ?>
<?php endif; ?>	  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
