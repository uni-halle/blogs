<?php $post_count = 1; ?>

<?php get_header(); ?>
		
	<div id="content_box">

		<div id="left_box">

			<div id="content" class="archives">
	
			<?php if (have_posts()) : ?>

				<h1>Search Results for "<span><?php echo $s; ?></span>"</h1>

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
			
			<?php else : ?>
		
				<div id="content_inner">
					<h1>Welp, we couldn't find that...try again?</h1>
					<div class="format_text">
						<?php include (TEMPLATEPATH . '/searchform.php'); ?>
					</div>
				</div>
		
			<?php endif; ?>
			
			</div>

			<?php include (TEMPLATEPATH . '/left_bar.php'); ?>
		
		</div>
	
		<?php get_sidebar(); ?>
		
	</div>
		
<?php get_footer(); ?>