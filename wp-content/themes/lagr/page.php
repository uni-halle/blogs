<?php get_header(); ?>
<?php if (function_exists('nav_breadcrumb')) nav_breadcrumb(); ?>
<div class="sidebar">
    <?php get_sidebar(); ?>
</div>
<div id="content" role="main">
    <!-- Template page -->
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="entry">
        <?php the_content(); ?>
    </div>
    <?php endwhile; endif; ?>
<?php comments_template(); ?>
</div><!-- #content -->
<p class="clear"></p>    
<?php get_footer(); ?>