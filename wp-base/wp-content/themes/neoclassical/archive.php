<?php $post_count = 1; ?>

<?php get_header(); ?>
		
	<div id="content_box">

		<div id="left_box">

			<div id="content" class="archives">
	
			<?php if (have_posts()) : ?>
			
				<?php if (is_category()) { ?>				
				<h1>Category &#8212; <span><?php echo single_cat_title(); ?></span></h1>
				<?php } elseif (is_month()) { ?>
				<h1>Posts from &#8212; <span><?php the_time('F Y'); ?></span></h1>
				<?php } ?>
			
				<div id="content_inner">
					<?php while (have_posts()) : the_post(); ?>
			
					<h2<?php if ($post_count == 1) echo(' class="top"'); ?>><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
					<p class="post_author"><em>by</em> <?php the_author(); ?></p>
					<div class="format_text">
<?php the_content('[Read more &rarr;]'); ?>
					</div>
					<p class="to_comments"><span class="date"><?php the_time('F j, Y') ?></span> &nbsp; <span class="num_comments"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></span></p>
			
					<?php $post_count++; endwhile; ?>
			
				</div>
			
				<?php include (TEMPLATEPATH . '/navigation.php'); ?>
			
			<?php endif; ?>
			
			</div>

			<?php include (TEMPLATEPATH . '/left_bar.php'); ?>
		
		</div>
	
		<?php get_sidebar(); ?>
		
	</div>
		
<?php get_footer(); ?>