<?php get_header(); ?>
<?php include (TEMPLATEPATH . '/topbox.php'); ?>		
<div id="boxer">
	<div id="content" class="fleft">
		<ul class="blogpost">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<li class="entry">
				<h1 class="maintitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h1>
				<div class="entry_post">
					<?php the_content('Read More') ?> 
	 			</div>	
			</li>
		
		<?php endwhile; ?>
		</ul>
		<?php else: ?>
		<?php include (TEMPLATEPATH . '/notfound.php'); ?>
		<?php endif; ?>
 	</div>
<?php get_sidebar(); ?>
<div class="clear"></div>
</div>
<?php get_footer(); ?>