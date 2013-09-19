<?php get_header(); ?>
<?php include (TEMPLATEPATH . '/topbox.php'); ?>		
<div id="boxer">
	<div id="content" class="fleft">
		<ul class="blogpost">
		<?php if(have_posts()) : ?>
 			<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
			<?php /* If this is a category archive */ if (is_category()) { ?>				
			<h2 class="pagetitle">Category: <?php echo single_cat_title(); ?></h2>
			<?php /* If this is a tag archive */ } elseif (is_tag()) { ?>
			<h2 class="pagetitle">Archive for the '<?php single_tag_title(); ?>' tag</h2>
			<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
			<h2 class="pagetitle">Archive for <?php the_time('F jS, Y'); ?></h2>
			<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<h2 class="pagetitle">Archive for <?php the_time('F Y'); ?></h2>
			<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<h2 class="pagetitle">Archive for <?php the_time('Y'); ?></h2>
			<?php /* If this is an author archive */ } elseif (is_author()) { ?>
			<h2 class="pagetitle">Author archives</h2>
			<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<h2 class="pagetitle">Blog Archives</h2>			
		<?php } ?>

		<?php while(have_posts()) : the_post(); ?>
		<li class="entry main">
			<div class="block_c fright"><a href="<?php the_permalink() ?>#comments"><?php comments_number('0','1','%'); ?></a></div>
			<div class="post_head">
				<div class="pinfo"> <?php the_time('d'); ?> <br /><span><?php the_time('M'); ?></span> </div>
				<h1 class="maintitle fleft"><a class="free" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h1>		
		  	</div>
		 	<div class="clear"></div>
			<div class="entry_post">
				<?php the_excerpt('&raquo; Read the rest of the entry.. ') ?> 
	 		</div> 	
 			<div class="block_b">
 				<p>Posted <span>by</span> <?php the_author_posts_link(); ?> <span>in</span>  <?php the_category(', ') ?></p> 
			</div>		
		</li>
		<?php endwhile; ?>
		</ul>
		<?php else: ?>
		<?php include (TEMPLATEPATH . '/notfound.php'); ?>		
		<?php endif; ?>
	
	</div><!--end of #content -->
<?php get_sidebar(); ?>
<div class="clear"></div>
</div>
<div class="pagination_bar">
	<?php
	include('wp-pagenavi.php');
	if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
	?>
	<div class="gototop"> <a href="#">Top</a></div>
	<div class="clear"></div>
</div>
<?php get_footer(); ?>