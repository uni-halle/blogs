<?php
/**
 * Die Hauptseite des Templates.
 *
 * Die Index.php - die Hauptseite des Templates
 *
 * @package WordPress
 * @subpackage Deutschdidaktik
 */

get_header(); ?>
<?php // if (function_exists('nav_breadcrumb')) nav_breadcrumb(); ?>
<div class="sidebar">
    <?php get_sidebar(); ?>
</div>

<div id="content" role="main">
    <!-- Template index -->
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php if (!in_category(get_query_var('cat'))):?>
    <!-- skipping <?php echo $cCat.' '; the_permalink();?> -->
    <?php continue ?>
    <?php endif ?>
    
    <h2><?php the_title(); echo " ".get_the_category()->cat_ID; ?></h2>
    <div class="entry">
        <?php the_content(); ?>
    </div>
    <?php endwhile ; endif ?>
</div><!-- #content -->
<p class="clear"></p>    
<?php get_footer(); ?>