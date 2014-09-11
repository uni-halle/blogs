<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>


<div class="post" id="post-<?php the_ID(); ?>" style="margin-bottom: 20px;">

<div class="p-head">
<p class="p-date"><?php the_time('d') ?> <?php the_time('M') ?>, <?php the_time('Y') ?></p>
<h2><?php the_title(); ?></h2>
<p class="p-who">Posted by: <?php the_author() ?> In: <?php the_category('|') ?></p>
</div>


<div class="p-con">
<?php the_content('Read the rest of this entry &raquo;'); ?>
</div>
 
<?php if (function_exists('the_tags')) { ?> <?php the_tags('<div class="p-tag">Tags: ', ', ', '</div>'); ?> <?php } ?>

 
</div>	


<?php comments_template(); ?>
<?php endwhile; else: ?>

<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>
<?php get_footer(); ?>
