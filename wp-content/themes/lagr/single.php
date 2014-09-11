<?php get_header(); ?>
<?php if (function_exists('nav_breadcrumb')) nav_breadcrumb(); ?>
<div class="sidebar">
    <?php get_sidebar(); ?>
</div>
<div id="content" role="main">
    <!-- Template single -->
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
   		<h2><?php the_title(); ?></h2>
   		<div class="entry">
    		<?php the_content(); ?>
   		</div> <!-- .entry -->
	<?php endwhile; endif; ?>
</div><!-- #content -->
<p class="clear"></p>    
<?php get_footer(); ?>