<?php $post_count = 1; ?>

<?php get_header(); ?>

	<div id="content_box">
	
		<div id="left_box">

			<div id="content">
<?php 		if (have_posts()) : ?>
		
				<div id="content_inner">
<?php 
				while (have_posts()) : the_post(); ?>

					<h2<?php if ($post_count == 1) echo(' class="top"'); ?>><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
					<p class="post_author"><em>by</em> <?php the_author(); ?></p>
					<div class="format_text">
<?php the_content('[Read more &rarr;]'); ?>
					</div>	
					<p class="to_comments"><span class="date"><?php the_time('F j, Y') ?></span> &nbsp; <span class="num_comments"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></span></p>
<?php
					$post_count++;
				endwhile; ?>
				
				</div>
			
				<?php include (TEMPLATEPATH . '/navigation.php'); ?>
<?php 
			else : ?>
			
				<div class="content_inner">
					<h2 class="top">There's nothing here.</h2>
					<div class="format_text">
						<p class="center">Sorry, but you are looking for something that isn't here.</p>
						<?php include (TEMPLATEPATH . "/searchform.php"); ?>
					</div>
				</div>
<?php 
			endif; ?>
	
			</div>
		
			<?php include (TEMPLATEPATH . '/left_bar.php')?>
		
		</div>

		<?php get_sidebar(); ?>

	</div>

<?php get_footer(); ?>