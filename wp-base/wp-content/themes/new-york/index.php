<?php get_header(); ?>

	<div id="content">

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
			<div <?php post_class(); ?>>
			<?php if (is_sticky()) echo '<div class="stickyribbon"></div>'; ?>
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
			<?php if (strlen($post->post_title) > 50) {
			echo substr(the_title($before = '', $after = '', FALSE), 0, 50) . '...'; } else {
				the_title();
			} ?>	
				</a></h2>
				<small><?php the_time('F jS, Y') ?> by <?php the_author() ?> received <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></small>

				<div class="entry">
					<?php the_content('<div class="moretext">Read the rest of this post &raquo;</div>'); ?>
				</div>

				<p class="postmetadata"><?php if (function_exists('the_tags')) { the_tags('Tags: ', ', ', '<br/>'); } ?>Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>
			</div>
</div>
		<?php endwhile; ?>

		<div class="navigation">
			<div class="newerposts"><?php next_posts_link('&laquo; Older Posts') ?></div>
			<div class="olderposts"><?php previous_posts_link('Newer Posts &raquo;') ?></div>
		</div>

	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		<br />
		<?php include (TEMPLATEPATH . "/searchform.php"); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
