<?php get_header(); ?>
<div class="eleven columns ">
<div id="content">

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
		
<article class="post" id="post-<?php the_ID(); ?>">
<div class="title">
<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
</div>

<div class="postmeta">
	<span class="author">Posted by <?php the_author(); ?> </span> <span class="clock">  <?php the_time('M - j - Y'); ?></span>
</div>

<div class="entry">
<?php
if ( has_post_thumbnail() ) { ?>
	<img class="postimg scale-with-grid" src="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php get_image_url(); ?>&amp;h=180&amp;w=300&amp;zc=1" alt="" />
<?php } else { ?>
	<img class="postimg scale-with-grid" src="<?php bloginfo('stylesheet_directory'); ?>/timthumb.php?src=<?php bloginfo('template_directory'); ?>/images/dummy.jpg&amp;h=180&amp;w=300&amp;zc=1" alt="" />
<?php } ?>

<?php wpe_excerpt('wpe_excerptlength_archive', ''); ?>
<div class="clear"></div>
</div>


<div class="singleinfo">
<span class="categori">Categories: <?php the_category(', '); ?> </span>
</div>

</article>
<?php endwhile; ?>

<?php getpagenavi(); ?>

<?php else : ?>

	<h1 class="title">Not Found</h1>
	<p>Sorry, but you are looking for something that isn't here.</p>

<?php endif; ?>

</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>