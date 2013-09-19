<?php get_header(); ?>
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
		
<!--Start Post-->
<div class="post" style="margin-bottom: 20px;">
      			
<div class="p-head">
<p class="p-date"><?php the_time('d') ?> <?php the_time('M') ?>, <?php the_time('Y') ?></p>
<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
<p class="p-who">Posted by: <?php the_author() ?> In: <?php the_category('|') ?></p>
</div>

<div class="p-con">
<?php the_content('Read the rest of this entry &raquo;'); ?>
</div>

<div class="p-com">
<?php comments_popup_link('No Comments', '<strong>1</strong> Comment', '<strong>%</strong> Comments'); ?>
</div>

<?php if (function_exists('the_tags')) { ?> <?php the_tags('<div class="p-tag">Tags: ', ', ', '</div>'); ?> <?php } ?>

</div>
<!--End Post-->

    			
<?php endwhile; ?>
<?php include("nav.php"); ?>
<?php else : ?>

<?php include("404.php"); ?>
<?php endif; ?>

<!--Track Theme-->
<?php if (function_exists('trackTheme')) { ?>
<?php trackTheme("fervens-a");  ?>
<?php } ?>
<!--Track Theme-->

<?php get_footer(); ?>
