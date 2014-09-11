<?php get_header(); ?>

	<div id="content_box">
	
		<div id="content" class="archive">

		<?php if (have_posts()) : ?>
	
			<h1>Search Results for '<?php echo $s; ?>' &darr;</h1>
	
			<?php while (have_posts()) : the_post(); ?>		
			
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
			<p class="post_date"><?php the_time('F jS, Y') ?> &#8212; <?php the_category(', ') ?></p>
			<div class="entry">
				<?php the_content("Continue reading &rarr;"); ?>
			</div>
			<p class="post_meta"><span class="add_comment"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></span></p>
					
			<?php endwhile; ?>
			
			<?php include (TEMPLATEPATH . '/navigation.php'); ?>
		
		<?php else : ?>
	
			<h2>Hmm, no results... try again?</h2>
			<p class="post_date">* * *</p>
			<div class="entry">
				<?php include (TEMPLATEPATH . '/searchform.php'); ?>
			</div>
		
		<?php endif; ?>
			
		</div>

		<?php get_sidebar(); ?>

	</div>

<?php get_footer(); ?>