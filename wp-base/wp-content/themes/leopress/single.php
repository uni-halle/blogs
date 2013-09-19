<?php get_header(); ?>
<?php get_sidebar(); ?>
<div id="maincontent">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post single" id="post-<?php the_ID(); ?>">
    <p class="date">Posted on <?php the_time('F j, Y'); ?> at <?php the_time('g:i a'); ?></p>
			<h2><?php the_title(); ?></h2>
			<div class="entry">
				<?php the_content('<p>Read the rest of this entry &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
    <p class="tags"><?php the_tags('Tags:', ', ', ''); ?> </p>
		</div>
    <ul class="actions">
        <li class="author">by <strong><?php the_author(); ?></strong></li>
        <li class="cats"><?php the_category(', ') ?></li>
    </ul>
						<h4 class="msg"><?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Both Comments and Pings are open ?>

						<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Only Pings are Open ?>

						<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Comments are open, Pings are not ?>
							You can skip to the end and leave a response. Pinging is currently not allowed.

						<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Neither Comments, nor Pings are open ?>
							Both comments and pings are currently closed.

						<?php } ?></h4>
		</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>
	<div class="post">
		<h1>Not Found - Error 404</h1>
		<p>Sorry, but you are looking for something that isn't here.</p>
 		<p>Please try to make a new search.</p>
</div>
<?php endif; ?>

</div><!-- #maincontent -->
<?php include (TEMPLATEPATH . '/sidebar-extra.php'); ?>

<?php get_footer(); ?>
