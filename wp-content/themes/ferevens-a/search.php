<?php get_header(); ?>
<?php if (have_posts()) : ?>


<h2 class="title">Search Results</h2>

<?php include("nav.php"); ?>
<?php while (have_posts()) : the_post(); ?>

<div class="post">

<div class="p-head">
<p class="p-date"><?php the_time('d') ?> <?php the_time('M') ?>, <?php the_time('Y') ?></p>
<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
<p class="p-who">Posted by: <?php the_author() ?> In: <?php the_category('|') ?></p>
</div>

<div class="p-con">
 <?php the_excerpt(); ?>
</div> 

<div class="p-com">
<?php comments_popup_link('No Comments', '<strong>1</strong> Comment', '<strong>%</strong> Comments'); ?>
</div>
 
<?php if (function_exists('the_tags')) { ?> <?php the_tags('<div class="p-tag">Tags: ', ', ', '</div>'); ?> <?php } ?>
</div>

<?php endwhile; ?>
<br clear="all" />	
<?php include("nav.php"); ?>
<?php else : ?>

<h2 class="title">No posts found.</h2>
<p>Try a different search!</p>
<?php endif; ?>


<?php get_footer(); ?>