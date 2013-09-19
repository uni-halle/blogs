<?php get_header(); ?>
<div id="content-container">

	<div id="content">
	<div id="body">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">
		
		
			<h2><?php the_title(); ?></h2>

			<?php if ( get_option("show_author") ) : ?>
			<div class="author"><?php the_author() ?></div>
			<?php endif ;?>

			<div class="entry">
				<?php the_content(); ?>
			</div>

			<div class="clear"></div>
			
			<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages', 'elegant-grunge').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			<div class="metadata">
				<?php the_tags( '<p>'.__('Tags:', 'elegant-grunge').' ', ', ', '</p>'); ?>

					<?php 
					printf(__('This entry was posted on %1$s at %2$s', 'elegant-grunge'), get_the_time(__('l, F jS, Y', 'elegant-grunge')), get_the_time());
					if ( count(($categories=get_the_category())) > 1 || $categories[0]->cat_ID != 1 ) {
						printf(__('and is filed under %s', 'elegant-grunge'), join(', ', array_map(create_function('$item', 'return $item->cat_name;'), get_the_category(', '))));
					}
					_e('. ', 'elegant-grunge');
					printf(__('You can follow any responses to this entry through the <a href="%s">RSS 2.0</a> feed.', 'elegant-grunge'), get_post_comments_feed_link());
					?>

					<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
						// Both Comments and Pings are open ?>
						<?php printf(__('You can %1$sleave a response%2$s, or %3$strackback%4$s from your own site.', 'elegant-grunge'),
							'<a href="#respond">', '</a>', '<a href="'.get_trackback_url().'" rel="trackback">', '</a>') ?>

					<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
						// Only Pings are Open ?>
						<?php printf(__('Responses are currently closed, but you can %1$strackback%2$s from your own site.', 'elegant-grunge'),
							'<a href="'.get_trackback_url().'" rel="trackback">', '</a>') ?>

					<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
						// Comments are open, Pings are not ?>
						<?php _e('You can skip to the end and leave a response. Pinging is currently not allowed.', 'elegant-grunge') ?>

					<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
						// Neither Comments, nor Pings are open ?>
						<?php _e('Both comments and pings are currently closed.', 'elegant-grunge') ?>

					<?php } edit_post_link(__('Edit this entry', 'elegant-grunge'),'','.'); ?>

				</div>
			</div>
			<div class="hr"><hr /></div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p><?php _e('Sorry, no posts matched your criteria.', 'elegant-grunge') ?></p>

	<?php endif; ?>

	</div> <!-- End body /-->

	<?php if ( get_option('page_setup') != 'no-sidebar'  ) get_sidebar(); ?>

	</div>
	
	<div class="clear"></div>
</div>
<?php get_footer(); ?>
