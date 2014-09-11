<?php get_header(); ?>
<?php include (TEMPLATEPATH . '/topbox.php'); ?>		

<div id="boxer">
	<div id="content" class="fleft">	
	<ul class="blogpost">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<li class="entry main">
			<div class="block_c fright"><a href="<?php the_permalink() ?>#comments"><?php comments_number('0','1','%'); ?></a></div>
			<div class="post_head">
				<div class="pinfo"><?php the_time('d'); ?><br /><span><?php the_time('M'); ?></span> </div>
				<h1 class="maintitle fleft"><a class="free" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h1>
			</div>
		  	<div class="clear"></div>
			<div class="entry_post">
				<?php the_content('Read more') ?> 
	 		</div>	
 			<div class="block_b">
 				<p>Posted <span>by</span> <?php the_author_posts_link(); ?> <span>in</span>  <?php the_category(', ') ?></p> 
 			</div>		
		</li>
		<?php endwhile; ?>
		<?php else: ?>
		<?php include (TEMPLATEPATH . '/notfound.php'); ?>		
		<?php endif; ?>
	</ul>
	</div><!--end of #content -->
	<?php get_sidebar(); ?>
	<div class="clear"></div>
</div><!--end of #boxer -->
<div class="pagination_bar">
	<?php include('wp-pagenavi.php');
	if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>
	<div class="gototop"><a href="#">Top</a></div>
	<div class="clear"></div>
</div>
<?php get_footer(); ?>