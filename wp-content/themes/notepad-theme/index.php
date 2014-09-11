<?php get_header(); ?>

	<div id="content">

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

		<div class="post">
			<h2 class="post-title"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			<p class="post-date"><?php the_time('M d') ?></p>
			<p class="post-data"><span class="postauthor"><?php the_author_link(); ?></span><span class="postcategory"><?php the_category(', ') ?></span> <?php the_tags( '<span class="posttag">', ', ', '</span>'); ?> <span class="postcomment"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></span><?php edit_post_link('[Edit]'); ?></p>
			<?php the_content('More'); ?>
		</div>
		<!--/post -->

		<?php endwhile; ?>

		<p class="post-nav"> <span class="previous"><?php next_posts_link('Older Entries') ?></span> <span class="next"><?php previous_posts_link('Newer Entries') ?></span> </p>

	<?php else : ?>

		<h2>Not Found</h2>
		<p>Sorry, but you are looking for something that isn't here.</p>

	<?php endif; ?>

	</div>
	<!--/content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>