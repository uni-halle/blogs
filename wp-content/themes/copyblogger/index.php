<?php get_header(); ?>

	<div id="content_box">
	
		<div id="content">

		<?php if (have_posts()) : ?>
			
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
	
			<h2>If you're seeing this, it's time to go slap somebody.</h2>
			<p class="post_date">* * *</p>
			<div class="entry">
				<p>Sorry, but you are looking for something that isn't here.</p>
				<?php include (TEMPLATEPATH . "/searchform.php"); ?>
			</div>
	
		<?php endif; ?>
		
		</div>

		<?php get_sidebar(); ?>
	
	</div>

<?php get_footer(); ?>