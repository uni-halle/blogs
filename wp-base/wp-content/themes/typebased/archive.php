<?php get_header(); ?>

	<div class="wrap background">
		
		<div id="content" class="left-col wrap">
		
			<?php if (have_posts()) : ?>
			<?php $post = $posts[0]; ?>

			<?php if (is_category()) { ?><h2 class="arh">Archive for '<?php echo single_cat_title(); ?>'</h2>
			<?php } elseif (is_day()) { ?><h2 class="arh">Archive for <?php the_time('F jS, Y'); ?></h2>
			<?php } elseif (is_month()) { ?><h2 class="arh">Archive for <?php the_time('F, Y'); ?></h2>
			<?php } elseif (is_year()) { ?><h2 class="arh">Archive for the year <?php the_time('Y'); ?></h2>
			<?php } elseif (is_author()) { ?><h2 class="arh">Archive by Author</h2>
			<?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?><h2 class="arh">Archives</h2>
			<?php } elseif (is_tag()) { ?><h2 class="arh">Tag Archives: <?php echo single_tag_title('', true); ?></h2>	

			<?php } ?>

			<?php while (have_posts()) : the_post(); ?>
		
		<!--- Post Starts -->
		
			<div class="post wrap">
			
				<div class="post-meta left-col">
					<h3 class="wrap"><span class="month"><?php the_time('M'); ?><span class="year"><?php the_time('o'); ?></span></span><span class="day"><?php the_time('d'); ?></span></h3>
					<h4 class="author"><?php the_author_posts_link(); ?></h4>
					<h4 class="comments"><a href="<?php comments_link(); ?>"><?php comments_number('0','1','%'); ?></a></h4>
				</div>
				
				<div class="post-content right-col">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
					<?php the_content('<br /><span class="read_more">Read more</span>'); ?>
				</div>
				
			</div>
			
			<!--- Post Ends -->
			
			<?php endwhile; ?>
			
			<div class="more_posts">
				<h2><?php next_posts_link('&laquo; Older Entries') ?> &nbsp; <?php previous_posts_link ('Recent Entries &raquo;') ?></h2>
			</div>
			
			<?php endif; ?>
			
		</div>
		
		<?php get_sidebar(); ?>
		
	</div>
	
</div>
	
<?php get_footer(); ?>