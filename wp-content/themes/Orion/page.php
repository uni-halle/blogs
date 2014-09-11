<?php get_header(); ?>
<div class="eleven columns ">
<div id="content" >
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<article class="post" id="post-<?php the_ID(); ?>">
	<div class="title">
		<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
	</div>
	<div class="entry">
		<?php the_content('Read the rest of this entry &raquo;'); ?>
		<div class="clear"></div>
	</div>
</article>
<?php endwhile; endif; ?>
</div>		
</div>	
<?php get_sidebar(); ?>
<?php get_footer(); ?>